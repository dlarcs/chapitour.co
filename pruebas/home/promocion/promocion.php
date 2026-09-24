<!-- PHP asigna el premio antes de animar la ruleta. -->
<button class="chapi-promo-launcher chapi-promo-launcher--demo" id="chapi-promo-demo-launcher" type="button" aria-haspopup="dialog" aria-controls="chapi-promo" hidden>
  <span aria-hidden="true">✳</span> Ver promoción otra vez <small>PRUEBAS</small>
</button>
<button class="chapi-promo-launcher" id="chapi-promo-launcher" type="button" aria-haspopup="dialog" aria-controls="chapi-promo" hidden>
  <span aria-hidden="true">✳</span> Chapinero te premia
</button>

<dialog class="chapi-promo" id="chapi-promo" aria-labelledby="chapi-promo-title">
  <div class="chapi-promo__shell">
    <header class="chapi-promo__header">
      <div class="chapi-promo__brand"><span aria-hidden="true">✳</span><div>chapitour.co<small>EL BARRIO TIENE ALGO PARA TI</small></div></div>
      <div class="chapi-promo__header-actions"><span class="chapi-promo__preview">Planes locales</span><button class="chapi-promo__close" type="button" aria-label="Cerrar promoción" data-promo-close>×</button></div>
    </header><p id="chapi-promo-error" class="chapi-promo__error" role="alert" hidden></p>

    <section class="chapi-promo__screen" data-promo-screen="wheel">
      <div class="chapi-promo__intro">
        <p class="chapi-promo__eyebrow"><span></span> EXPLORA. DESCUBRE. GANA.</p>
        <h2 id="chapi-promo-title" tabindex="-1">CHAPINERO<br><span>TE PREMIA.</span><svg class="chapi-promo__underline" viewBox="0 0 310 14" aria-hidden="true"><path d="M3 10Q140-3 305 5M80 13Q195 4 278 10"/></svg></h2>
        <p class="chapi-promo__lead">Tu próximo plan viene <br>con una sorpresa.</p>
        <p class="chapi-promo__description">Gira la ruleta y descubre una promoción en los negocios aliados de Chapinero.</p>
        <div class="chapi-promo__perks"><span><b aria-hidden="true">✦</b> <span id="chapi-promo-available">Tu oportunidad</span></span><span><b aria-hidden="true">◷</b> <span id="chapi-promo-duration">72 horas para disfrutar</span></span></div>
        <button class="chapi-promo__button chapi-promo__button--pink" id="chapi-promo-spin" type="button"><span>Girar ruleta</span><span aria-hidden="true">↗</span></button>
        <p class="chapi-promo__micro" id="chapi-promo-spin-status" role="status">Un giro de bienvenida y otro cada 5 visitas adicionales.</p>
      </div>

      <div class="chapi-promo__wheel-area">
        <div class="chapi-promo__orbit" aria-hidden="true"></div>
        <span class="chapi-promo__sticker" aria-hidden="true">¡HOY SE SALE!</span>
        <span class="chapi-promo__spark chapi-promo__spark--one" aria-hidden="true">✳</span>
        <span class="chapi-promo__spark chapi-promo__spark--two" aria-hidden="true">✦</span>
        <div class="chapi-promo__wheel-wrap">
          <span class="chapi-promo__pointer" aria-hidden="true"></span>
          <svg class="chapi-promo__wheel" id="chapi-promo-wheel" viewBox="0 0 400 400" role="img" aria-label="Ruleta con Street Grill, Capital Queer, Jimar Factory, Garage Disco Bar, Pictogramas y Gran y Chela Club"></svg>
          <div class="chapi-promo__wheel-hub" aria-hidden="true"><span>✳</span><b>CHAPI<br>TOUR</b></div>
        </div>
        <p class="chapi-promo__wheel-caption" id="chapi-promo-wheel-caption"><span></span> 6 aliados. Un nuevo lugar por descubrir.</p>
      </div>
      <ol class="chapi-promo__steps"><li><span>01</span> Gira la ruleta</li><li><span>02</span> Descubre tu premio</li><li><span>03</span> Vive Chapinero</li></ol>
    </section>

    <section class="chapi-promo__screen chapi-promo__result" data-promo-screen="result" hidden aria-labelledby="chapi-promo-result-title">
      <div class="chapi-promo__celebration" aria-hidden="true"><i>✦</i><i>✳</i><i>✦</i><i>✧</i></div>
      <div class="chapi-promo__result-intro">
        <p class="chapi-promo__eyebrow">EL PLAN YA ESTÁ ARMADO</p>
        <h2 id="chapi-promo-result-title" tabindex="-1">¡GANASTE<span>!</span></h2>
        <p class="chapi-promo__description">Chapinero tiene un lugar para ti. <br>Descúbrelo con tu promoción.</p>
        <div class="chapi-promo__venue"><img id="chapi-promo-logo" alt="" width="60" height="60"><div><small id="chapi-promo-category"></small><h3 id="chapi-promo-business"></h3><p id="chapi-promo-description"></p><p id="chapi-promo-address"></p></div></div>
        <a class="chapi-promo__text-button" href="promos/cliente/">Ver todas mis promociones y mi progreso →</a><label class="chapi-promo__history" id="chapi-promo-history-label" hidden>Mis promociones<select id="chapi-promo-history"></select></label><p class="chapi-promo__save-hint"><span aria-hidden="true">▣</span> Guarda un pantallazo de tu premio y presenta el código al llegar. El establecimiento validará su vigencia.</p>
        <button type="button" class="chapi-promo__text-button" data-promo-close>Seguir explorando Chapinero <span aria-hidden="true">↗</span></button>
      </div>
      <div class="chapi-promo__ticket">
        <div class="chapi-promo__ticket-top"><span class="chapi-promo__eyebrow">TU PRÓXIMO ANTOJO</span><span class="chapi-promo__sample" id="chapi-promo-prize-status"></span></div>
        <p class="chapi-promo__discount" id="chapi-promo-benefit"></p><p class="chapi-promo__benefit-title" id="chapi-promo-benefit-title"></p>
        <div class="chapi-promo__ticket-divider"></div>
        <label class="chapi-promo__code-label" for="chapi-promo-code">TU CÓDIGO DE PROMOCIÓN</label>
        <div class="chapi-promo__code-row"><input id="chapi-promo-code" readonly><button type="button" id="chapi-promo-copy" aria-label="Copiar código"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="8" y="8" width="12" height="13" rx="2"/><path d="M15 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h3"/></svg></button></div>
        <div class="chapi-promo__validity"><span>◷ Tiempo para disfrutar</span><strong id="chapi-promo-countdown" aria-label="Tiempo restante"></strong></div>
        <p class="chapi-promo__expiry" id="chapi-promo-expiry"></p><p class="chapi-promo__micro">Tienes 72 horas desde el giro para redimir. El negocio confirma cuando uses el código.</p>
        <a class="chapi-promo__button chapi-promo__button--green" id="chapi-promo-claim" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 11.7a8.5 8.5 0 0 1-12.6 7.5L3 20.5l1.3-4.7a8.5 8.5 0 1 1 16.2-4.1Z"/><path d="M8 7.5c-.8.8-.3 2.6 1.4 4.3s3.5 2.2 4.3 1.4l1.3-1.3-2-1-1 1c-1.1-.5-2-1.4-2.5-2.5l1-1-1-2Z"/></svg><span>Reclama por WhatsApp</span><span aria-hidden="true">↗</span></a>
        <p class="chapi-promo__conditions" id="chapi-promo-conditions"></p><details class="chapi-promo__message-preview" open><summary>Tu mensaje para WhatsApp</summary><p id="chapi-promo-whatsapp-message"></p></details><ol id="chapi-promo-timeline" class="chapi-promo__timeline" aria-label="Seguimiento del código"></ol><p class="chapi-promo__micro" id="chapi-promo-copy-status" role="status"></p><button class="chapi-promo__button chapi-promo__button--pink" type="button" id="chapi-promo-use-opportunity" hidden>Usar mi nueva oportunidad ↗</button>
      </div>
      <div class="chapi-promo__another"><div><span aria-hidden="true">✳</span><p>Los buenos planes se comparten.<small id="chapi-promo-invite-description">Invita a 8 amigos y desbloquea otra oportunidad.</small></p></div><button type="button" id="chapi-promo-invite">Quiero volver a tener una oportunidad de un descuento <span aria-hidden="true">→</span></button></div>
    </section>

    <section class="chapi-promo__screen chapi-promo__share" data-promo-screen="share" hidden aria-labelledby="chapi-promo-share-title">
      <button type="button" class="chapi-promo__back" data-promo-back="result">← Volver</button>
      <div class="chapi-promo__share-layout"><div>
        <p class="chapi-promo__eyebrow">MÁS AMIGOS. MÁS PLANES.</p>
        <h2 id="chapi-promo-share-title" tabindex="-1">ARMA EL PARCHE.<br><span>VUELVE A GIRAR.</span></h2>
        <p class="chapi-promo__description" id="chapi-promo-share-description">Comparte tu enlace por WhatsApp. Cuando 8 visitantes distintos entren y confirmen su visita tendrás otro giro.</p>
        <div class="chapi-promo__share-note"><span aria-hidden="true">✳</span><p>Más amigos, más planes.<br><small>Invitar amigos te da una oportunidad adicional.</small></p></div>
      </div><div class="chapi-promo__invite-card">
        <div class="chapi-promo__progress-heading"><span>TU PRÓXIMA OPORTUNIDAD</span><strong id="chapi-promo-invite-count">0 <small>/ 8</small></strong></div>
        <div class="chapi-promo__friends" id="chapi-promo-friends" aria-hidden="true"></div>
        <div class="chapi-promo__progress" role="progressbar" aria-label="Visitas referidas válidas" aria-valuemin="0" aria-valuemax="8" aria-valuenow="0"><span id="chapi-promo-progress-fill"></span></div>
        <p class="chapi-promo__invite-status" id="chapi-promo-invite-status" role="status"></p>
        <a class="chapi-promo__button chapi-promo__button--green" id="chapi-promo-share-message" target="_blank" rel="noopener noreferrer"><span>Compartir por WhatsApp</span><span aria-hidden="true">↗</span></a><button class="chapi-promo__text-button" type="button" id="chapi-promo-copy-link">Copiar enlace de invitación</button>
        <button class="chapi-promo__button chapi-promo__button--pink" type="button" id="chapi-promo-again" hidden><span>Volver a girar</span><span aria-hidden="true">↗</span></button>
        <p class="chapi-promo__micro">Una visita por navegador y red en esta invitación. Abrir WhatsApp no suma visitas.</p><button class="chapi-promo__text-button" type="button" id="chapi-promo-refresh">Actualizar mi progreso ↻</button>
      </div></div>
    </section>

    <section class="chapi-promo__screen chapi-promo__message-screen" data-promo-screen="unavailable" hidden aria-labelledby="chapi-promo-unavailable-title"><p class="chapi-promo__eyebrow">SIEMPRE HAY UN NUEVO PLAN</p><h2 id="chapi-promo-unavailable-title" tabindex="-1">Lo bueno <span>está por venir.</span></h2><p class="chapi-promo__description" id="chapi-promo-unavailable-message">Los aliados están preparando sus promociones. Conservamos tu progreso.</p><button class="chapi-promo__button chapi-promo__button--outline" id="chapi-promo-retry" type="button">Consultar oportunidades</button><button class="chapi-promo__text-button" type="button" id="chapi-promo-invite-start" hidden>Invitar amigos para conseguir un giro →</button><button class="chapi-promo__text-button" type="button" data-promo-close>Seguir explorando Chapinero</button></section>
    <footer class="chapi-promo__footer"><p><span aria-hidden="true">◌</span> Entorno de pruebas · 72 horas para redimir · Conserva tus cookies</p><span>HECHO PARA VIVIR CHAPINERO ↗</span></footer>
    <p class="chapi-promo__sr-only" id="chapi-promo-announcement" role="status" aria-live="polite"></p>
  </div>
</dialog>

<aside id="chapi-referral-banner" class="chapi-referral-banner" hidden aria-label="Confirmar invitación"><p>Te invitaron a descubrir Chapinero.<small>Confirma tu visita para sumar al progreso de quien te invitó.</small></p><button type="button" id="chapi-confirm-referral">Confirmar mi visita</button><p id="chapi-referral-feedback" role="status"></p><button type="button" id="chapi-dismiss-referral" aria-label="Cerrar invitación">×</button></aside>
