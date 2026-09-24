import { createAjaxClient } from './makeAjaxRequest.js';
import { prizeCard } from './prize-card.js';
const request = createAjaxClient(new URL('../api/index.php', import.meta.url));
const $ = selector => document.querySelector(selector);
let state, status = '', page = 1, authMode = 'register', loading = false, expiryTimer;
const notice = (message, error = false) => { const n = $('#customer-notice'); n.textContent = message; n.hidden = !message; n.classList.toggle('error', error); };
async function refresh() {
  if (loading) return;
  loading = true; $('#customer-refresh').disabled = true;
  try { const data = await request('cliente_panel', { estado: status, pagina: page }); state = data; page = data.pagina; render(data); }
  catch (error) { notice(error.message, true); }
  finally { loading = false; $('#customer-refresh').disabled = false; }
}
function render(data) {
  const account = data.cliente;
  $('#customer-name').textContent = account ? `HOLA, ${account.nombre.toLocaleUpperCase('es-CO')}` : 'TU ESPACIO EN CHAPITOUR';
  $('#account-title').textContent = account ? 'Tu historial está guardado en tu cuenta' : 'Explora sin registro';
  $('#account-description').textContent = account ? `Conectado como ${account.email}. Tus promociones te acompañan cuando inicias sesión en otro dispositivo.` : 'Guardamos tus promociones en este navegador. Crea una cuenta para conservarlas y consultarlas desde otros dispositivos.';
  $('#register-open').hidden = Boolean(account); $('#login-open').hidden = Boolean(account); $('#customer-logout').hidden = !account;
  for (const name of ['total', 'activos', 'redimidos', 'vencidos']) $(`#count-${name}`).textContent = data.resumen[name];
  $('#customer-total').textContent = `${data.resumen.total} códigos en tu historial`;
  $('#customer-level').textContent = data.nivel.nombre;
  $('#level-description').textContent = data.siguiente_nivel ? `${data.resumen.redimidos} promociones redimidas. Te faltan ${data.redenciones_para_siguiente} para llegar a ${data.siguiente_nivel.nombre}.` : `${data.resumen.redimidos} promociones redimidas. Completaste las metas de esta ruta de prueba.`;
  $('#level-track').replaceChildren(...data.niveles.map(level => { const step = document.createElement('div'); step.className = `level-step${Number(data.resumen.redimidos) >= level.redenciones ? ' is-reached' : ''}`; const title = document.createElement('strong'); title.textContent = level.nombre; const target = document.createElement('small'); target.textContent = `${level.redenciones} promociones redimidas`; step.append(title, target); return step; }));
  $('#visit-progress').textContent = `${data.visitas} visitas registradas. ${data.visitas === 0 ? 'Entra a la ruleta para recibir tu primera oportunidad.' : `Faltan ${data.visitas_para_proxima} visitas para la siguiente oportunidad por visitas.`}`;
  $('#available-spins').textContent = `${data.oportunidades} ${data.oportunidades === 1 ? 'giro disponible' : 'giros disponibles'}`;
  $('#referral-title').textContent = `Invita a ${data.amigos_requeridos} personas`;
  $('#referral-progress').textContent = `${data.referidos_progreso} de ${data.amigos_requeridos} visitas confirmadas para tu próximo giro extra. ${data.referidos_total} en total.`;
  $('#customer-invite').href = `https://wa.me/?text=${encodeURIComponent(`¡Prueba las promociones de Chapitour! Abre mi enlace y confirma tu visita: ${data.invitacion_url}`)}`;
  $('#customer-prizes').replaceChildren(...data.premios.map(p => prizeCard(p, prize => request('evento', { tipo: 'reclamar_whatsapp', premio_id: prize.id }).catch(() => {}))));
  if (!data.premios.length) { const empty = document.createElement('p'); empty.className = 'empty-state'; empty.textContent = status ? `No tienes códigos en estado ${status}.` : 'Tu próxima aventura empieza con un giro. Aquí aparecerán todos tus códigos.'; $('#customer-prizes').append(empty); }
  document.querySelectorAll('[data-status]').forEach(button => button.setAttribute('aria-pressed', String(button.dataset.status === status)));
  $('#customer-page').textContent = `Página ${data.pagina} de ${data.paginas}`;
  $('#customer-previous').disabled = data.pagina <= 1; $('#customer-next').disabled = data.pagina >= data.paginas;
  clearTimeout(expiryTimer);
  const expiries = data.premios.filter(p => p.estado === 'activo').map(p => Date.parse(p.vence_at) - Date.parse(data.ahora));
  if (expiries.length) expiryTimer = setTimeout(refresh, Math.max(1000, Math.min(...expiries, 30000) + 100));
}
function openAuth(mode) {
  authMode = mode; const register = mode === 'register'; const form = $('#customer-auth-form'); form.reset();
  $('#auth-name-label').hidden = !register; form.elements.nombre.required = register; form.elements.nombre.disabled = !register;
  $('#auth-repeat-label').hidden = !register; form.elements.repetir.required = register; form.elements.repetir.disabled = !register;
  form.elements.password.autocomplete = register ? 'new-password' : 'current-password';
  $('#auth-title').textContent = register ? 'Guarda tus promociones' : 'Bienvenido de nuevo';
  $('#auth-description').textContent = register ? 'Al crear tu cuenta vincularemos las promociones de este navegador. No perderás tus códigos.' : 'Inicia sesión para recuperar tu historial. También vincularemos las nuevas promociones que conseguiste en este navegador.';
  $('#auth-submit').textContent = register ? 'Crear cuenta y guardar historial' : 'Iniciar sesión';
  $('#auth-error').textContent = ''; $('#customer-auth').showModal();
}
$('#register-open').addEventListener('click', () => openAuth('register'));
$('#login-open').addEventListener('click', () => openAuth('login'));
$('#auth-close').addEventListener('click', () => $('#customer-auth').close());
$('#customer-auth').addEventListener('close', () => { $('#customer-auth-form').reset(); });
$('#customer-auth-form').addEventListener('submit', async event => {
  event.preventDefault(); const form = event.currentTarget; const data = Object.fromEntries(new FormData(form));
  if (authMode === 'register' && data.password !== data.repetir) { $('#auth-error').textContent = 'Las contraseñas no coinciden.'; return; }
  $('#auth-submit').disabled = true; $('#auth-error').textContent = '';
  try { await request(authMode === 'register' ? 'cliente_registro' : 'cliente_login', data); $('#customer-auth').close(); page = 1; status = ''; await refresh(); notice('Tu cuenta y tus promociones están vinculadas.'); }
  catch (error) { $('#auth-error').textContent = error.message; }
  finally { $('#auth-submit').disabled = false; }
});
$('#customer-logout').addEventListener('click', async event => { event.currentTarget.disabled = true; try { await request('cliente_salir'); page = 1; status = ''; await refresh(); notice('Sesión cerrada. Tus códigos siguen guardados en tu cuenta.'); } catch (error) { notice(error.message, true); } finally { $('#customer-logout').disabled = false; } });
$('#customer-refresh').addEventListener('click', refresh);
document.querySelectorAll('[data-status]').forEach(button => button.addEventListener('click', () => { if (loading) return; status = button.dataset.status; page = 1; refresh(); }));
$('#customer-previous').addEventListener('click', () => { if (!loading) { page--; refresh(); } });
$('#customer-next').addEventListener('click', () => { if (!loading) { page++; refresh(); } });
$('#customer-copy-invite').addEventListener('click', async () => { if (!state) return; try { await navigator.clipboard.writeText(state.invitacion_url); notice('Enlace de invitación copiado.'); } catch { notice(`Copia este enlace: ${state.invitacion_url}`); } });
document.addEventListener('visibilitychange', () => { if (!document.hidden) refresh(); });
setInterval(() => { if (!document.hidden) refresh(); }, 30000);
refresh();
