import { createAjaxClient } from './makeAjaxRequest.js';
const makeAjaxRequest = createAjaxClient(new URL('../api/index.php', import.meta.url));
const $ = selector => document.querySelector(selector);
const el = (tag, text, className) => { const node = document.createElement(tag); if (text !== undefined) node.textContent = text; if (className) node.className = className; return node; };
let currentUser = null, dashboard = null, page = 1, selectedPrize = null, resetTarget = null;
const dates = new Intl.DateTimeFormat('es-CO', { dateStyle: 'short', timeStyle: 'short', timeZone: 'America/Bogota' });
const date = value => value ? dates.format(new Date(value.includes('T') ? value : `${value.replace(' ', 'T')}Z`)) : '—';
function notice(message, error = false) { const n = $('#notice'); n.textContent = message; n.classList.toggle('error', error); n.hidden = !message; }
function section(name) { for (const id of ['login-section', 'password-section', 'dashboard']) $(`#${id}`).hidden = id !== name; $('#session-actions').hidden = !currentUser; }
function activateTab(name) {
  if (name === 'admin' && currentUser?.rol !== 'admin') name = 'overview';
  document.querySelectorAll('[data-panel]').forEach(panel => panel.hidden = panel.dataset.panel !== name);
  document.querySelectorAll('[data-tab]').forEach(tab => { if (tab.dataset.tab === name) tab.setAttribute('aria-current', 'page'); else tab.removeAttribute('aria-current'); });
}
function clearSessionView() {
  currentUser = null; dashboard = null; selectedPrize = null; resetTarget = null; page = 1;
  for (const id of ['metrics', 'prize-rows', 'distribution', 'business-cards', 'users-list', 'events-list', 'audit-list', 'rules-view', 'lookup-result', 'temporary-password', 'session-name']) $(`#${id}`).replaceChildren();
  $('#lookup-result').hidden = true; $('#redeem-form').hidden = true; $('#admin-tab').hidden = true;
  $('#lookup-form').reset(); $('#redeem-form').reset(); $('#password-form').reset();
  $('#edit-dialog').close(); $('#reset-dialog').close(); activateTab('overview');
}
function handle(error) { notice(error.message, true); if (error.code === 'AUTH_REQUIRED') { clearSessionView(); section('login-section'); } if (error.code === 'PASSWORD_CHANGE_REQUIRED') section('password-section'); }
async function busy(form, work) { const buttons = [...form.querySelectorAll('button')]; buttons.forEach(b => b.disabled = true); try { await work(); } catch (e) { handle(e); } finally { buttons.forEach(b => b.disabled = false); } }
function setUser(user) { currentUser = user; $('#session-name').textContent = user.negocio || user.usuario; $('#password-cancel').hidden = Boolean(Number(user.cambiar_password)); }
function badge(status) { return el('span', status, `badge ${status}`); }
function formData(form) { return Object.fromEntries(new FormData(form)); }
async function load() {
  const data = await makeAjaxRequest('panel', { ...formData($('#filters')), pagina: page });
  dashboard = data; setUser(data.usuario); section('dashboard'); render(data);
}
function render(data) {
  $('#dashboard-title').textContent = currentUser.rol === 'admin' ? 'Todos los aliados, un solo lugar.' : currentUser.negocio;
  $('#admin-tab').hidden = currentUser.rol !== 'admin';
  $('#filters').elements.desde.value = data.desde; $('#filters').elements.hasta.value = data.hasta;
  const s = data.resumen;
  const rate = Number(s.emitidos) ? `${Math.round(Number(s.redimidos) / Number(s.emitidos) * 100)} %` : '0 %';
  $('#metrics').replaceChildren(...[['Emitidos', s.emitidos], ['Activos', s.activos], ['Redimidos', s.redimidos], ['Vencidos', s.vencidos], ['Aperturas WhatsApp', s.whatsapp], ['Tasa de redención', rate]].map(([label, value]) => { const card = el('div', undefined, 'metric'); card.append(el('span', label), el('strong', value)); return card; }));
  $('#prize-total').textContent = `${data.total} códigos`;
  $('#prize-rows').replaceChildren(...data.premios.map(p => {
    const row = el('tr'), code = el('button', p.codigo, 'code-link'); code.type = 'button'; code.addEventListener('click', () => { $('#lookup-code').value = p.codigo; $('#lookup-form').requestSubmit(); $('#lookup-form').scrollIntoView({ block: 'center' }); });
    const first = el('td'); first.append(code); const business = el('td', p.negocio); business.append(el('small', p.titulo)); const state = el('td'); state.append(badge(p.estado)); row.append(first, business, state, el('td', date(p.creado_at)), el('td', date(p.vence_at)), el('td', date(p.redimido_at))); return row;
  }));
  if (!data.premios.length) { const row = el('tr'), cell = el('td', 'Todavía no hay códigos para este filtro.'); cell.colSpan = 6; row.append(cell); $('#prize-rows').append(row); }
  $('#page-label').textContent = `Página ${data.pagina} de ${data.paginas}`; $('#previous').disabled = data.pagina <= 1; $('#next').disabled = data.pagina >= data.paginas;
  const max = Math.max(1, ...data.distribucion.map(d => Number(d.emitidos)));
  $('#distribution').replaceChildren(...data.distribucion.map(d => { const row = el('div', undefined, 'distribution-row'), labels = el('div'), bar = el('div', undefined, 'bar'), fill = el('span'); labels.append(el('span', d.nombre), el('span', `${d.redimidos} / ${d.emitidos} redimidos`)); fill.style.width = `${Number(d.emitidos) / max * 100}%`; bar.append(fill); row.append(labels, bar); return row; }));
  if (!data.distribucion.length) $('#distribution').append(el('p', 'Los resultados aparecerán cuando se emitan los primeros códigos.', 'muted'));
  $('#business-cards').replaceChildren(...data.negocios.map(b => {
    const card = el('article', undefined, 'business-card'), img = el('img'); img.src = new URL(`../../${b.logo}`, import.meta.url); img.alt = ''; card.append(img, el('h3', b.nombre), badge(Number(b.promocion_activa) ? 'activo' : 'pendiente'), el('p', b.titulo), el('p', b.condiciones), el('p', `WhatsApp: ${b.whatsapp || 'Pendiente'} · ${b.direccion || 'Dirección pendiente'}`), el('small', `Emitidos: ${b.entregados} · Cupo: ${b.cupo_total ?? 'Sin límite'}`));
    if (currentUser.rol === 'admin') { const button = el('button', 'Editar promoción', 'outline'); button.type = 'button'; button.addEventListener('click', () => edit(b)); card.append(button); } return card;
  }));
  if (currentUser.rol === 'admin') {
    $('#rules-view').textContent = JSON.stringify(data.reglas, null, 2);
    $('#users-list').replaceChildren(...data.usuarios.map(u => { const row = el('div', undefined, 'list-row'), label = el('div', u.usuario); label.append(el('small', `${u.negocio || 'Administración'} · ${u.rol}`)); row.append(label); if (Number(u.id) !== Number(currentUser.id)) { const b = el('button', 'Restablecer contraseña', 'subtle'); b.type = 'button'; b.addEventListener('click', () => reset(u)); row.append(b); } return row; }));
    $('#events-list').replaceChildren(...data.eventos.map(e => { const row = el('div', undefined, 'list-row'); row.append(el('span', e.tipo.replaceAll('_', ' ')), el('strong', e.total)); return row; }));
    $('#audit-list').replaceChildren(...data.auditoria.map(a => { const row = el('div', undefined, 'list-row'), label = el('div', a.accion.replaceAll('_', ' ')); label.append(el('small', `${a.usuario || 'Sistema'} · ${date(a.creado_at)}`)); row.append(label); return row; }));
  }
}
function showPrize(prize) {
  selectedPrize = prize; const box = $('#lookup-result'); box.hidden = false; box.className = 'lookup-prize'; box.replaceChildren(badge(prize.estado), el('h3', prize.negocio), el('p', prize.titulo), el('p', prize.condiciones), el('code', prize.codigo), el('small', `Vence: ${date(prize.vence_at)}${prize.redimido_at ? ` · Redimido: ${date(prize.redimido_at)}` : ''}`));
  $('#redeem-form').hidden = prize.estado !== 'activo'; $('#redeem-form').reset();
}
function edit(b) { const form = $('#business-form'); form.reset(); for (const name of ['id', 'nombre', 'whatsapp', 'direccion', 'titulo', 'descripcion', 'condiciones', 'porcentaje', 'cupo_total']) form.elements[name].value = b[name] ?? ''; form.elements.activa.checked = Boolean(Number(b.promocion_activa)); $('#edit-error').textContent = ''; $('#edit-dialog').showModal(); }
function reset(user) { resetTarget = user.id; $('#reset-info').textContent = `Se cambiará la contraseña de ${user.usuario} (${user.negocio || 'administración'}). La clave se mostrará una sola vez.`; $('#temporary-password').hidden = true; $('#temporary-password').textContent = ''; $('#reset-error').textContent = ''; $('#reset-confirm').hidden = false; $('#reset-dialog').showModal(); }
$('#login-form').addEventListener('submit', e => { e.preventDefault(); busy(e.currentTarget, async () => { const result = await makeAjaxRequest('login', formData(e.target)); e.target.reset(); clearSessionView(); setUser(result.usuario); notice(''); if (Number(currentUser.cambiar_password)) section('password-section'); else await load(); }); });
$('#password-form').addEventListener('submit', e => { e.preventDefault(); busy(e.currentTarget, async () => { const d = formData(e.target); if (d.nueva !== d.repetir) throw new Error('Las nuevas contraseñas no coinciden.'); const result = await makeAjaxRequest('password', d); e.target.reset(); setUser(result.usuario); await load(); notice('Contraseña actualizada.'); }); });
$('#password-open').addEventListener('click', () => section('password-section'));
$('#password-cancel').addEventListener('click', () => section('dashboard'));
$('#logout').addEventListener('click', async () => { try { await makeAjaxRequest('logout'); clearSessionView(); section('login-section'); notice('Sesión cerrada.'); } catch(e) { handle(e); } });
$('#refresh').addEventListener('click', () => load().catch(handle));
$('#filters').addEventListener('submit', e => { e.preventDefault(); page = 1; load().catch(handle); });
$('#previous').addEventListener('click', () => { page = Math.max(1, page - 1); load().catch(handle); });
$('#next').addEventListener('click', () => { page++; load().catch(handle); });
$('#lookup-form').addEventListener('submit', e => { e.preventDefault(); selectedPrize = null; $('#lookup-result').hidden = true; $('#redeem-form').hidden = true; busy(e.currentTarget, async () => { const result = await makeAjaxRequest('consultar_codigo', formData(e.target)); showPrize(result.premio); notice(''); }); });
$('#redeem-form').addEventListener('submit', e => { e.preventDefault(); busy(e.currentTarget, async () => { if (!selectedPrize) return; const result = await makeAjaxRequest('redimir', { codigo: selectedPrize.codigo, confirmado: e.target.elements.confirmado.checked }); showPrize(result.premio); await load(); notice(result.nueva_oportunidad ? 'Visita confirmada. Código redimido y un nuevo giro disponible para el visitante.' : 'Visita confirmada. Código redimido.'); }); });
document.querySelectorAll('[data-tab]').forEach(button => button.addEventListener('click', () => activateTab(button.dataset.tab)));
$('#edit-close').addEventListener('click', () => $('#edit-dialog').close());
$('#business-form').addEventListener('submit', async e => { e.preventDefault(); const button = e.target.querySelector('[type=submit]'); button.disabled = true; try { await makeAjaxRequest('guardar_negocio', { ...formData(e.target), activa: e.target.elements.activa.checked }); $('#edit-dialog').close(); await load(); notice('Promoción actualizada.'); } catch(error) { $('#edit-error').textContent = error.message; } finally { button.disabled = false; } });
$('#reset-close').addEventListener('click', () => $('#reset-dialog').close());
$('#reset-dialog').addEventListener('close', () => { $('#temporary-password').textContent = ''; });
$('#reset-confirm').addEventListener('click', async e => { e.target.disabled = true; try { const result = await makeAjaxRequest('reset_password', { id: resetTarget }); $('#temporary-password').textContent = result.password_temporal; $('#temporary-password').hidden = false; e.target.hidden = true; } catch(error) { $('#reset-error').textContent = error.message; } finally { e.target.disabled = false; } });
// Do not send empty date fields on the initial load.
const today = new Date(); const isoDay = d => new Intl.DateTimeFormat('en-CA', { timeZone: 'America/Bogota', year: 'numeric', month: '2-digit', day: '2-digit' }).format(d);
$('#filters').elements.hasta.value = isoDay(today); $('#filters').elements.desde.value = isoDay(new Date(today.getTime() - 29 * 86400000));
load().catch(error => { if (error.code === 'AUTH_REQUIRED') { currentUser = null; section('login-section'); } else if (error.code === 'PASSWORD_CHANGE_REQUIRED') { currentUser = { usuario: 'Cuenta', cambiar_password: 1 }; $('#password-cancel').hidden = true; section('password-section'); } else handle(error); });
