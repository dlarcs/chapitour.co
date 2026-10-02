const assert=require('node:assert/strict');
const fs=require('node:fs');
const {execFileSync}=require('node:child_process');
const {chromium,request}=require('playwright');
const base='http://127.0.0.1:8792/pruebas/chapitour-premia/';
const socket=process.argv[2];
if(!/^\/private\/tmp\/chapitour-panel-qa\.[A-Za-z0-9]+\/mysql\.sock$/.test(socket||''))throw Error('Solo una base QA temporal.');
const output='/private/tmp/chapitour-panels-qa';fs.mkdirSync(output,{recursive:true});
(async()=>{
  const contexts=[];let browser;
  try {
    async function actor(){
      const http=await request.newContext();contexts.push(http);
      let state=await(await http.get(base+'api.php')).json();assert(state.csrf,JSON.stringify(state));
      return {http,get state(){return state;},async refresh(){state=await(await http.get(base+'api.php')).json();return state;},async call(action,data={},expected=200){
        const r=await http.post(base+'api.php',{headers:{'X-CSRF-Token':state.csrf},data:{action,...data}});const b=await r.json();
        assert.equal(r.status(),expected,JSON.stringify(b));if(r.ok())state=b;return b;
      }};
    }
    const admin=await actor(),client=await actor(),guest=await actor(),stamp=Date.now();
    await admin.call('login',{email:'laurazoro@gmail.com',password:'Qa-admin-new-456!'});
    assert(admin.state.campaign.eligible_promotions>0,'Se requiere una oferta QA aprobada.');
    await admin.call('spin',{ticket_id:'1'},403);await guest.call('spin',{ticket_id:'1'},403);
    // Existing password client fixture; new registrations now use verified Google credentials.
    execFileSync('/Applications/XAMPP/xamppfiles/bin/php',['-r',`
      $db=new PDO('mysql:unix_socket='.$argv[1].';dbname=chapitour_panels_qa','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
      $s=$db->prepare('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)');$s->execute(['Prueba local de ruleta',$argv[2],password_hash('Qa-wheel-456!',PASSWORD_BCRYPT)]);
    `,socket,`wheel-${stamp}@example.invalid`]);
    await client.call('login',{email:`wheel-${stamp}@example.invalid`,password:'Qa-wheel-456!'});
    await client.call('visit');assert(!client.state.campaign.can_spin);
    browser=await chromium.launch({headless:true,executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',args:['--no-sandbox']});
    const adminContext=await browser.newContext({storageState:await admin.http.storageState(),viewport:{width:1280,height:960}});
    const page=await adminContext.newPage(),errors=[];page.on('pageerror',e=>errors.push(e.message));
    let adminWrites=0;page.on('request',r=>{if(r.method()==='POST')adminWrites++;});
    const codesBefore=JSON.stringify(admin.state.codes);
    await page.goto(base+'#promociones');await page.getByRole('button',{name:'Probar ruleta',exact:true}).click();
    await page.getByText('Modo de prueba · Sin premio real',{exact:true}).waitFor();
    await page.getByRole('button',{name:'Girar',exact:true}).click();
    await page.waitForFunction(()=>document.querySelector('#wheel-disc')?.getAnimations().some(a=>a.currentTime>0));
    assert(await page.locator('[data-action="spin"]').isDisabled());await page.keyboard.press('Escape');assert(await page.locator('dialog').evaluate(d=>d.open));
    await page.getByRole('heading',{name:'Giro de prueba completado'}).waitFor();
    assert.equal(await page.locator('dialog [data-action="whatsapp"], dialog .code-copy').count(),0);
    await page.screenshot({path:output+'/giro-admin-desktop.png'});
    await page.setViewportSize({width:390,height:844});await page.emulateMedia({reducedMotion:'reduce'});
    await page.getByRole('button',{name:'Volver a probar'}).click();await page.getByRole('button',{name:'Girar',exact:true}).click();
    await page.getByRole('heading',{name:'Giro de prueba completado'}).waitFor();
    await page.screenshot({path:output+'/giro-admin-mobile.png'});
    assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
    assert.equal(adminWrites,0);await admin.refresh();assert.equal(JSON.stringify(admin.state.codes),codesBefore);
    console.log('PASS giro admin animado, bloqueo de doble clic, repetición, móvil y movimiento reducido; cero escrituras o códigos');
    const guestPage=await browser.newPage();await guestPage.goto(base);await guestPage.locator('[data-action="preview-wheel"]').click();
    assert(await guestPage.locator('[data-action="spin"]').isDisabled());
    await guestPage.getByRole('button',{name:'Registrarme con Google',exact:true}).waitFor();
    assert(await guestPage.getByRole('button',{name:'Ingresar',exact:true}).isVisible());
    await guestPage.getByRole('button',{name:'Registrarme con Google',exact:true}).click();
    await guestPage.getByRole('heading',{name:'Crear mi cuenta con Google'}).waitFor();
    assert.equal(await guestPage.locator('[data-form="register"]').count(),0);
    assert((await guestPage.locator('dialog').innerText()).includes('Google'));
    await guestPage.screenshot({path:output+'/registro-google-pendiente.png'});
    const clientContext=await browser.newContext({storageState:await client.http.storageState(),viewport:{width:1280,height:960}});
    const clientPage=await clientContext.newPage();clientPage.on('pageerror',e=>errors.push(e.message));
    await clientPage.goto(base);await clientPage.locator('[data-action="preview-wheel"]').click();assert(await clientPage.locator('[data-action="spin"]').isDisabled());await clientPage.keyboard.press('Escape');
    // Only this isolated fixture can prepare seven visits; the application exposes no bypass.
    const id=client.state.user.id.replace('client-','');
    execFileSync('/Applications/XAMPP/xamppfiles/bin/php',['-r',`
      $db=new PDO('mysql:unix_socket='.$argv[1].';dbname=chapitour_panels_qa','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
      $s=$db->prepare("SELECT email FROM cp_clientes WHERE id=?");$s->execute([$argv[2]]);
      if(!preg_match('/^wheel-[0-9]+@example\\.invalid$/',$s->fetchColumn()))throw new Exception('Solo cliente QA');
      $s=$db->prepare("UPDATE cp_panel_visitas SET visitas_ciclo=7,ultima_visita_at=DATE_SUB(UTC_TIMESTAMP(),INTERVAL 4 HOUR) WHERE cliente_id=?");$s->execute([$argv[2]]);
    `,socket,id]);
    await client.call('visit');assert(client.state.campaign.can_spin);const ticket=client.state.campaign.ticket_id;
    await clientPage.goto(base+'#retos');await clientPage.reload();await clientPage.locator('.reward-ready [data-action="preview-wheel"]').click();
    let release,lose=true,wonCode,spinRequests=0;const hold=new Promise(r=>{release=r;});
    await clientPage.route('**/api.php',async route=>{
      if(route.request().method()==='POST'&&route.request().postDataJSON().action==='spin'){
        spinRequests++;
        if(lose){lose=false;const response=await route.fetch();wonCode=(await response.json()).won_code;await hold;await route.abort('failed');return;}
      }
      await route.continue();
    });
    await clientPage.locator('[data-action="spin"]').click();
    await clientPage.waitForFunction(()=>document.querySelector('#wheel-disc')?.getAnimations().some(a=>a.currentTime>50));
    await clientPage.keyboard.press('Escape');assert(await clientPage.locator('dialog').evaluate(d=>d.open));release();
    await clientPage.locator('#modal-error').filter({hasText:/./}).waitFor();
    assert.equal(await clientPage.locator('#wheel-disc').evaluate(d=>d.getAnimations().length),0);
    await clientPage.locator('[data-action="spin"]').click();await clientPage.getByRole('heading',{name:'¡Te ganaste una promoción especial!'}).waitFor();
    assert(wonCode);assert((await clientPage.locator('dialog').innerText()).includes(wonCode));assert.equal(spinRequests,2);
    const retry=await client.call('spin',{ticket_id:ticket});assert.equal(retry.won_code,wonCode);assert.equal(client.state.codes.length,1);
    assert.equal(client.state.codes[0].expires_at-client.state.codes[0].created_at,72*3600);
    assert.deepEqual(errors,[]);
    console.log('PASS clientes conservan elegibilidad; animación inmediata antes de respuesta, recuperación de error y un solo premio por giro');
  } finally {if(browser)await browser.close();for(const c of contexts)await c.dispose();}
})().catch(e=>{console.error(e);process.exitCode=1;});
