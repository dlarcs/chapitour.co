'use strict';
(() => {
  const $ = (s, root = document) => root.querySelector(s);
  const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const assets = '../../';
  const icons = {
    crown:'<path d="m3 6 5 5 4-8 4 8 5-5-2 14H5Z"/>',
    arrow:'<path d="M4 12h16m-6-6 6 6-6 6"/>',
    chevron:'<path d="m8 4 8 8-8 8"/>',
    close:'<path d="m6 6 12 12M6 18 18 6"/>',
    home:'<path d="m3 10 9-7 9 7v11h-7v-7h-4v7H3Z"/>',
    gift:'<rect x="3" y="8" width="18" height="5" rx="1"/><path d="M5 13v8h14v-8M12 8v13"/><path d="M12 8C1 8 5-3 12 8c7-11 11 0 0 0Z"/>',
    trophy:'<path d="M7 3h10v6a5 5 0 0 1-10 0ZM7 5H3v3a4 4 0 0 0 5 4m9-7h4v3a4 4 0 0 1-5 4M12 14v6m-5 1h10"/>',
    tag:'<path d="M3 3h8l10 10-8 8L3 11Z"/><circle cx="7.5" cy="7.5" r="1"/>',
    user:'<circle cx="12" cy="7" r="4"/><path d="M4 21v-3a8 8 0 0 1 16 0v3Z"/>',
    users:'<circle cx="9" cy="7" r="3"/><path d="M2 21v-3a7 7 0 0 1 14 0v3ZM16 4a3 3 0 0 1 0 6m2 4a5 5 0 0 1 4 5v2"/>',
    store:'<path d="M3 9 5 3h14l2 6M4 12v9h16v-9M9 21v-7h6v7"/><path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0Z"/>',
    camera:'<path d="m8 5 2-3h4l2 3h5v16H3V5Z"/><circle cx="12" cy="12" r="4"/>',
    share:'<circle cx="18" cy="4" r="3"/><circle cx="5" cy="12" r="3"/><circle cx="18" cy="20" r="3"/><path d="m8 10 7-4m-7 8 7 4"/>',
    sparkles:'<path d="m12 3 2.5 6.5L21 12l-6.5 2.5L12 21l-2.5-6.5L3 12l6.5-2.5ZM20 2v4m-2-2h4"/>',
    pin:'<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
    clock:'<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    calendar:'<rect x="3" y="5" width="18" height="16" rx="3"/><path d="M7 2v6m10-6v6M3 11h18"/>',
    repeat:'<path d="M20 8a9 9 0 0 0-16-2M20 3v5h-5M4 16a9 9 0 0 0 16 2M4 21v-5h5"/>',
    info:'<circle cx="12" cy="12" r="9"/><path d="M12 11v6m0-11v1"/>',
    check:'<path d="m5 12 4 4L19 6"/>',
    search:'<circle cx="10" cy="10" r="7"/><path d="m15 15 6 6"/>',
    logout:'<path d="M10 3H3v18h7m-2-9h13m-5-5 5 5-5 5"/>',
    copy:'<rect x="8" y="8" width="13" height="13" rx="2"/><path d="M16 8V3H3v13h5"/>',
    whatsapp:'<path d="m3 21 1-5a9 9 0 1 1 4 4Z"/><path d="M8 7c-1 4 3 8 7 9l2-3-3-1-1 1-3-3 1-1-1-3Z"/>',
    lock:'<rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V6a4 4 0 0 1 8 0v4m-4 4v3"/>',
    warning:'<path d="m12 3 10 18H2ZM12 9v5m0 3v1"/>',
    plus:'<path d="M12 4v16M4 12h16"/>',
    edit:'<path d="m15 4 5 5M4 20l5-1L21 7l-5-5L4 14Z"/>',
    trash:'<path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7m4-7v7"/>',
    ticket:'<path d="M3 5h18v5a2 2 0 0 0 0 4v5H3v-5a2 2 0 0 0 0-4Z"/><path d="M15 5v3m0 3v2m0 3v3"/>',
    flame:'<path d="M12 2c1 7 8 7 8 13a8 8 0 0 1-16 0c0-4 3-5 4-8 0 4 3 4 3 6 3-4 2-8 1-11Z"/>',
    coffee:'<path d="M3 8h13v7a6.5 6.5 0 0 1-13 0ZM16 9h2a4 4 0 0 1 0 8h-2M2 22h18M6 2v3m5-3v3"/>',
    beer:'<path d="M5 7v14h11V7m0 3h4v8h-4M8 11v6m5-6v6"/><path d="M4 7a3 3 0 0 1 1-6 3 3 0 0 1 5 0 3 3 0 0 1 5 0 3 3 0 0 1 1 6Z"/>',
    music:'<path d="M9 18V5l12-3v13M9 9l12-3"/><ellipse cx="6" cy="18" rx="3" ry="3"/><ellipse cx="18" cy="15" rx="3" ry="3"/>',
    target:'<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>',
  };
  const icon = (name, cls='') => `<svg class="icon ${cls}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${icons[name] || icons.sparkles}</svg>`;
  const brand = () => `<a class="brand" href="#inicio" aria-label="Chapitour, inicio">${icon('crown')}<span>CHAPITOUR<span class="brand-dot">.CO</span><small>TE PREMIA</small></span></a>`;
  const btn = (label, action, cls='', extra='') => `<button type="button" class="btn ${cls}" data-action="${action}" ${extra}>${label}</button>`;
  const avatar = (u,cls='') => `<div class="avatar ${cls}">${u?.photo_url?`<img src="${esc(u.photo_url)}" alt="Mi foto de perfil">`:esc((u?.name||'').slice(0,2).toUpperCase())}</div>`;
  let avatarPreviewUrl=null;
  let publicationFilter = '';
  let state, currentView, busy = false, spinBusy = false, lastFocus, toastTimer;
  let wheelParts = [], wheelTicket = null, wheelDemo = false, visitTask = null;
  const dialog = $('#modal');
  const business = id => state.businesses.find(b => b.id === id);
  const status = c => c.redeemed_at ? 'Redimido' : (c.expires_at * 1000 <= Date.now() + (state.server_time * 1000 - receivedAt) ? 'Vencido' : 'Activo');
  let receivedAt = Date.now();
  const date = t => new Intl.DateTimeFormat('es-CO', {dateStyle:'medium', timeStyle:'short', timeZone:'America/Bogota'}).format(new Date(t * 1000));
  const badge = c => `<span class="status ${status(c).toLowerCase()}"><i></i>${status(c)}</span>`;
  const publication = p => `<span class="publication ${p.publication}">${p.publication === 'draft' ? 'Borrador · Por confirmar' : p.publication === 'approved' ? 'Confirmada' : 'Oferta de referencia'}</span>`;
  async function api(action, data={}) {
    const multipart=data instanceof FormData;if(multipart)data.set('action',action);
    const response = await fetch('api.php', action === 'state' ? {credentials:'same-origin'} : {method:'POST', credentials:'same-origin', headers:multipart?{'X-CSRF-Token':state.csrf}:{'Content-Type':'application/json','X-CSRF-Token':state.csrf}, body:multipart?data:JSON.stringify({action,...data})});
    let result;
    try { result = await response.json(); } catch { throw new Error('No fue posible cargar la información. Intenta de nuevo.'); }
    if (!response.ok) throw new Error(result.error || 'No se pudo completar la acción.');
    state = result; receivedAt = Date.now(); return result;
  }
  function toast(message) { const el=$('#toast'); el.textContent=message; el.classList.add('visible'); clearTimeout(toastTimer); toastTimer=setTimeout(()=>el.classList.remove('visible'), 4500); }
  function openModal(html, cls='') {
    if(!dialog.open)lastFocus=document.activeElement; dialog.className=cls; dialog.innerHTML=`<button class="modal-close" data-action="close" aria-label="Cerrar ventana">${icon('close')}</button>${html}<p class="form-error" role="alert" id="modal-error"></p>`;
    if (!dialog.open) dialog.showModal();
    document.body.classList.add('modal-open');
  }
  function closeModal() { if (spinBusy) return; dialog.close(); }
  dialog.addEventListener('close',()=>{if(dialog.open)return;document.body.classList.remove('modal-open');dialog.innerHTML='';if(lastFocus?.isConnected)lastFocus.focus();else {const main=$('#main');main?.setAttribute('tabindex','-1');main?.focus({preventScroll:true});}});
  dialog.addEventListener('cancel',e=>{if(spinBusy)e.preventDefault();});
  dialog.addEventListener('click',e=>{if(e.target===dialog){const r=dialog.getBoundingClientRect();if(e.clientX<r.left||e.clientX>r.right||e.clientY<r.top||e.clientY>r.bottom)closeModal();}});
  const navItem = (id, label, i) => `<a href="#${id}" class="nav-item ${currentView===id?'active':''}" ${currentView===id?'aria-current="page"':''}>${icon(i)}<span>${label}</span>${currentView===id?'<i class="nav-dot"></i>':''}</a>`;
  function sandboxBar() {
    return `<div class="sandbox-bar"><span><i></i> CHAPITOUR TE PREMIA <span class="sandbox-note">· Paneles de pruebas con datos guardados</span></span><span>${state.campaign.enabled?'Promociones disponibles':state.campaign.setup_required?'Configurando la ruleta':'Promociones por aprobar'}</span></div>`;
  }
  function shell(content) {
    const u=state.user, role=u.role;
    const nav=role==='client' ? navItem('retos','Mis retos','trophy')+navItem('mis-promociones','Mis promociones','tag')+navItem('perfil','Mi perfil','user') : role==='ally' ? navItem('aliado','Promociones y códigos','ticket') : navItem('aliados','Aliados','users')+navItem('promociones','Promociones','tag')+navItem('codigos','Códigos y estados','ticket');
    const name=role==='ally'?business(u.business_id)?.name:u.name.split(' ')[0];
    return `${sandboxBar()}<div class="app-shell"><aside class="sidebar">${brand()}<div class="nav-label">${role==='client'?'TU CHAPITOUR':role==='ally'?'ESPACIO DEL ALIADO':'ADMINISTRACIÓN'}</div><nav aria-label="Navegación del panel">${nav}</nav><a class="nav-item explore-nav" href="#inicio">${icon('home')}<span>Explorar Chapitour</span>${icon('arrow')}</a><div class="sidebar-poster"><div class="poster-text">LOS BUENOS<br>PLANES <em>TAMBIÉN<br>TE PREMIAN.</em></div>${icon('crown')}<span>Más barrio. Más historias.<br>Más Chapinero.</span></div><button class="logout" data-action="logout">${icon('logout')} Cerrar sesión</button><span class="sidebar-location">${icon('pin')} Chapinero, Bogotá</span></aside><div class="workspace"><header class="topbar"><span>${role==='client'?'Mi espacio':role==='ally'?'Panel del aliado':'Panel de administración'}</span><div class="topbar-right"><a href="#inicio" class="back-home">Ir a Chapitour ${icon('arrow')}</a>${avatar(u)}<span class="user-label">${role==='ally'?'Tu negocio':'Hola,'} <strong>${esc(name)}</strong></span></div></header><main id="main" tabindex="-1">${content}</main><footer class="footer"><strong>CHAPITOUR<span>.CO</span></strong><span>Explora. Conecta. Vive Chapinero.</span><span class="footer-right">Hecho para descubrir ${icon('crown')}</span></footer></div></div>`;
  }
  function publicPage() {
    const u=state.user;
    return `${sandboxBar()}<div class="public-page"><header class="public-header">${brand()}<nav aria-label="Navegación principal"><a href="#explorar">Nuestros aliados</a><a href="#comunidad">Comunidad</a><a href="#como-funciona">Cómo funciona</a></nav>${u?`<a class="btn outline" href="#${homeView()}">${icon('user')} ${u.role==='client'?'Mis retos':'Mi panel'}</a>`:btn('Iniciar sesión','login','outline')}</header><main id="main"><section class="public-hero"><div class="hero-copy"><span class="eyebrow">${icon('pin')} CHAPINERO, BOGOTÁ · TU PRÓXIMO PLAN</span><h1>Sal de la rutina.<br>Entra en <em>Chapitour.</em></h1><p>Los lugares, las personas y las historias que hacen único a nuestro barrio. Descúbrelos y deja que Chapinero te sorprenda.</p><div class="hero-actions"><a class="btn primary" href="#explorar">Explorar aliados ${icon('arrow')}</a>${btn(`${icon('gift')} Conoce la ruleta`,'preview-wheel','text-btn')}</div><div class="hero-tags"><span>Gastronomía</span><span>Vida nocturna</span><span>Experiencias</span></div></div><div class="hero-photo"><img src="${assets}home/img/bar2.png" alt="Ambiente nocturno con luces de neón en un bar"/><div class="photo-caption">LA CIUDAD SE VIVE.<br><em>CHAPINERO SE DESCUBRE.</em>${icon('crown')}</div><span class="photo-label">UN BARRIO. MUCHOS PLANES.</span></div></section><section class="signup-banner" id="crear-cuenta"><div class="signup-icon">${icon(u?'trophy':'sparkles')}</div><div><span class="eyebrow">CHAPITOUR TE PREMIA</span><h2>${u?'Tu próximo plan empieza aquí':'Descubre Chapinero y participa en nuestros retos'}</h2><p>${u?'Explora tus retos y consulta tus promociones.':'Crea tu cuenta para empezar a explorar y consultar tus promociones.'}</p></div><div class="signup-actions">${u?`<a class="btn yellow" href="#${homeView()}">${u.role==='client'?'Mis retos':'Mi cuenta'} ${icon('arrow')}</a>`:btn(`Crear mi cuenta ${icon('arrow')}`,'register','yellow')+`<span>Ya tengo cuenta · <button class="inline-button" data-action="login">Iniciar sesión</button></span>`}</div></section>${leaderboardSection()}<section class="public-section" id="explorar"><div class="section-heading"><div><span class="eyebrow">NEGOCIOS DEL BARRIO</span><h2>Buenos lugares. <span class="pink-text">Grandes planes.</span></h2></div><span class="muted">Encuentra tu próxima historia</span></div><div class="business-grid">${state.businesses.map(b=>`<a class="business-card" href="${b.path?esc(assets+b.path):'#inicio'}" ${b.path?'target="_blank" rel="noopener"':''}><div class="business-photo" style="--accent:var(--${b.color})"><img loading="lazy" src="${esc(assets+(b.image||'home/img/bar2.png'))}" alt="${esc(b.name)}"/><span class="business-mark">${icon(b.icon)}</span></div><div class="business-card-copy"><div><h3>${esc(b.name)}</h3><p>${esc(b.category)}</p></div>${icon('arrow')}</div></a>`).join('')}</div></section><section class="how-it-works public-section" id="como-funciona"><div><span class="eyebrow">ASÍ DE FÁCIL</span><h2>El barrio tiene<br>algo para ti.</h2></div><article><span>01</span><h3>Crea tu cuenta</h3><p>Tu espacio para explorar los retos y consultar tu información.</p></article><article><span>02</span><h3>Descubre Chapinero</h3><p>Conoce a nuestros aliados y encuentra planes para cada momento.</p></article><article><span>03</span><h3>Disfruta tus promociones</h3><p>Consulta tu código y coordina con el negocio para redimirlo.</p></article></section></main><footer class="footer">${brand()}<span>Negocios · Comunidad · Ciudad</span><span>Tu información se guarda en tu cuenta.</span></footer></div>`;
  }
  const challengeFor = type => (state.challenges || []).find(c=>c.type===type);
  const progressFor = type => challengeFor(type)?.progress ?? null;
  function challenges() {
    const photo=challengeFor('fotografia'),questions=challengeFor('preguntas');
    const item=(type,target,label)=>{const n=progressFor(type);return `<div class="progress-label"><strong>${n===null?'—':esc(n)} <span>/ ${target}</span></strong><span>${esc(label)}</span></div><progress value="${Number(n)||0}" max="${target||1}" aria-label="${esc(label)}"></progress>`;};
    return `<section class="challenge-grid monthly-goals" aria-label="Mis metas del mes">
      <article class="challenge purple" data-goal="compartir"><div class="card-top"><span class="icon-tile">${icon('share')}</span><span class="card-category">COMPARTE</span></div><h3>Comparte la página con 20 amigos</h3><p>Completa esta meta para ganar otro giro en la ruleta.</p><div class="challenge-bottom">${item('compartir',20,progressFor('compartir')===null?'Entregas por verificar':'Entregas verificadas')}${btn(`Compartir página ${icon('arrow')}`,'share','card-link')}<small class="verification-note">Compartir no confirma las 20 entregas. Su verificación está pendiente de definir.</small></div></article>
      <article class="challenge cyan" data-goal="fotografia"><div class="card-top"><span class="icon-tile">${icon('camera')}</span><span class="card-category">ETIQUETA</span></div><h3>Etiqueta en Instagram desde 3 negocios</h3><p>Publica tus fotos y etiqueta a <strong>@chapitour.co</strong> y al negocio que visitaste.</p><div class="challenge-bottom">${item('fotografia',3,'Lugares registrados por ti')}${btn(`${photo?.progress===3?'Ver mis 3 lugares':'Registrar dónde lo hice'} ${icon('arrow')}`,'photo-progress','card-link',photo?.ready?'':'disabled')}<small class="verification-note">1 lugar distinto = 1 punto. Solo tienes que indicar dónde lo hiciste.</small>${photo?.ready?'':'<small class="verification-note">Las nuevas metas se están preparando.</small>'}</div></article>
      <article class="challenge pink" data-goal="preguntas"><div class="card-top"><span class="icon-tile">${icon('search')}</span><span class="card-category">DESCUBRE</span></div><h3>Preguntas y pistas</h3><p>Explora sus espacios, descubre lo que ofrecen y responde las preguntas de cada negocio.</p><div class="challenge-bottom">${item('preguntas',questions?.target||5,'Preguntas respondidas')}${btn(`Responder preguntas ${icon('arrow')}`,'question-places','card-link',questions?.ready?'':'disabled')}<small class="verification-note">Esta meta tiene su propio avance, independiente del 3/3 de Instagram.</small></div></article>
    </section><aside class="return-banner"><div>${icon('gift')}<div><h3>¡Volver tiene premio!</h3><p>Sigue entrando a chapitour.co para descubrir nuevos lugares, planes y promociones. Tu próxima visita puede traer una sorpresa.</p></div></div><a href="#explorar" class="btn yellow">Explorar Chapitour ${icon('arrow')}</a></aside>`;
  }
  function photoProgress() {
    const c=challengeFor('fotografia');
    if(!c?.ready)throw new Error('Las nuevas metas se están preparando. Intenta de nuevo más tarde.');
    const available=c.places.filter(p=>!p.complete),complete=c.progress>=3;
    openModal(`<div class="modal-symbol cyan">${icon('camera')}</div><span class="eyebrow">META DE INSTAGRAM</span><h2 id="modal-title">Tus 3 lugares</h2><p class="modal-lead">Publica una foto en Instagram y etiqueta a <strong>@chapitour.co</strong> y a la cuenta del negocio. Después selecciona dónde lo hiciste.</p><p class="portrait-clue">Una pista para una de tus fotos: encuentra el retrato que más te guste y etiquétanos.</p><div class="goal-total"><strong>${c.progress}/3</strong><span>${complete?'¡Meta completada!':'Cada lugar distinto suma 1 punto.'}</span></div>
      ${complete?'':`<form data-form="record_photo"><input type="hidden" name="month" value="${esc(c.month)}"><label>¿Dónde lo hiciste?<select name="business_id" required id="photo-place"><option value="">Selecciona un negocio</option>${available.map(p=>`<option value="${esc(p.id)}">${esc(p.name)}</option>`).join('')}</select></label><p class="photo-mentions" id="photo-mentions" aria-live="polite">Selecciona el lugar para consultar a quién etiquetar.</p><button class="btn primary full" type="submit" ${available.length?'':'disabled'}>Registrar lugar · +1 punto ${icon('check')}</button></form>`}
      <ul class="goal-place-list">${c.places.map(p=>`<li>${icon(p.complete?'check':'pin')}<span>${esc(p.name)}</span><small>${p.complete?'Registrado · 1 punto':'Pendiente'}</small></li>`).join('')}</ul><p class="modal-note">El avance se guarda según lo que registras. No se comprueban automáticamente fotos ni etiquetas. Cada negocio suma una sola vez al mes.</p>`,'form-modal');
  }
  function photoMentions() {
    const p=challengeFor('fotografia')?.places.find(p=>p.id===$('#photo-place')?.value),target=$('#photo-mentions');
    if(target)target.textContent=p?`Etiqueta a @chapitour.co y ${p.instagram?'@'+p.instagram: 'a la cuenta de Instagram que te indique '+p.name+'.'}`:'Selecciona el lugar para consultar a quién etiquetar.';
  }
  function questionPlaces() {
    const c=challengeFor('preguntas');
    if(!c?.ready)throw new Error('Las nuevas metas se están preparando. Intenta de nuevo más tarde.');
    openModal(`<div class="modal-symbol pink">${icon('search')}</div><span class="eyebrow">META DE PREGUNTAS</span><h2 id="modal-title">Preguntas y pistas</h2><p class="modal-lead">Descubre a qué lugar corresponde cada pista. Responde y selecciona dónde lo encontraste.</p><div class="goal-total"><strong>${c.progress}/${c.target}</strong><span>Preguntas respondidas.</span></div><div class="question-place-list">${c.places.map(p=>btn(`<span>${esc(p.name)}<small>${p.complete?'Respuesta registrada':p.questions[0].label}</small></span>${icon(p.complete?'check':'arrow')}`,'answer-place','outline',`data-id="${esc(p.id)}" ${p.complete?'disabled':''}`)).join('')}</div><p class="modal-note">El progreso de las preguntas es independiente de tu meta de Instagram.</p>`,'form-modal');
  }
  function questionForm(id) {
    const c=challengeFor('preguntas'),p=c?.places.find(p=>p.id===id);
    if(!p||p.complete)return;
    openModal(`<span class="eyebrow">CONOCE EL LUGAR</span><h2 id="modal-title">${esc(p.name)}</h2><p class="modal-lead">Observa el lugar y responde con tus propias palabras.</p><form data-form="answer_questions"><input type="hidden" name="question_id" value="${esc(p.id)}"><input type="hidden" name="month" value="${esc(c.month)}"><input type="hidden" name="version" value="${c.version}"><label>¿Dónde lo descubriste?<select name="business_id" required><option value="">Selecciona el negocio</option>${c.locations.map(b=>`<option value="${esc(b.id)}">${esc(b.name)}</option>`).join('')}</select></label>${p.questions.map(q=>`<label>${esc(q.label)}<textarea name="answer_${esc(q.id)}" rows="2" maxlength="250" required></textarea></label>`).join('')}<div class="modal-actions">${btn('Volver a las preguntas','question-places','outline')}<button class="btn primary" type="submit">Guardar respuesta ${icon('check')}</button></div></form>`,'form-modal');
  }
  function clientPage() {
    const month=new Intl.DateTimeFormat('es-CO',{month:'long',year:'numeric',timeZone:'America/Bogota'}).format(new Date());
    return `<section class="client-hero"><div><span class="eyebrow">UN BARRIO ENTERO POR DESCUBRIR</span><h1>Mis retos <span>del mes</span><span class="title-spark">✦</span></h1><p>Descubre Chapinero y completa tus metas.</p><div class="month-row"><span class="month">${icon('calendar')}${month}</span><span>${icon('repeat')} Tus metas se renuevan mensualmente.</span></div></div><div class="hero-sticker">EXPLORA<br>DESCUBRE<br><em>REPITE.</em>${icon('crown')}</div></section><div class="section-heading compact"><h2>Mis metas</h2></div>${challenges()}${rewardNotice()}<div class="client-bottom"><section class="panel"><div class="section-heading"><div class="heading-with-icon">${icon('tag')}<div><h2>Mis promociones</h2><p>Tu próximo buen momento empieza aquí.</p></div></div><a href="#mis-promociones" class="subtle-link">Ver todas ${icon('arrow')}</a></div>${promotionsPreview()}</section><aside class="profile-preview panel">${avatar(state.user,'large')}<h3>Tu Chapitour,<br>a tu manera.</h3><p>Tu información y tus próximos planes, en un solo lugar.</p><a class="btn outline" href="#perfil">Mi perfil ${icon('arrow')}</a></aside></div>`;
  }
  function emptyPromotions() { return `<div class="empty-state">${icon('gift')}<h3>Lo mejor está por venir</h3><p>Aquí aparecerán las promociones que ganes.</p><a href="#retos" class="subtle-link">Explorar mis retos ${icon('arrow')}</a></div>`; }
  function promotionsPreview() { const c=state.codes.find(c=>status(c)==='Activo'); return c?prizeCard(c):emptyPromotions(); }
  function whatsappButton(c, cls='primary') {
    const available=c.whatsapp_available;
    return `${btn(`${icon('whatsapp')} Redimir por WhatsApp ${icon('arrow')}`,'whatsapp',cls,`data-code="${esc(c.code)}" ${available?'':'disabled aria-describedby="whatsapp-note-'+esc(c.code)+'"'}`)}${!available?`<small id="whatsapp-note-${esc(c.code)}" class="muted whatsapp-pending">WhatsApp del negocio pendiente de confirmar.</small>`:''}`;
  }
  function prizeCard(c) {
    const b=business(c.business_id), s=status(c);
    return `<article class="prize-card"><div class="prize-card-header"><div class="prize-icon">${icon(b?.icon||'gift')}</div><div><span class="eyebrow">TU PROMOCIÓN</span><h3>${esc(c.business_name)}</h3><p>${esc(c.description)}</p></div>${badge(c)}</div><div class="prize-meta"><div><small>Código único</small><button class="code-copy" data-action="copy" data-code="${esc(c.code)}">${esc(c.code)} ${icon('copy')}</button></div><div><small>Vence el · hora de Bogotá</small><span>${icon('calendar')}${date(c.expires_at)}</span></div></div><div class="prize-actions">${s==='Activo'?whatsappButton(c):''}${btn('Ver detalle','prize','outline small',`data-code="${esc(c.code)}"`)}</div></article>`;
  }
  function promotionsPage() { return `<div class="page-heading"><span class="eyebrow">TUS PLANES TIENEN PREMIO</span><h1>Mis <span>promociones</span></h1><p>Encuentra tus códigos y consulta cuándo puedes utilizarlos.</p></div><div class="info-strip">${icon('clock')} Cada código tiene una vigencia de 72 horas desde su generación. </div><section class="prizes-list">${state.codes.length?state.codes.map(prizeCard).join(''):emptyPromotions()}</section>`; }
  function rankingRows(ranking) {
    if(!ranking?.entries.length)return `<div class="ranking-empty">${icon('users')}<h3>${ranking?.ready?'El próximo nombre puede ser el tuyo':'La comunidad se está preparando'}</h3><p>Crea tu cuenta, participa en las metas y personaliza tu alias en Mi perfil.</p></div>`;
    return `<table class="ranking-table"><thead><tr><th>Nombre</th><th>Calificación</th></tr></thead><tbody>${ranking.entries.map(row=>`<tr class="ranking-entry"><td>${esc(row.name)}</td><td><strong>${row.score}</strong><span> ${row.score===1?'punto':'puntos'}</span></td></tr>`).join('')}</tbody></table>`;
  }
  function rankingMonth(ranking) {
    return ranking?.month?new Intl.DateTimeFormat('es-CO',{month:'long',year:'numeric',timeZone:'America/Bogota'}).format(new Date(ranking.month+'T12:00:00Z')):'';
  }
  function leaderboardSection() {
    const r=state.leaderboard;
    return `<section class="public-section community-section" id="comunidad"><div class="community-copy"><span class="eyebrow">LA COMUNIDAD DE CHAPITOUR</span><h2>Los que más viven <em>Chapinero.</em></h2><p>Explora, participa y sube en la lista. Cada mes comienza una nueva oportunidad.</p><div class="community-month">${icon('calendar')}${esc(rankingMonth(r))}</div><details class="score-rules"><summary>¿Cómo sumo puntos?</summary><p>Visitas válidas: +1, hasta 10 puntos. Preguntas respondidas: +5, hasta 25. Fotos con etiquetas registradas: +15, hasta 45.</p><p>Compartir con 20 amigos: 20 puntos cuando se confirme el reto. La comprobación de entregas sigue pendiente. Máximo: 100 puntos al mes.</p><p>Es un puntaje de participación: las fotos y las respuestas se registran por el cliente.</p></details>${state.user?.role==='client'?'<a href="#perfil" class="btn outline">Mi posición y perfil '+icon('arrow')+'</a>':!state.user?btn('Quiero participar','register','primary'):''}</div><div class="ranking-card"><div class="ranking-card-heading"><span>${icon('trophy')} PARTICIPACIÓN DEL MES</span><small>${r?.total||0} ${(r?.total||0)===1?'participante':'participantes'}</small></div>${rankingRows(r)}${r?.total?btn(`Ver toda la lista ${icon('arrow')}`,'ranking-page','text-btn full','data-page="1"'):''}<p class="ranking-footnote">Participantes registrados · Alias y puntajes.</p></div></section>`;
  }
  async function rankingPage(page=1) {
    const result=await api('leaderboard',{page:String(page)}),r=result.ranking_page;
    openModal(`<span class="eyebrow">${esc(rankingMonth(r))}</span><h2 id="modal-title">La comunidad de Chapitour</h2><p class="modal-lead">Participa en las metas y personaliza tu alias desde Mi perfil.</p>${rankingRows(r)}<div class="ranking-pagination">${btn('Anterior','ranking-page','outline small',`data-page="${page-1}" ${page<=1?'disabled':''}`)}<span>Página ${r.page}</span>${btn('Siguiente','ranking-page','outline small',`data-page="${page+1}" ${r.has_more?'':'disabled'}`)}</div>`,'form-modal');
  }
  function memberScore(c) {
    return `<section class="member-score panel"><div><span class="eyebrow">MI PARTICIPACIÓN DEL MES</span><h2>${c.score||0}<small> / 100 puntos</small></h2><p>${c.visible?`Tu posición: <strong>${c.position||'—'}</strong>. Las personas con el mismo puntaje comparten posición.`:'Puedes sumar puntos y elegir cuándo aparecer en la lista pública.'}</p>${c.points_to_climb?`<p>Te faltan <strong>${c.points_to_climb} puntos</strong> para alcanzar la siguiente posición.</p>`:''}</div><dl>${[['visits','Visitas válidas',10],['questions','Preguntas',25],['photos','Fotos y etiquetas',45],['sharing','Compartir',20]].map(([key,label,max])=>`<div><dt>${label}</dt><dd>${c.breakdown?.[key]||0} / ${max}</dd></div>`).join('')}</dl><small>Los puntos se renuevan cada mes.</small></section>`;
  }
  function profilePage() {
    const u=state.user,c=u.community||{};
    return `<div class="page-heading"><span class="eyebrow">TU ESPACIO EN CHAPITOUR</span><h1>Mi <span>perfil</span></h1><p>Tu foto, tu nombre público y tus próximos planes.</p></div>
      <section class="profile-photo-panel panel"><div id="profile-photo-preview">${avatar(u,'profile-photo')}</div><form data-form="upload_avatar"><h2>Mi fotografía</h2><p>Elige la foto que quieres ver en tu perfil.</p><div class="photo-picker"><label for="avatar-file" class="btn outline">Seleccionar fotografía</label><input id="avatar-file" class="photo-file-input" name="avatar" type="file" aria-label="Seleccionar fotografía" accept="image/jpeg,image/png,image/webp" required ${c.ready?'':'disabled'}><span id="avatar-filename">Ningún archivo seleccionado</span></div><small>JPG, PNG o WebP · Hasta 5 MB. Se recorta al centro.</small><p class="form-error" role="alert" id="photo-error"></p><div class="photo-actions"><button class="btn primary" type="submit" ${c.ready?'':'disabled'}>Guardar foto ${icon('camera')}</button>${u.photo_url?btn('Quitar foto','remove-avatar','outline'):''}</div></form></section>
      ${memberScore(c)}<section class="profile-panel panel"><div class="profile-intro">${avatar(u,'large')}<div><h2>${esc(u.name)}</h2><span class="muted">Cuenta de Chapitour</span></div></div><form data-form="profile"><div class="form-grid"><label>Nombre<input name="name" autocomplete="name" required maxlength="80" value="${esc(u.name)}"></label><label>Correo electrónico<input name="email" type="email" autocomplete="email" required maxlength="150" value="${esc(u.email)}" ${u.google_linked?'readonly':''}></label><label>Ciudad<input name="city" autocomplete="address-level2" maxlength="80" value="${esc(u.city)}"></label><div class="field-static"><span>Miembro desde</span><strong>${date(u.created_at)}</strong></div></div>
      <fieldset class="public-profile-settings" ${c.ready?'':'disabled'}><legend>Mi alias en la comunidad</legend><label>Nombre público o alias<input name="public_name" maxlength="40" value="${esc(c.public_name||'')}" placeholder="Así aparecerás en la lista"></label><label class="checkbox-label"><input type="checkbox" name="ranking_visible" ${c.visible?'checked':''}> Mostrar mi alias en la lista pública.</label><p>Tu cuenta aparece con un alias automático si aún no lo has personalizado. Puedes cambiarlo o desmarcar esta opción para ocultarte. La lista muestra solo tu alias y puntaje; tu foto permanece en tu perfil.</p></fieldset>
      <p class="form-error" role="alert"></p><button class="btn primary" type="submit">Guardar cambios ${icon('check')}</button></form>${u.google_linked?'<p class="modal-note">Cuenta vinculada con Google.</p>':btn('Vincular Google','google-link','outline')}</section><section class="danger-zone"><div><h3>Eliminar mi cuenta</h3><p>Se eliminarán tu perfil, fotografía y participación pública. Se conservará el historial de premios y redenciones.</p></div>${btn(`${icon('trash')} Eliminar mi cuenta`,'delete-account','danger outline')}</section>`;
  }
  function validateAvatarFile(file) {
    if(!file||!['image/jpeg','image/png','image/webp'].includes(file.type))throw new Error('Selecciona una fotografía JPG, PNG o WebP.');
    if(file.size>5*1024*1024)throw new Error('La foto no puede superar 5 MB.');
  }
  function previewAvatar() {
    const file=$('#avatar-file')?.files[0],error=$('#photo-error');if(!file)return;
    try{validateAvatarFile(file);$('#avatar-filename').textContent=file.name;if(avatarPreviewUrl)URL.revokeObjectURL(avatarPreviewUrl);avatarPreviewUrl=URL.createObjectURL(file);$('#profile-photo-preview').innerHTML=`<div class="avatar profile-photo"><img src="${esc(avatarPreviewUrl)}" alt="Vista previa de mi fotografía"></div>`;error.textContent='';}
    catch(e){error.textContent=e.message;$('#avatar-file').value='';$('#avatar-filename').textContent='Ningún archivo seleccionado';}
  }
  async function avatarUpload(file) {
    validateAvatarFile(file);let bitmap;
    try{bitmap=await createImageBitmap(file,{imageOrientation:'from-image'});}catch{throw new Error('No pudimos leer la imagen. Selecciona otra fotografía.');}
    try{const canvas=document.createElement('canvas');canvas.width=512;canvas.height=512;const ctx=canvas.getContext('2d'),side=Math.min(bitmap.width,bitmap.height);ctx.fillStyle='#fff';ctx.fillRect(0,0,512,512);ctx.drawImage(bitmap,(bitmap.width-side)/2,(bitmap.height-side)/2,side,side,0,0,512,512);const blob=await new Promise(resolve=>canvas.toBlob(resolve,'image/jpeg',.88));if(!blob)throw new Error('No pudimos preparar la fotografía.');const form=new FormData();form.set('avatar',blob,'perfil.jpg');return form;}finally{bitmap.close();}
  }
  function stats() {
    return `<div class="stats-grid">${[['Activo','Códigos activos','ticket','cyan'],['Redimido','Códigos redimidos','check','pink'],['Vencido','Códigos vencidos','clock','yellow']].map(([s,label,i,color])=>`<article class="stat ${color}"><span class="icon-tile">${icon(i)}</span><div><span>${label}</span><strong>${state.codes.filter(c=>status(c)===s).length}</strong></div></article>`).join('')}<article class="stat validity purple">${icon('clock')}<div><strong>72 horas</strong><span>de vigencia por código</span></div></article></div>`;
  }
  function allyPage() {
    const b=business(state.user.business_id);
    return `<div class="page-heading"><span class="eyebrow">MÁS BARRIO, MÁS CONEXIONES</span><h1>Panel <span>del aliado</span></h1><p>Consulta y valida los códigos de tus promociones.</p></div><section class="ally-banner"><span class="business-avatar">${icon(b.icon)}</span><div><span class="eyebrow">TU NEGOCIO EN CHAPITOUR</span><h2>${esc(b.name)}</h2><p>${esc(b.category)}</p></div><div class="ally-banner-tag">Buenos lugares.<br><strong>Mejores historias.</strong>${icon('crown')}</div></section><div class="section-heading compact"><h2>Así van tus promociones</h2></div>${stats()}${codesPanel(false)}<div class="info-strip">${icon('info')} Abrir WhatsApp o enviar un mensaje no redime un código. Confirma la redención cuando apliques la promoción.</div>`;
  }
  function pendingNotice() {
    if (!state.promotions.some(p=>p.publication==='draft')) return '';
    return `<section class="pending-notice">${icon('warning')}<div><h3>Promociones pendientes de confirmar</h3><p>Antes de activar la campaña, reemplaza las promociones de ejemplo de Pictogramas y Jimar Factory por las ofertas aprobadas por cada negocio.</p><p class="pending-detail">Confirma el beneficio, los productos o servicios incluidos, los horarios, las restricciones y el número de WhatsApp.</p></div>${btn(`Revisar promociones pendientes ${icon('arrow')}`,'pending','yellow small')}</section>`;
  }
  function adminPage() {
    return `<div class="page-heading"><span class="eyebrow">EL BARRIO CRECE CONTIGO</span><h1>Administración de <span>Chapitour</span></h1><p>Gestiona aliados, promociones y códigos para conectar más experiencias.</p></div><div class="admin-metrics"><span>${icon('users')}<strong>${state.businesses.length}</strong> Aliados</span><span>${icon('tag')}<strong>${state.promotions.length}</strong> Promociones</span><span>${icon('ticket')}<strong>${state.codes.filter(c=>status(c)==='Activo').length}</strong> Códigos activos</span></div>${pendingNotice()}${!state.google_auth?.enabled?'<section class="pending-notice"><div><h3>Registro con Google pendiente de activar</h3><p>Configura el ID de cliente de Google para habilitar el registro de nuevos clientes y su giro de bienvenida.</p></div></section>':''}<nav class="admin-tabs" aria-label="Secciones de administración">${navItem('aliados','Aliados','users')}${navItem('promociones','Promociones','tag')}${navItem('codigos','Códigos y estados','ticket')}</nav>${currentView==='aliados'?alliesPanel():currentView==='promociones'?adminPromotions():codesPanel(true)}<section class="info-strip wheel-test-access">${icon('gift')}<div><strong>Prueba la ruleta</strong><p>Comprueba la animación desde tu cuenta administradora. No entrega premios ni genera códigos.</p></div>${btn('Probar ruleta','preview-wheel','yellow')}</section><details class="rules-panel"><summary>${icon('info')} Reglas de la ruleta <span>${state.campaign.enabled?'Lista para clientes elegibles':'Sin promociones disponibles'}</span></summary><p>Las oportunidades disponibles se consultan automáticamente. Los giros ya ganados se conservan. Cada código vence 72 horas después de generarse y solo puede redimirse una vez.</p><p>${state.campaign.eligible_promotions} promociones aprobadas y disponibles para entregar. Guardar un borrador no lo incluye en la ruleta.</p></details>`;
  }
  function alliesPanel() { return `<section class="panel"><div class="section-heading"><div><h2>Aliados de Chapitour</h2><p>Administra las cuentas de los negocios. Eliminar un acceso conserva su página y sus promociones.</p></div>${btn(`${icon('plus')} Crear cuenta de aliado`,'new-business','primary')}</div><div class="allies-grid">${state.businesses.map(b=>`<article class="ally-account"><div class="business-avatar ${b.color}">${icon(b.icon)}</div><h3>${esc(b.name)}</h3><p>${esc(b.email||'Sin cuenta de acceso')}</p><span class="muted">${state.promotions.filter(p=>p.business_id===b.id).length} promociones</span><div class="account-danger ally-actions">${b.account_id?btn(`${icon('trash')} Eliminar acceso`,'delete-business','danger outline small',`data-id="${b.id}" aria-label="Eliminar acceso de ${esc(b.name)}"`):btn('Crear acceso','new-business','outline small',`data-id="${b.id}"`)}</div></article>`).join('')}</div></section>`; }
  function adminPromotions() { return `<section class="panel"><div class="section-heading"><div><h2>Gestión de promociones</h2><p>Editar una oferta no cambia los códigos ya emitidos.</p></div>${btn(`${icon('plus')} Crear promoción`,'new-promotion','primary')}</div><div class="filters"><label class="search-field">${icon('search')}<input id="promotion-search" placeholder="Buscar negocio o promoción" aria-label="Buscar negocio o promoción"></label><select id="publication-filter" aria-label="Filtrar publicación"><option value="">Todas las publicaciones</option><option value="draft" ${publicationFilter==='draft'?'selected':''}>Borrador · Por confirmar</option><option value="approved" ${publicationFilter==='approved'?'selected':''}>Confirmadas</option></select></div><div id="promotion-results">${promotionTable('',publicationFilter)}</div></section>`; }
  function promotionTable(search='', filter='') {
    const list=state.promotions.filter(p=>(!filter||p.publication===filter)&&`${business(p.business_id)?.name} ${p.description}`.toLocaleLowerCase('es').includes(search.toLocaleLowerCase('es')));
    return `<div class="table-wrap"><table class="data-table promotion-table"><thead><tr><th>Negocio</th><th>Promoción</th><th>Publicación</th><th>Gestión</th><th class="danger-column">Eliminar</th></tr></thead><tbody>${list.map(p=>`<tr><td data-label="Negocio"><strong>${esc(business(p.business_id)?.name||'Cuenta eliminada')}</strong></td><td data-label="Promoción">${esc(p.description)}</td><td data-label="Publicación">${publication(p)}</td><td data-label="Gestión">${btn(`${icon('edit')} Editar`,'edit-promotion','outline small',`data-id="${p.id}"`)}</td><td data-label="Eliminar" class="danger-column">${btn(icon('trash'),'delete-promotion','icon-button danger',`data-id="${p.id}" aria-label="Eliminar promoción de ${esc(business(p.business_id)?.name||'cuenta eliminada')}"`)}</td></tr>`).join('')}</tbody></table>${!list.length?'<p class="no-results">No hay promociones que coincidan con tu búsqueda.</p>':''}</div><p class="table-foot">${list.length} promociones · La publicación es independiente del estado del código.</p>`;
  }
  function codesPanel(admin) { return `<section class="panel codes-panel"><div class="section-heading"><div class="heading-with-icon">${icon('ticket')}<div><h2>${admin?'Códigos y estados':'Códigos de tus promociones'}</h2><p>Busca, consulta y confirma la redención de códigos activos.</p></div></div></div><div class="filters"><label class="search-field">${icon('search')}<input id="code-search" placeholder="Buscar por código…" aria-label="Buscar por código"></label><select id="status-filter" aria-label="Filtrar por estado"><option value="">Todos los estados</option><option>Activo</option><option>Redimido</option><option>Vencido</option></select>${admin?`<select id="business-filter" aria-label="Filtrar por negocio"><option value="">Todos los negocios</option>${state.businesses.map(b=>`<option value="${b.id}">${esc(b.name)}</option>`).join('')}</select>`:''}</div><div id="code-results">${codeTable()}</div></section>`; }
  function codeTable(search='',filter='',bid='') {
    const list=state.codes.filter(c=>(!filter||status(c)===filter)&&(!bid||bid===c.business_id)&&c.code.toLowerCase().includes(search.toLowerCase()));
    return `<div class="table-wrap"><table class="data-table codes-table"><thead><tr><th>Promoción</th><th>Código</th><th>Negocio</th><th>Estado</th><th>Acción</th></tr></thead><tbody>${list.map(c=>`<tr><td data-label="Promoción">${esc(c.description)}</td><td data-label="Código"><button class="code-copy" data-action="copy" data-code="${esc(c.code)}">${esc(c.code)} ${icon('copy')}</button></td><td data-label="Negocio">${esc(c.business_name)}</td><td data-label="Estado">${badge(c)}<small class="expiry">${status(c)==='Redimido'?'Redimido: '+date(c.redeemed_at):'Vence: '+date(c.expires_at)}</small></td><td data-label="Acción">${status(c)==='Activo'?btn('Marcar como redimido','confirm-redeem','yellow small',`data-code="${esc(c.code)}"`):`<span class="unavailable">${status(c)==='Redimido'?'Ya fue redimido':'Código vencido'}</span>`}</td></tr>`).join('')}</tbody></table>${!list.length?'<p class="no-results">No hay códigos que coincidan con tu búsqueda.</p>':''}</div><p class="table-foot">${list.length} códigos · Hora de Bogotá</p>`;
  }
  function homeView() { return !state.user?'inicio':state.user.role==='client'?'retos':state.user.role==='ally'?'aliado':'aliados'; }
  function render() {
    if(avatarPreviewUrl){URL.revokeObjectURL(avatarPreviewUrl);avatarPreviewUrl=null;}
    if(state.user?.must_change_password){$('#app').innerHTML=passwordPage();return;}
    currentView=location.hash.slice(1)||'inicio';
    const isPublic=['inicio','explorar','como-funciona','crear-cuenta','comunidad'].includes(currentView);
    if(!isPublic&&!state.user){location.hash='inicio';return;}
    if(!isPublic){const valid=state.user.role==='client'?['retos','mis-promociones','perfil']:state.user.role==='ally'?['aliado']:['aliados','promociones','codigos'];if(!valid.includes(currentView)){location.hash=homeView();return;}}
    $('#app').innerHTML=isPublic?publicPage():shell(currentView==='retos'?clientPage():currentView==='mis-promociones'?promotionsPage():currentView==='perfil'?profilePage():currentView==='aliado'?allyPage():adminPage());
    document.title=`${isPublic?'Descubre Chapinero':state.user.role==='client'?'Mi Chapitour':state.user.role==='ally'?'Panel del aliado':'Administración'} · Chapitour te premia`;
    if(['explorar','como-funciona','crear-cuenta','comunidad'].includes(currentView))requestAnimationFrame(()=>document.getElementById(currentView)?.scrollIntoView({behavior:'smooth'}));
  }
  let googleLibrary, authReturnToWheel=false;
  function loadGoogle() {
    if(window.google?.accounts?.id)return Promise.resolve();
    if(!googleLibrary)googleLibrary=new Promise((resolve,reject)=>{
      const script=document.createElement('script');script.src='https://accounts.google.com/gsi/client';script.async=true;
      script.onload=()=>resolve();script.onerror=()=>{script.remove();googleLibrary=null;reject(new Error('No se pudo cargar Google. Revisa tu conexión e intenta de nuevo.'));};document.head.append(script);
    });
    return googleLibrary;
  }
  async function mountGoogle() {
    const target=$('#google-signin',dialog);
    if(!state.google_auth?.enabled) {
      target.innerHTML='<button class="btn outline full" disabled>Continuar con Google</button><p class="modal-note">El registro con Google todavía no está disponible. Inténtalo más tarde.</p>';return;
    }
    target.textContent='Preparando acceso con Google…';
    try {
      await loadGoogle();if(!target.isConnected)return;
      google.accounts.id.initialize({client_id:state.google_auth.client_id,nonce:state.google_auth.nonce,auto_select:false,callback:async response=>{
        if(busy)return;busy=true;$('#modal-error').textContent='';
        try {
          await api('google_login',{credential:response.credential});await recordVisit();
          const showWheel=authReturnToWheel||state.campaign.ticket_kind==='welcome';
          closeModal();location.hash=homeView();render();
          if(showWheel)await previewWheel();else toast('Has ingresado con Google.');
        } catch(error){if(dialog.open)$('#modal-error').textContent=error.message;else toast(error.message);}
        finally{busy=false;}
      }});
      target.textContent='';google.accounts.id.renderButton(target,{type:'standard',theme:'outline',size:'large',text:'continue_with',shape:'pill',locale:'es',width:Math.min(360,target.clientWidth||280)});
    } catch(error){if(target.isConnected)target.textContent=error.message;}
  }
  async function authModal(register,link=false) {
    await api('state');
    if(dialog.classList.contains('wheel-modal'))authReturnToWheel=true;
    else if(!dialog.classList.contains('auth-modal'))authReturnToWheel=false;
    openModal(`<span class="eyebrow">TU PRÓXIMO PLAN EMPIEZA AQUÍ</span><h2 id="modal-title">${link?'Vincular mi cuenta con Google':register?'Crear mi cuenta con Google':'Qué bueno verte de nuevo'}</h2><p class="modal-lead">${link?'Selecciona la cuenta de Google con el mismo correo de tu perfil.':register?'Regístrate con tu cuenta de Google y disfruta tu primer giro.':'Ingresa con Google para volver a tus retos y promociones.'}</p>${!link?'<p class="modal-note registration-ranking-note">Las cuentas nuevas aparecen en la comunidad con un alias automático. Puedes cambiarlo u ocultarte desde Mi perfil.</p>':''}<div id="google-signin" class="google-signin"></div><p class="modal-note">Google verifica tu nombre y correo. Chapitour no recibe tu contraseña de Google.</p>${!register&&!link?`<div class="auth-divider">Acceso con contraseña</div><p class="modal-note">Administradores, aliados y cuentas existentes.</p><form data-form="login"><label>Correo electrónico o usuario<input name="email" type="text" autocomplete="username" maxlength="150" required placeholder="tu@correo.com"></label><label>Contraseña<input name="password" type="password" autocomplete="current-password" maxlength="72" required></label><button class="btn primary full" type="submit">Iniciar sesión ${icon('arrow')}</button></form>`:''}${!link?`<p class="auth-switch">${register?'¿Ya tienes cuenta?':'¿Primera vez por aquí?'} <button class="inline-button" data-action="${register?'login':'register'}">${register?'Ingresar':'Registrarme con Google'}</button></p>`:''}`,'auth-modal');
    mountGoogle();
  }
  function passwordPage() {
    return `${sandboxBar()}<main id="main" class="password-page"><section class="panel"><div class="modal-symbol yellow">${icon('lock')}</div><h1>Cambia tu contraseña temporal</h1><p>Elige una contraseña nueva para acceder a tu panel.</p><form data-form="change_password"><label>Contraseña actual<input name="current_password" type="password" autocomplete="current-password" required maxlength="72"></label><label>Nueva contraseña<input name="new_password" type="password" autocomplete="new-password" required minlength="8" maxlength="72"></label><label>Repite la nueva contraseña<input name="confirm_password" type="password" autocomplete="new-password" required minlength="8" maxlength="72"></label><p class="form-error" role="alert"></p><button type="submit" class="btn primary full">Guardar nueva contraseña</button></form>${btn('Cerrar sesión','logout','text-btn full')}</section></main>`;
  }
  function businessForm(id='') {
    const choices=state.businesses.filter(b=>!b.email);
    openModal(`<span class="eyebrow">CRECE LA COMUNIDAD</span><h2 id="modal-title">Crear cuenta de aliado</h2><form data-form="business"><label>Negocio<select name="business_id"><option value="">Registrar un negocio nuevo</option>${choices.map(b=>`<option value="${b.id}" ${b.id===id?'selected':''}>${esc(b.name)}</option>`).join('')}</select></label><label>Nombre si el negocio es nuevo<input name="name" maxlength="80" placeholder="Nombre del aliado"></label><label>Correo electrónico<input name="email" type="email" required maxlength="100" autocomplete="off"></label><label>Contraseña temporal<input name="password" type="password" minlength="8" maxlength="72" required autocomplete="new-password"></label><p class="modal-note">Puedes reutilizar el correo de una cuenta eliminada. El aliado deberá cambiar su contraseña al entrar y solo tendrá acceso a los datos de su negocio.</p><div class="modal-actions">${btn('Cancelar','close','outline')}<button type="submit" class="btn primary">Crear aliado ${icon('plus')}</button></div></form>`);
  }
  function wheelSVG(parts) {
    const colors=['#fa079a','#ffe43b','#7435f5','#20d7e2','#a827f0','#22c7a7'];
    const n=parts.length,step=360/Math.max(n,1);
    return `<svg id="wheel-disc" viewBox="0 0 400 400" role="img" aria-label="Ruleta de aliados: ${esc(parts.map(b=>b.name).join(', '))}"><circle cx="200" cy="200" r="196" fill="#161326"/>${parts.map((b,i)=>{
      const start=(i*step-90)*Math.PI/180,end=((i+1)*step-90)*Math.PI/180,mid=(i+.5)*step*Math.PI/180-Math.PI/2;
      const tx=200+121*Math.cos(mid),ty=200+121*Math.sin(mid);
      const words=b.name.split(' '),lines=[''];for(const word of words){const k=lines.length-1;if(lines[k].length+word.length>13&&lines[k])lines.push(word);else lines[k]+=(lines[k]?' ':'')+word;}
      const shape=n===1?'<circle cx="200" cy="200" r="187"/>':`<path d="M200 200L${200+187*Math.cos(start)} ${200+187*Math.sin(start)}A187 187 0 ${step>180?1:0} 1 ${200+187*Math.cos(end)} ${200+187*Math.sin(end)}Z"/>`;
      return `<g fill="${colors[i%colors.length]}" stroke="#12121e" stroke-width="2">${shape}</g><text x="${tx}" y="${ty-(lines.length-1)*8}" text-anchor="middle" fill="${[1,3,5].includes(i%6)?'#071017':'white'}" font-family="Arial,sans-serif" font-weight="700" font-size="${n>7?10:13}">${lines.map((line,j)=>`<tspan x="${tx}" dy="${j?17:0}">${esc(line)}</tspan>`).join('')}</text>`;
    }).join('')}<circle cx="200" cy="200" r="193" fill="none" stroke="#ff66da" stroke-width="3"/></svg>`;
  }
  function rewardNotice() {
    if (!state.campaign.can_spin) return '';
    return `<section class="info-strip reward-ready">${icon('gift')}<div><strong>${state.campaign.ticket_kind==='welcome'?'¡Tu giro de bienvenida está listo!':'¡Tu próxima sorpresa ya está aquí!'}</strong><p>${state.campaign.ticket_kind==='welcome'?'Gracias por unirte a Chapitour. Gira y descubre tu primera promoción.':'Tienes un giro disponible para descubrir una promoción.'}</p></div>${btn('Girar la ruleta','preview-wheel','yellow')}</section>`;
  }
  async function recordVisit() {
    if (state.user?.role!=='client') return;
    if (visitTask) return visitTask;
    visitTask=api('visit').finally(()=>{visitTask=null;});
    return visitTask;
  }
  async function previewWheel() {
    await api('state');
    const c=state.campaign;
    wheelDemo=state.user?.role==='admin';
    wheelParts=state.businesses.filter(b=>c.wheel_business_ids.includes(b.id));
    const preview=!wheelParts.length;
    if(preview)wheelParts=state.businesses;
    wheelTicket=c.ticket_id;
    const canSpin=c.can_spin||(wheelDemo&&wheelParts.length>0);
    const notes={configuration_pending:'Un administrador debe terminar de preparar la ruleta.',login_required:'Inicia sesión con tu cuenta de cliente para participar.',client_required:'La entrega de premios está disponible para cuentas de clientes.',promotions_pending:'Todavía no hay promociones aprobadas disponibles. Los giros que hayas ganado se conservan.',visits_pending:'Sigue explorando Chapitour. Tu giro se habilitará al completar tus visitas válidas.',ready:c.ticket_kind==='welcome'?'Tu giro de bienvenida está listo. Descubre tu primera promoción.':'Tu giro está listo. Descubre la promoción que te espera.'};
    openModal(`<div class="modal-brand">${brand()}</div>${wheelDemo?'<span class="wheel-demo-label">Modo de prueba · Sin premio real</span>':''}<h2 id="modal-title" class="wheel-title">¡Gira <span>la ruleta!</span></h2><p class="modal-lead">${wheelDemo?'Prueba la experiencia desde el panel de administrador':'Descubre una promoción especial de nuestros aliados'}</p><div class="wheel-wrap"><div class="wheel-pointer"></div>${wheelSVG(wheelParts)}<div class="wheel-hub">${icon('crown')}</div></div>${btn(`Girar ${icon('arrow')}`,'spin','yellow spin-button',canSpin?'':'disabled')}<p class="modal-note" role="status">${wheelDemo?(wheelParts.length?'Pulsa Girar para ver la animación. Esta prueba no genera códigos ni consume visitas o promociones.':'Añade un aliado para probar la ruleta.'):notes[c.reason]||notes.configuration_pending}</p>${!state.user?`<div class="wheel-auth-actions">${btn('Registrarme con Google','register','primary')}${btn('Ingresar','login','outline')}</div><p class="modal-note">Tu primer giro te espera. Regístrate o ingresa para guardar tu promoción.</p>`:''}<p class="wheel-disclaimer">${preview?'Vista previa de aliados. Todavía no hay ofertas disponibles para entregar.':'Solo se muestran negocios con promociones confirmadas y disponibles.'}${wheelDemo?'':' Esta oportunidad depende de tu dinámica de visitas a Chapitour.'}</p>`,'wheel-modal');
  }
  async function spinWheel() {
    if(spinBusy||(!wheelDemo&&!wheelTicket)||!wheelParts.length)return;
    const demo=wheelDemo;
    spinBusy=true;const button=$('[data-action="spin"]',dialog);button.disabled=true;button.textContent='Girando…';dialog.setAttribute('aria-busy','true');$('#modal-error').textContent='';
    const reduced=matchMedia('(prefers-reduced-motion: reduce)').matches;
    let disc=$('#wheel-disc'),loading=null;
    try {
      // Start moving on the click, while the server selects the real prize.
      if(!demo&&!reduced&&typeof disc.animate==='function')loading=disc.animate([{transform:'rotate(0deg)'},{transform:'rotate(360deg)'}],{duration:850,iterations:Infinity,easing:'linear'});
      let won;
      if(demo) {
        const selected=wheelParts[Math.floor(Math.random()*wheelParts.length)];
        won={business_id:selected.id,business_name:selected.name};
      } else {
        const result=await api('spin',{ticket_id:wheelTicket});
        won=result.codes.find(c=>c.code===result.won_code);
        if(!won)throw new Error('Consulta Mis promociones para ver tu premio.');
      }
      const rotation=loading?Number(loading.currentTime||0)/850*360:0;
      loading?.cancel();loading=null;
      if(!wheelParts.some(b=>b.id===won.business_id)) {wheelParts.push({id:won.business_id,name:won.business_name});disc.outerHTML=wheelSVG(wheelParts);disc=$('#wheel-disc');}
      const index=wheelParts.findIndex(b=>b.id===won.business_id),target=360-(index+.5)*360/wheelParts.length;
      const angle=rotation+360*5+((target-rotation%360+360)%360);
      if(!reduced&&typeof disc.animate==='function') {
        const animation=disc.animate([{transform:`rotate(${rotation}deg)`},{transform:`rotate(${angle}deg)`}],{duration:3800,easing:'cubic-bezier(.13,.66,.08,1)',fill:'forwards'});
        await animation.finished;
      }
      if(demo)demoSpinResult(won);else {render();prizeModal(won,true);}
    } finally {
      loading?.cancel();spinBusy=false;dialog.removeAttribute('aria-busy');
      if(button.isConnected){button.disabled=false;button.innerHTML=`Girar ${icon('arrow')}`;}
    }
  }
  function demoSpinResult(result) {
    const b=business(result.business_id),offer=state.promotions.find(p=>p.business_id===result.business_id&&p.publication==='approved');
    openModal(`<span class="wheel-demo-label">Modo de prueba · Sin premio real</span><div class="modal-symbol yellow">${icon(b?.icon||'gift')}</div><h2 id="modal-title">Giro de prueba completado</h2><p class="modal-lead">La ruleta se detuvo en <strong>${esc(result.business_name)}</strong>.</p>${offer?`<div class="demo-offer"><span>Promoción confirmada del negocio</span><p>${esc(offer.description)}</p></div>`:''}<p class="modal-note">Esta es una demostración de la animación. No has ganado una promoción ni se ha generado un código.</p><div class="modal-actions">${btn('Volver a probar','preview-wheel','yellow')}${btn('Cerrar','close','outline')}</div>`,'wheel-demo-result');
  }
  function prizeModal(c,won=false) {
    const b=business(c.business_id);
    openModal(`<div class="modal-brand">${brand()}</div><h2 id="modal-title" class="prize-title">${won?'¡Te ganaste una<br><span>promoción especial!</span>':'Tu promoción<br><span>en Chapitour</span>'}</h2><p class="modal-lead">${won?'Tu premio está listo para reclamar.':'Consulta el detalle de tu promoción.'}</p><div class="prize-detail"><div class="prize-business"><div class="prize-icon large">${icon(b?.icon||'gift')}</div><h3>${esc(c.business_name)}</h3><span class="prize-ribbon">TU PREMIO</span></div><dl><div><dt>${icon('store')} Negocio</dt><dd>${esc(c.business_name)}</dd></div><div><dt>${icon('tag')} Promoción</dt><dd>${esc(c.description)}${c.conditions?`<small>${esc(c.conditions)}</small>`:''}</dd></div><div><dt>${icon('clock')} Tiempo para reclamar</dt><dd>Tienes 72 horas para reclamar esta promoción<small>Desde su generación. Vence el ${date(c.expires_at)}.</small></dd></div><div><dt>${icon('ticket')} Código único · ${status(c)}</dt><dd><button class="code-copy" data-action="copy" data-code="${esc(c.code)}">${esc(c.code)} ${icon('copy')}</button></dd></div></dl></div>${status(c)==='Activo'?whatsappButton(c,'yellow full'):badge(c)}<p class="modal-note">Al hacer clic se abrirá WhatsApp con el negocio, la promoción y tu código listos. Debes pulsar “Enviar”. La redención la confirma el negocio al aplicar la promoción.</p><div class="modal-security">${icon('lock')} Código único, personal, de un solo uso y válido por tiempo limitado.</div>`,'prize-modal');
  }
  function confirmRedeem(c) {
    openModal(`<div class="modal-symbol yellow">${icon('ticket')}</div><h2 id="modal-title">¿Confirmas que vas a aplicar esta promoción al cliente?</h2><p class="modal-lead">Esta acción marcará el código como redimido y no podrá utilizarse nuevamente.</p><dl class="confirm-details"><div><dt>Negocio</dt><dd>${esc(c.business_name)}</dd></div><div><dt>Promoción</dt><dd>${esc(c.description)}</dd></div><div><dt>Código</dt><dd class="mono">${esc(c.code)}</dd></div></dl><div class="state-transition"><span class="status activo"><i></i>Activo</span>${icon('arrow')}<span class="status redimido"><i></i>Redimido</span></div><div class="modal-actions">${btn('Cancelar','close','outline')}${btn('Confirmar redención','redeem','yellow',`data-code="${esc(c.code)}"`)}</div>`);
  }
  const promotionLabels = {business_id:'Negocio',description:'Beneficio / descripción',whatsapp:'WhatsApp del negocio',included:'Productos o servicios incluidos',hours:'Horarios',restrictions:'Restricciones',confirmed:'Confirmación con el negocio'};
  function promotionErrors(form) {
    const errors={}, approved=form.elements.publication.value==='approved';
    for(const [name,label] of Object.entries(promotionLabels)) {
      const field=form.elements.namedItem(name);
      if(name==='confirmed') { if(approved&&!field.checked)errors[name]='Marca la casilla después de confirmar los datos con el negocio.'; continue; }
      const value=field.value.trim();
      if(!value&&(approved||['business_id','description'].includes(name)))errors[name]=`Completa ${label.toLocaleLowerCase('es')}.`;
      else if(field.maxLength>0&&value.length>field.maxLength)errors[name]=`Usa un máximo de ${field.maxLength} caracteres.`;
    }
    const phone=form.elements.whatsapp.value.replace(/[\s+()-]/g,'');
    if(phone&&!/^[1-9][0-9]{7,14}$/.test(phone))errors.whatsapp='Revisa el número de WhatsApp con su indicativo de país.';
    return errors;
  }
  function validatePromotion(form, focus=false) {
    const errors=promotionErrors(form);
    for(const name of Object.keys(promotionLabels)) {
      const field=form.elements.namedItem(name),message=$(`#error-${name}`,form);
      message.textContent=errors[name]||'';
      if(errors[name])field.setAttribute('aria-invalid','true');else field.removeAttribute('aria-invalid');
    }
    const missing=Object.keys(errors),summary=$('#promotion-validation',form);
    summary.textContent=missing.length?`Falta revisar: ${missing.map(name=>promotionLabels[name]).join(', ')}.`:'';
    summary.hidden=!missing.length;
    if(focus&&missing.length)form.elements.namedItem(missing[0]).focus();
    return !missing.length;
  }
  function updatePromotionForm(form) {
    const approved=form.elements.publication.value==='approved';
    for(const name of ['whatsapp','included','hours','restrictions','confirmed'])form.elements.namedItem(name).required=approved;
    if(form.dataset.validated)validatePromotion(form);
  }
  function promotionForm(id) {
    const p=state.promotions.find(p=>p.id===id),b=business(p?.business_id);
    const error=name=>`<small class="field-error" id="error-${name}"></small>`;
    openModal(`<span class="eyebrow">GESTIÓN DE PROMOCIONES</span><h2 id="modal-title">${p?'Editar':'Crear'} promoción</h2>
      <p class="modal-lead">Las ofertas provisionales permanecen fuera de la entrega de premios.</p>
      <form data-form="promotion" novalidate><input type="hidden" name="id" value="${esc(p?.id||'')}"><input type="hidden" name="publication" value="approved">
        <label>Negocio<select name="business_id" required aria-describedby="error-business_id"><option value="">Selecciona un aliado</option>${state.businesses.map(b=>`<option value="${b.id}" ${p?.business_id===b.id?'selected':''}>${esc(b.name)}</option>`).join('')}</select>${error('business_id')}</label>
        <label>Beneficio / descripción<textarea name="description" required maxlength="500" rows="3" aria-describedby="error-description">${esc(p?.description||'')}</textarea>${error('description')}</label>
        <p>Estado actual: ${p?publication(p):'<span class="publication draft">Sin guardar</span>'}</p>
        <label>WhatsApp del negocio<input name="whatsapp" type="tel" maxlength="25" value="${esc(b?.whatsapp||'')}" placeholder="Indicativo del país + número" aria-describedby="error-whatsapp">${error('whatsapp')}</label>
        <p class="approval-help" id="approval-help">Completa los datos y marca la casilla de confirmación. Pulsa <strong>Confirmar promoción</strong> para guardarla como confirmada y habilitarla para la ruleta. <strong>Guardar borrador</strong> la mantiene fuera de la ruleta.</p>
        <label>Productos o servicios incluidos<textarea name="included" maxlength="350" rows="2" placeholder="Información confirmada por el negocio" aria-describedby="error-included">${esc(p?.included||'')}</textarea>${error('included')}</label>
        <div class="form-grid"><label>Horarios<textarea name="hours" maxlength="250" rows="2" aria-describedby="error-hours">${esc(p?.hours||'')}</textarea>${error('hours')}</label>
        <label>Restricciones<textarea name="restrictions" maxlength="350" rows="2" aria-describedby="restrictions-help error-restrictions">${esc(p?.restrictions||'')}</textarea><small id="restrictions-help" class="field-help">Si el negocio confirma que no hay restricciones, indícalo aquí.</small>${error('restrictions')}</label></div>
        <label class="checkbox-label"><input name="confirmed" type="checkbox" aria-describedby="error-confirmed"> He confirmado con el negocio el beneficio, los productos o servicios, los horarios, las restricciones y su WhatsApp.</label>${error('confirmed')}
        <p class="modal-note">La vigencia del código es siempre de 72 horas. Los cambios se guardan en la base de datos.</p>
        <p id="promotion-validation" class="validation-summary" role="alert" hidden></p>
        <div class="modal-actions promotion-actions"><button class="btn primary" type="submit" data-publication="approved">Confirmar promoción ${icon('check')}</button><button class="btn outline" type="submit" data-publication="draft">Guardar borrador</button>${btn('Cancelar','close','text-btn')}</div>
      </form>`,'form-modal');
    updatePromotionForm($('[data-form="promotion"]',dialog));
  }
  function deleteModal(type,id) {
    const p=state.promotions.find(p=>p.id===id),b=business(id);
    const name=type==='account'?state.user.name:type==='business'?b?.name:p?.description;
    const detail=type==='business'?'Se eliminará esta cuenta de acceso y se cerrarán sus sesiones. El negocio seguirá visible en la página principal, con su página, promociones y códigos intactos. Podrás crear un nuevo acceso con el mismo correo.':type==='promotion'?'Se eliminará esta promoción. Los códigos ya emitidos conservarán su beneficio y su vigencia.':'Se eliminarán tus datos de perfil y se desactivará tu acceso. El historial de premios y redenciones se conserva. Esta acción no se puede deshacer.';
    openModal(`<div class="modal-symbol pink">${icon('trash')}</div><h2 id="modal-title">${type==='account'?'Eliminar mi cuenta':type==='business'?'Eliminar cuenta de acceso':'Eliminar promoción'}</h2><div class="delete-target">${type==='promotion'?`<strong>${esc(business(p.business_id)?.name||'Cuenta eliminada')}</strong>`:''}<p>${esc(name)}</p>${type==='account'?`<small>${esc(state.user.email)}</small>`:type==='business'?`<small>${esc(b?.email)}</small>`:''}</div><p class="modal-lead">${detail}</p><div class="modal-actions">${btn('Cancelar','close','outline')}${btn('Confirmar eliminación','execute-delete','danger-fill',`data-type="${type}" data-id="${esc(id)}" ${type==='business'?`data-account-id="${esc(b?.account_id)}"`:''}`)}</div>`);
  }
  async function dispatch(el) {
    const action=el.dataset.action,code=el.dataset.code,id=el.dataset.id;
    switch(action) {
      case 'close':closeModal();break;
      case 'register':await authModal(true);break;
      case 'login':await authModal(false);break;
      case 'logout':await api('logout');location.hash='inicio';render();break;
      case 'ranking-page':await rankingPage(Number(el.dataset.page)||1);break;
      case 'remove-avatar':await api('remove_avatar');render();toast('Foto de perfil eliminada.');break;
      case 'google-link':await authModal(false,true);break;
      case 'preview-wheel':await previewWheel();break;
      case 'spin':await spinWheel();break;
      case 'prize':prizeModal(state.codes.find(c=>c.code===code));break;
      case 'copy':await copyText(code);toast('Código copiado');break;
      case 'share': { const share={title:'Descubre Chapinero con Chapitour',text:'Encuentra lugares, planes y experiencias en Chapinero.',url:'https://chapitour.co/'};if(navigator.share){try{await navigator.share(share);toast('Tu progreso no cambia al abrir la opción de compartir.');}catch(e){if(e.name!=='AbortError')throw e;}}else{await copyText(share.url);toast('Enlace copiado. Compartirlo no confirma las entregas.');}break; }
      case 'photo-progress':photoProgress();break;
      case 'question-places':questionPlaces();break;
      case 'answer-place':questionForm(id);break;
      case 'whatsapp': { const r=await api('whatsapp',{code});window.location.assign(r.whatsapp_url);break; }
      case 'confirm-redeem':confirmRedeem(state.codes.find(c=>c.code===code));break;
      case 'redeem':await api('redeem',{code,confirm:code});closeModal();render();toast('Código marcado como redimido. No podrá volver a utilizarse.');break;
      case 'pending':publicationFilter='draft';location.hash='promociones';render();$('#publication-filter').value='draft';$('#promotion-results').innerHTML=promotionTable('','draft');$('#promotion-results').scrollIntoView({behavior:'smooth',block:'center'});break;
      case 'new-promotion':promotionForm();break;
      case 'edit-promotion':promotionForm(id);break;
      case 'new-business':businessForm(id||'');break;
      case 'delete-account':deleteModal('account',state.user.id);break;
      case 'delete-business':deleteModal('business',id);break;
      case 'delete-promotion':deleteModal('promotion',id);break;
      case 'execute-delete': { const type=el.dataset.type;await api(type==='account'?'delete_account':type==='business'?'delete_business':'delete_promotion',{id,confirm:id,...(type==='business'?{account_id:el.dataset.accountId}:{})});closeModal();if(type==='account')location.hash='inicio';render();toast(type==='business'?'Acceso eliminado. El negocio sigue publicado.':'Eliminación completada.');break; }
    }
  }
  async function copyText(value) { if(navigator.clipboard&&window.isSecureContext){await navigator.clipboard.writeText(value);return;}const area=document.createElement('textarea');area.value=value;area.className='clipboard-fallback';(dialog.open?dialog:document.body).appendChild(area);area.select();const ok=document.execCommand('copy');area.remove();if(!ok)throw new Error('No se pudo copiar. Selecciona y copia el código manualmente.'); }
  document.addEventListener('click',async e=>{const el=e.target.closest('[data-action]');if(!el||el.disabled||busy)return;busy=true;try{await dispatch(el);}catch(error){if(dialog.open)$('#modal-error').textContent=error.message;else toast(error.message);}finally{busy=false;}});
  document.addEventListener('submit',async e=>{
    const form=e.target.closest('[data-form]');if(!form)return;e.preventDefault();if(busy)return;
    if(dialog.open)$('#modal-error').textContent='';
    if(form.dataset.form==='promotion'){form.elements.publication.value=e.submitter?.dataset.publication||'approved';form.dataset.validated='true';updatePromotionForm(form);if(!validatePromotion(form,true))return;}
    busy=true;const submits=form.querySelectorAll('button');submits.forEach(button=>button.disabled=true);
    try{let fields=Object.fromEntries(new FormData(form));const type=form.dataset.form;if(type==='upload_avatar')fields=await avatarUpload(form.elements.avatar.files[0]);if(type==='profile'&&state.user.community?.ready)fields.ranking_visible=form.elements.ranking_visible.checked?'1':'0';if(type==='promotion')fields.confirmed=!!form.elements.confirmed.checked;await api(({promotion:'save_promotion',business:'save_business'})[type]||type,fields);if(['login','register'].includes(type))await recordVisit();const reopenWheel=type==='login'&&authReturnToWheel;closeModal();if(['register','login','change_password'].includes(type))location.hash=homeView();if(type==='promotion'&&fields.publication==='approved'&&publicationFilter==='draft')publicationFilter='';render();if(reopenWheel)await previewWheel();if(type==='record_photo'){photoProgress();toast('Lugar registrado. Cada negocio suma una sola vez.');return;}if(type==='answer_questions'){questionPlaces();toast('Respuesta registrada en tu meta de preguntas.');return;}toast(type==='upload_avatar'?'Tu foto de perfil está guardada.':type==='register'?'Tu cuenta está lista. ¡Empieza a explorar!':type==='promotion'?(fields.publication==='approved'?'Promoción confirmada y guardada.':'Borrador guardado. La oferta aún no participa en la ruleta.'):'Cambios guardados.');}
    catch(error){const target=dialog.open?$('#modal-error'):$('.form-error',form);target.textContent=error.message;target.scrollIntoView({block:'nearest'});}finally{submits.forEach(button=>button.disabled=false);busy=false;}
  });
  function filters(e){if(e.target.id==='avatar-file'){previewAvatar();return;}if(e.target.id==='photo-place'){photoMentions();return;}const promotion=e.target.closest('[data-form="promotion"]');if(promotion){updatePromotionForm(promotion);return;}if(['code-search','status-filter','business-filter'].includes(e.target.id))$('#code-results').innerHTML=codeTable($('#code-search').value,$('#status-filter').value,$('#business-filter')?.value||'');if(['promotion-search','publication-filter'].includes(e.target.id)){publicationFilter=$('#publication-filter').value;$('#promotion-results').innerHTML=promotionTable($('#promotion-search').value,publicationFilter);}}
  document.addEventListener('input',filters);document.addEventListener('change',filters);
  window.addEventListener('hashchange',()=>{render();if(!['explorar','como-funciona','crear-cuenta','comunidad'].includes(location.hash.slice(1)))window.scrollTo(0,0);});
  // Expired codes cannot remain actionable in a panel left open past their deadline.
  setInterval(()=>{if(!state||dialog.open||busy)return;const changed=state.codes.some(c=>c.status!==status(c));if(changed){state.codes.forEach(c=>c.status=status(c));render();}},30000);
  api('state').then(async()=>{await recordVisit();render();}).catch(error=>{$('#app').innerHTML=`<main id="main" class="loading"><h1>No pudimos abrir Chapitour</h1><p>${esc(error.message)}</p><a class="btn primary" href="">Volver a intentar</a></main>`;});
})();
