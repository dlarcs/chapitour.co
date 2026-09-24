import { Ruleta } from './ruleta.js';
export class PromocionUI {
  constructor(dialog) {
    this.dialog = dialog; this.launcher = document.getElementById('chapi-promo-launcher'); this.screen = 'wheel'; this.prize = null; this.clockOffset = 0;
    this.wheel = new Ruleta(this.$('#chapi-promo-wheel'));
    dialog.querySelectorAll('[data-promo-close]').forEach(b => b.addEventListener('click', () => dialog.close()));
    dialog.addEventListener('close', () => { document.documentElement.classList.remove('chapi-promo-open'); clearInterval(this.timer); this.launcher.focus({ preventScroll: true }); });
    dialog.addEventListener('click', e => { if (e.target !== dialog) return; const r = dialog.getBoundingClientRect(); if (e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom) dialog.close(); });
  }
  $(selector) { return this.dialog.querySelector(selector); }
  text(selector, text) { this.$(selector).textContent = text; }
  error(message = '') { this.text('#chapi-promo-error', message); this.$('#chapi-promo-error').hidden = !message; }
  show(name) { this.screen = name; this.dialog.querySelectorAll('[data-promo-screen]').forEach(s => s.hidden = s.dataset.promoScreen !== name); const h = this.$(`[data-promo-screen="${name}"] h2`); this.dialog.setAttribute('aria-labelledby', h.id); this.dialog.scrollTop = 0; if (this.dialog.open) h.focus({ preventScroll: true }); }
  open() { if (!this.dialog.open) this.dialog.showModal(); document.documentElement.classList.add('chapi-promo-open'); this.show(this.screen); clearInterval(this.timer); this.tick(); this.timer = setInterval(() => this.tick(), 1000); }
  busy(busy) { const b = this.$('#chapi-promo-spin'); b.disabled = busy; b.setAttribute('aria-busy', String(busy)); b.firstElementChild.textContent = busy ? 'Descubriendo tu próximo plan…' : 'Girar ruleta'; this.text('#chapi-promo-spin-status', busy ? 'Estamos preparando tu premio.' : 'Un giro de bienvenida y otro cada 5 visitas adicionales.'); }
  renderState(state, redraw = true) {
    this.state = state; this.clockOffset = Date.parse(state.ahora) - (state.receivedAt || Date.now()); this.launcher.hidden = false;
    if (redraw) this.wheel.render(state.negocios);
    this.text('#chapi-promo-wheel-caption', `${state.negocios.length} aliados con promociones disponibles.`);
    this.text('#chapi-promo-duration', `${state.vigencia_horas} horas para disfrutar`);
    this.text('#chapi-promo-available', `${state.oportunidades} ${state.oportunidades === 1 ? 'giro disponible' : 'giros disponibles'}`);
    this.$('#chapi-promo-spin').disabled = !state.oportunidades || !state.negocios.length;
    this.$('#chapi-promo-use-opportunity').hidden = !state.oportunidades || !state.negocios.length;
    const count = state.referidos_progreso, target = state.amigos_requeridos;
    this.text('#chapi-promo-invite-description', `Invita a ${target} amigos y desbloquea otra oportunidad.`);
    this.text('#chapi-promo-share-description', `Comparte tu enlace por WhatsApp. Cuando ${target} visitantes distintos entren y confirmen su visita, tendrás otro giro.`);
    this.$('#chapi-promo-invite-count').replaceChildren(document.createTextNode(`${count} `), Object.assign(document.createElement('small'), { textContent: `/ ${target}` }));
    const progress = this.$('.chapi-promo__progress'); progress.setAttribute('aria-valuemax', target); progress.setAttribute('aria-valuenow', count); this.$('#chapi-promo-progress-fill').style.width = `${count / target * 100}%`;
    this.$('#chapi-promo-friends').replaceChildren(...Array.from({ length: Math.min(target, 20) }, (_, i) => Object.assign(document.createElement('span'), { className: `chapi-promo__friend${i < count ? ' is-complete' : ''}`, textContent: i < count ? '✓' : String(i + 1) })));
    this.text('#chapi-promo-invite-status', `${state.referidos_total} visitas válidas en total. Faltan ${target - count} para la siguiente oportunidad.`);
    const link = this.$('#chapi-promo-share-message'); link.href = `https://wa.me/?text=${encodeURIComponent(`¡Hay plan en Chapinero! Entra a Chapitour, descubre sus aliados y confirma mi invitación: ${state.invitacion_url}`)}`; link.hidden = !state.referidos_habilitados;
    this.$('#chapi-promo-again').hidden = !state.oportunidades || !state.negocios.length;
    this.$('#chapi-promo-invite').hidden = !state.referidos_habilitados;
    this.$('#chapi-promo-copy-link').hidden = !state.referidos_habilitados;
    this.$('#chapi-promo-invite-start').hidden = !state.referidos_habilitados || !state.negocios.length;
    const history = this.$('#chapi-promo-history'); history.replaceChildren(...state.premios.map(p => { const o = document.createElement('option'); o.value = p.id; o.textContent = `${p.negocio} · ${p.estado} · ${p.codigo}`; return o; })); this.$('#chapi-promo-history-label').hidden = state.premios.length < 2;
    const selected = state.premios.find(p => p.id === this.prize?.id) || state.premios[0]; if (selected) this.renderPrize(selected);
    this.text('#chapi-promo-unavailable-message', state.negocios.length ? `Faltan ${state.visitas_para_proxima} visitas para tu siguiente giro. También puedes invitar a ${state.amigos_requeridos} personas o redimir una promoción.` : 'Los aliados están preparando sus promociones. Conservamos tu oportunidad y tu progreso para cuando estén disponibles.');
  }
  renderPrize(p) {
    this.prize = p; this.text('#chapi-promo-business', p.negocio); this.text('#chapi-promo-category', `${p.categoria} · CHAPINERO`); this.text('#chapi-promo-description', p.descripcion); this.text('#chapi-promo-address', `Dirección: ${p.direccion || 'Por confirmar con el negocio'}`);
    const logo = this.$('#chapi-promo-logo'); logo.hidden = !p.logo;
    if (p.logo) logo.src = new URL(`../../${p.logo}`, import.meta.url); else logo.removeAttribute('src');
    this.$('#chapi-promo-code').value = p.codigo; this.text('#chapi-promo-prize-status', p.estado); this.text('#chapi-promo-conditions', p.condiciones);
    this.text('#chapi-promo-benefit-title', p.titulo); const benefit = this.$('#chapi-promo-benefit'); benefit.replaceChildren();
    if (p.porcentaje) benefit.append(document.createTextNode(String(Number(p.porcentaje))), Object.assign(document.createElement('span'), { textContent: '%' }));
    else benefit.append(Object.assign(document.createElement('span'), { textContent: 'TU PROMO' }));
    this.text('#chapi-promo-expiry', `Vence: ${p.vence_texto}`);
    this.text('#chapi-promo-whatsapp-message', p.mensaje_whatsapp);
    const dates = new Intl.DateTimeFormat('es-CO', { dateStyle: 'medium', timeStyle: 'short', timeZone: 'America/Bogota' });
    this.$('#chapi-promo-timeline').replaceChildren(...p.seguimiento.map(step => Object.assign(document.createElement('li'), { textContent: `${step.estado.toUpperCase()} · ${dates.format(new Date(step.fecha))} · ${step.detalle}` })));
    const claim = this.$('#chapi-promo-claim'); const valid = p.estado === 'activo' && /^[1-9][0-9]{7,14}$/.test(p.whatsapp);
    if (valid && p.url_whatsapp) claim.href = p.url_whatsapp; else claim.removeAttribute('href');
    claim.setAttribute('aria-disabled', String(!valid)); this.$('#chapi-promo-history').value = p.id; this.tick();
  }
  tick() { if (!this.prize) return; const seconds = Math.max(0, Math.ceil((Date.parse(this.prize.vence_at) - Date.now() - this.clockOffset) / 1000)); this.text('#chapi-promo-countdown', this.prize.estado === 'redimido' ? 'Redimido' : !seconds ? 'Vencido' : [Math.floor(seconds / 3600), Math.floor(seconds % 3600 / 60), seconds % 60].map(n => String(n).padStart(2, '0')).join(':')); if (!seconds || this.prize.estado !== 'activo') { if (!seconds && this.prize.estado === 'activo') this.text('#chapi-promo-prize-status', 'vencido'); this.$('#chapi-promo-claim').removeAttribute('href'); this.$('#chapi-promo-claim').setAttribute('aria-disabled', 'true'); } }
}
