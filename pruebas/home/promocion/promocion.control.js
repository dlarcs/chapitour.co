import { createAjaxClient } from '../../promos/assets/makeAjaxRequest.js';
import { PromocionUI } from './promocion.ui.js';
export class PromocionControl {
  constructor(dialog) {
    this.ui = new PromocionUI(dialog); this.request = createAjaxClient(new URL('../../promos/api/index.php', import.meta.url)); this.spinning = false; this.requestId = null; this.refreshing = false;
    this.ui.launcher.addEventListener('click', async () => { await this.refresh(true); this.ui.open(); await this.markShown(); });
    this.ui.$('#chapi-promo-spin').addEventListener('click', () => this.girar());
    dialog.addEventListener('close', () => this.track('modal_cerrado'));
    this.ui.$('#chapi-promo-invite').addEventListener('click', () => this.ui.show('share'));
    this.ui.$('#chapi-promo-invite-start').addEventListener('click', () => this.ui.show('share'));
    dialog.querySelectorAll('[data-promo-back]').forEach(b => b.addEventListener('click', () => this.ui.show(this.state?.premios.length ? 'result' : 'unavailable')));
    for (const id of ['chapi-promo-again', 'chapi-promo-use-opportunity']) this.ui.$(`#${id}`).addEventListener('click', async () => { this.ui.wheel.render(this.state.negocios); this.ui.show('wheel'); await this.markShown(); });
    this.ui.$('#chapi-promo-copy').addEventListener('click', () => this.copiarCodigo());
    this.ui.$('#chapi-promo-copy-link').addEventListener('click', async () => { try { await navigator.clipboard.writeText(this.state.invitacion_url); this.ui.text('#chapi-promo-invite-status', 'Enlace de invitación copiado.'); } catch { this.ui.text('#chapi-promo-invite-status', `Copia este enlace: ${this.state.invitacion_url}`); } });
    this.ui.$('#chapi-promo-share-message').addEventListener('click', () => this.track('compartir_whatsapp'));
    this.ui.$('#chapi-promo-claim').addEventListener('click', event => { if (event.currentTarget.getAttribute('aria-disabled') === 'true') event.preventDefault(); else this.track('reclamar_whatsapp', this.ui.prize.id); });
    this.ui.$('#chapi-promo-history').addEventListener('change', e => { const p = this.state.premios.find(p => p.id === Number(e.target.value)); if (p) this.ui.renderPrize(p); });
    this.ui.$('#chapi-promo-refresh').addEventListener('click', () => this.refresh());
    this.ui.$('#chapi-promo-retry').addEventListener('click', () => this.refresh(true));
    document.addEventListener('visibilitychange', () => { if (!document.hidden && this.state) this.refresh(); });
    setInterval(() => { if (dialog.open && !document.hidden && this.state) this.refresh(); }, 30000);
    document.getElementById('chapi-confirm-referral').addEventListener('click', () => this.confirmarReferido());
    document.getElementById('chapi-dismiss-referral').addEventListener('click', () => { document.getElementById('chapi-referral-banner').hidden = true; });
  }
  apply(state, redraw = true) { this.state = state; this.ui.renderState(state, redraw); }
  async iniciar() {
    try {
      const query = new URL(location.href).searchParams;
      const state = await this.request('iniciar', { ref: query.get('ref') || '' }); this.apply(state); this.selectScreen();
      if (state.abrir_automaticamente && !document.getElementById('chapi-promo-demo')?.open) { this.ui.open(); await this.markShown(); }
      if (state.referido_pendiente) { const banner = document.getElementById('chapi-referral-banner'); banner.hidden = false; let wait = state.referido_espera; const button = document.getElementById('chapi-confirm-referral'); const update = () => { button.disabled = wait > 0; button.textContent = wait > 0 ? `Confirmar mi visita (${wait}s)` : 'Confirmar mi visita'; wait--; }; update(); const timer = setInterval(() => { update(); if (wait < 0) clearInterval(timer); }, 1000); }
    } catch (error) { this.ui.launcher.hidden = false; this.ui.show('unavailable'); this.ui.error(error.message); }
  }
  selectScreen() { this.ui.show(this.state.oportunidades && this.state.negocios.length ? 'wheel' : this.state.premios.length ? 'result' : 'unavailable'); }
  async markShown() { if (!this.state?.oportunidad_mostrar || this.ui.screen !== 'wheel') return; try { await this.request('mostrado', { oportunidad_id: this.state.oportunidad_mostrar }); this.state.oportunidad_mostrar = null; } catch { /* The unconsumed opportunity can be shown again on a later visit. */ } }
  async refresh(select = false) {
    if (this.spinning || this.refreshing) return; this.refreshing = true;
    try { const state = await this.request(this.state ? 'estado' : 'iniciar'); this.apply(state); this.ui.error(); if (select) this.selectScreen(); }
    catch (error) { this.ui.error(error.message); } finally { this.refreshing = false; }
  }
  async girar() {
    if (this.spinning || !this.state?.oportunidades) return;
    this.spinning = true; this.ui.busy(true); this.ui.error();
    this.requestId ||= crypto.randomUUID ? crypto.randomUUID() : ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c => (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16));
    try {
      const result = await this.request('girar', { solicitud_id: this.requestId });
      result.estado.receivedAt = Date.now();
      await this.ui.wheel.animate(result.premio.negocio_id);
      this.requestId = null; this.apply(result.estado, false); this.ui.renderPrize(result.premio); this.ui.show('result');
      this.ui.text('#chapi-promo-announcement', `Tu premio está en ${result.premio.negocio}: ${result.premio.titulo}.`);
    } catch (error) { this.ui.error(error.message); if (['NO_OPPORTUNITY', 'NO_PROMOTIONS', 'CAMPAIGN_PAUSED'].includes(error.code)) this.requestId = null; }
    finally { this.spinning = false; this.ui.busy(false); this.ui.$('#chapi-promo-spin').disabled = !this.state?.oportunidades || !this.state?.negocios.length; }
  }
  async track(tipo, premio_id = null) { try { await this.request('evento', { tipo, premio_id }); } catch { /* Navigation telemetry must not block the customer. */ } }
  async copiarCodigo() { if (!this.ui.prize) return; try { await navigator.clipboard.writeText(this.ui.prize.codigo); this.ui.text('#chapi-promo-copy-status', 'Código copiado.'); this.track('codigo_copiado', this.ui.prize.id); } catch { const input = this.ui.$('#chapi-promo-code'); input.focus(); input.select(); this.ui.text('#chapi-promo-copy-status', 'Código seleccionado. Usa Copiar en tu dispositivo.'); } }
  async confirmarReferido() { const b = document.getElementById('chapi-confirm-referral'); b.disabled = true; try { const result = await this.request('confirmar_referido'); document.getElementById('chapi-referral-feedback').textContent = result.message; b.hidden = true; } catch (error) { document.getElementById('chapi-referral-feedback').textContent = error.message; b.disabled = false; } }
}
