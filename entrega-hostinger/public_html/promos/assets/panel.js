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
  if (['admin', 'businesses'].includes(name) && currentUser?.rol !== 'admin') name = 'overview';
  document.querySelectorAll('[data-panel]').forEach(panel => panel.hidden = panel.dataset.panel !== name);
  document.querySelectorAll('[data-tab]').forEach(tab => { if (tab.dataset.tab === name) tab.setAttribute('aria-current', 'page'); else tab.removeAttribute('aria-current'); });
}
function clearSessionView() {
  currentUser = null; dashboard = null; selectedPrize = null; resetTarget = null; page = 1;
  for (const id of ['metrics', 'prize-rows', 'distribution', 'business-cards', 'promotion-cards', 'users-list', 'events-list', 'audit-list', 'rules-view', 'lookup-result', 'temporary-password', 'session-name']) $(`#${id}`).replaceChildren();
  $('#lookup-result').hidden = true; $('#redeem-form').hidden = true; $('#admin-tab').hidden = true;
  $('#lookup-form').reset(); $('#redeem-form').reset(); $('#password-form').reset();
  document.querySelectorAll('dialog').forEach(dialog => dialog.close()); clearOwnerCredentials(); activateTab('overview');
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
  $('#businesses-tab').hidden = currentUser.rol !== 'admin';
  $('#dashboard-role').textContent = currentUser.rol === 'admin' ? 'SUPERADMINISTRADOR' : 'DUEÑO DE NEGOCIO';
  $('#promotion-new').disabled = !data.negocios.length;
  $('#owner-new').disabled = !data.negocios.some(b => Number(b.activo));
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
  $('#promotion-cards').replaceChildren(...data.promociones.map(p => {
    const card = el('article', undefined, 'business-card');
    const exhausted = p.cupo_total !== null && Number(p.entregados) >= Number(p.cupo_total);
    const status = !Number(p.activa) ? 'pausada' : !Number(p.negocio_activo) ? 'negocio deshabilitado' : exhausted ? 'agotada' : 'activo';
    card.append(el('small', p.negocio), el('h3', p.titulo), badge(status), el('p', p.descripcion || 'Sin descripción adicional.'), el('p', p.condiciones), el('small', `Emitidos: ${p.entregados} · Cupo: ${p.cupo_total ?? 'Sin límite'}`));
    const button = el('button', 'Editar promoción', 'outline'); button.type = 'button'; button.addEventListener('click', () => editPromotion(p)); card.append(button); return card;
  }));
  if (!data.promociones.length) $('#promotion-cards').append(el('p', 'Todavía no hay promociones. Crea la primera oferta de tu negocio.', 'empty-state'));
  $('#business-cards').replaceChildren(...data.negocios.map(b => {
    const card = el('article', undefined, 'business-card');
    const mark = el('span', b.nombre.slice(0, 1).toUpperCase(), 'business-avatar');
    card.append(mark, el('h3', b.nombre), badge(Number(b.activo) ? 'activo' : 'deshabilitado'), el('p', b.categoria), el('p', `WhatsApp: ${b.whatsapp || 'Pendiente'} · ${b.direccion || 'Dirección pendiente'}`));
    card.append(el('small', `${data.promociones.filter(p => Number(p.negocio_id) === Number(b.id)).length} promociones`));
    if (currentUser.rol === 'admin') { const button = el('button', 'Editar negocio', 'outline'); button.type = 'button'; button.addEventListener('click', () => editBusiness(b)); card.append(button); }
    return card;
  }));
  if (!data.negocios.length) $('#business-cards').append(el('p', 'Agrega tu primer negocio para asignarle un dueño y crear promociones.', 'empty-state'));
  if (currentUser.rol === 'admin') {
    const rules = data.reglas;
    const frequency = rules.frecuencia === 'cada_visitas' ? `Cada ${rules.cada_visitas} visitas adicionales` : rules.frecuencia === 'cada_dias' ? `Cada ${rules.cada_dias} días` : 'Una oportunidad de bienvenida';
    $('#rules-view').replaceChildren(...[
      ['Oportunidad inicial y frecuencia', frequency], ['Vigencia de los códigos', `${rules.vigencia_horas} horas`],
      ['Invitaciones', rules.referidos_habilitados ? `Un giro por cada ${rules.amigos_requeridos} visitas confirmadas` : 'Desactivadas'],
      ['Al redimir una promoción', rules.premiar_redencion ? 'Se entrega una nueva oportunidad' : 'Sin giro adicional'],
    ].map(([label, value]) => { const row = el('div', undefined, 'list-row'); row.append(el('span', label), el('strong', value)); return row; }));
    $('#users-list').replaceChildren(...data.usuarios.map(u => {
      const row = el('div', undefined, 'list-row'), label = el('div', u.usuario), actions = el('div', undefined, 'row-actions');
      label.append(el('small', `${u.negocio || 'Chapitour'} · ${u.rol === 'admin' ? 'Superadministrador' : 'Dueño'} · ${Number(u.activo) ? 'Activo' : 'Desactivado'}`));
      if (Number(u.id) !== Number(currentUser.id) && Number(u.activo)) { const b = el('button', 'Restablecer contraseña', 'subtle'); b.type = 'button'; b.addEventListener('click', () => reset(u)); actions.append(b); }
      if (u.rol === 'aliado') { const b = el('button', Number(u.activo) ? 'Desactivar acceso' : 'Activar acceso', 'subtle'); b.type = 'button'; b.addEventListener('click', async () => { b.disabled = true; try { await makeAjaxRequest('estado_dueno', { id: u.id, activo: !Number(u.activo) }); await load(); notice('Estado del acceso actualizado.'); } catch (error) { handle(error); } finally { b.disabled = false; } }); actions.append(b); }
      row.append(label, actions); return row;
    }));
    $('#events-list').replaceChildren(...data.eventos.map(e => { const row = el('div', undefined, 'list-row'); row.append(el('span', e.tipo.replaceAll('_', ' ')), el('strong', e.total)); return row; }));
    $('#audit-list').replaceChildren(...data.auditoria.map(a => { const row = el('div', undefined, 'list-row'), label = el('div', a.accion.replaceAll('_', ' ')); label.append(el('small', `${a.usuario || 'Sistema'} · ${date(a.creado_at)}`)); row.append(label); return row; }));
  }
}
function showPrize(prize) {
  selectedPrize = prize; const box = $('#lookup-result'); box.hidden = false; box.className = 'lookup-prize'; box.replaceChildren(badge(prize.estado), el('h3', prize.negocio), el('p', prize.titulo), el('p', prize.condiciones), el('code', prize.codigo), el('small', `Vence: ${date(prize.vence_at)}${prize.redimido_at ? ` · Redimido: ${date(prize.redimido_at)}` : ''}`));
  $('#redeem-form').hidden = prize.estado !== 'activo'; $('#redeem-form').reset();
}
function businessOptions(select, activeOnly = false) {
  select.replaceChildren(...dashboard.negocios.filter(b => !activeOnly || Number(b.activo)).map(b => { const option = el('option', b.nombre); option.value = b.id; return option; }));
}
function editPromotion(p = null) {
  const form = $('#promotion-form'); form.reset(); businessOptions(form.elements.negocio_id);
  for (const name of ['id', 'titulo', 'descripcion', 'condiciones', 'porcentaje', 'cupo_total']) form.elements[name].value = p?.[name] ?? '';
  if (p) form.elements.negocio_id.value = p.negocio_id;
  form.elements.negocio_id.disabled = Boolean(p) || currentUser.rol !== 'admin';
  form.elements.activa.checked = Boolean(Number(p?.activa));
  $('#promotion-dialog-title').textContent = p ? 'Editar promoción' : 'Nueva promoción';
  $('#edit-error').textContent = ''; $('#edit-dialog').showModal();
}
function editBusiness(b = null) {
  const form = $('#business-form'); form.reset();
  for (const name of ['id', 'nombre', 'categoria', 'whatsapp', 'direccion']) form.elements[name].value = b?.[name] ?? '';
  form.elements.activo.checked = b ? Boolean(Number(b.activo)) : true;
  $('#business-dialog-title').textContent = b ? 'Editar negocio' : 'Nuevo negocio';
  $('#business-error').textContent = ''; $('#business-dialog').showModal();
}
function clearOwnerCredentials() { $('#owner-created-name').textContent = ''; $('#owner-created-password').textContent = ''; $('#owner-created').hidden = true; }
function newOwner() { clearOwnerCredentials(); const form = $('#owner-form'); form.reset(); form.hidden = false; businessOptions(form.elements.negocio_id, true); $('#owner-error').textContent = ''; $('#owner-dialog').showModal(); }
async function saveForm(form, errorSelector, action, data, after) {
  const dialog = form.closest('dialog');
  const buttons = [...dialog.querySelectorAll('button')]; buttons.forEach(b => b.disabled = true); dialog.dataset.busy = 'true'; $(errorSelector).textContent = '';
  try { const result = await makeAjaxRequest(action, data); await after(result); }
  catch (error) { $(errorSelector).textContent = error.message; if (!dialog.open || ['AUTH_REQUIRED', 'PASSWORD_CHANGE_REQUIRED'].includes(error.code)) { dialog.close(); handle(error); } }
  finally { buttons.forEach(b => b.disabled = false); delete dialog.dataset.busy; }
}
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
document.querySelectorAll('[data-close]').forEach(button => button.addEventListener('click', () => $(`#${button.dataset.close}`).close()));
document.querySelectorAll('dialog').forEach(dialog => dialog.addEventListener('cancel', event => { if (dialog.dataset.busy) event.preventDefault(); }));
$('#promotion-new').addEventListener('click', () => editPromotion());
$('#business-new').addEventListener('click', () => editBusiness());
$('#owner-new').addEventListener('click', newOwner);
$('#owner-dialog').addEventListener('close', clearOwnerCredentials);
$('#promotion-form').addEventListener('submit', e => {
  e.preventDefault(); const form = e.currentTarget;
  saveForm(form, '#edit-error', 'guardar_promocion', { ...formData(form), negocio_id: form.elements.negocio_id.value, activa: form.elements.activa.checked }, async () => { $('#edit-dialog').close(); await load(); notice('Promoción guardada.'); });
});
$('#business-form').addEventListener('submit', e => {
  e.preventDefault(); const form = e.currentTarget;
  saveForm(form, '#business-error', 'guardar_negocio', { ...formData(form), activo: form.elements.activo.checked }, async () => { $('#business-dialog').close(); await load(); notice('Negocio guardado. Ya puedes asignarle un dueño y crear sus promociones.'); });
});
$('#owner-form').addEventListener('submit', e => {
  e.preventDefault(); const form = e.currentTarget;
  saveForm(form, '#owner-error', 'crear_dueno', formData(form), async result => {
    form.hidden = true; $('#owner-created-name').textContent = `Usuario: ${result.usuario}`; $('#owner-created-password').textContent = result.password_temporal; $('#owner-created').hidden = false;
    await load();
  });
});
$('#reset-close').addEventListener('click', () => $('#reset-dialog').close());
$('#reset-dialog').addEventListener('close', () => { $('#temporary-password').textContent = ''; });
$('#reset-confirm').addEventListener('click', async e => { e.target.disabled = true; try { const result = await makeAjaxRequest('reset_password', { id: resetTarget }); $('#temporary-password').textContent = result.password_temporal; $('#temporary-password').hidden = false; e.target.hidden = true; } catch(error) { $('#reset-error').textContent = error.message; } finally { e.target.disabled = false; } });
// Do not send empty date fields on the initial load.
const today = new Date(); const isoDay = d => new Intl.DateTimeFormat('en-CA', { timeZone: 'America/Bogota', year: 'numeric', month: '2-digit', day: '2-digit' }).format(d);
$('#filters').elements.hasta.value = isoDay(today); $('#filters').elements.desde.value = isoDay(new Date(today.getTime() - 29 * 86400000));
load().catch(error => { if (error.code === 'AUTH_REQUIRED') { currentUser = null; section('login-section'); } else if (error.code === 'PASSWORD_CHANGE_REQUIRED') { currentUser = { usuario: 'Cuenta', cambiar_password: 1 }; $('#password-cancel').hidden = true; section('password-section'); } else handle(error); });
