const assert=require('node:assert/strict');
const fs=require('node:fs');
const {chromium,request}=require('playwright');
const base='http://127.0.0.1:8797/',api=base+'premia/api.php';
const socket=process.argv[2]||'';
if(!/^\/private\/tmp\/chapitour-panel-qa\.[A-Za-z0-9]+\/mysql\.sock$/.test(socket))throw Error('Solo QA temporal.');
const root=socket.replace('/mysql.sock',''),fixture=JSON.parse(fs.readFileSync(root+'/production-ui.json'));
(async()=>{
  const contexts=[];let browser;
  async function actor(email,password){
    const http=await request.newContext();contexts.push(http);
    let state=await(await http.get(api)).json();assert(state.csrf);
    const call=async(action,data={})=>{const res=await http.post(api,{headers:{'X-CSRF-Token':state.csrf},data:{action,...data}});const result=await res.json();assert.equal(res.status(),200,JSON.stringify(result));state=result;return result;};
    if(email)await call('login',{email,password});
    return {http,call,get state(){return state;}};
  }
  try{
    const guest=await actor(),client=await actor(fixture.email,'Qa-production-456!'),admin=await actor('laurazoro@gmail.com','Qa-admin-new-456!');
    assert(client.state.campaign.can_spin);assert.equal(client.state.campaign.ticket_kind,'welcome');
    assert.equal(client.state.campaign.visits_per_reward,undefined);assert.equal(client.state.campaign.new_visit_after,undefined);
    browser=await chromium.launch({channel:'chrome',headless:true});
    const page=await browser.newPage({viewport:{width:1440,height:1000}}),errors=[],badAssets=[];
    page.on('pageerror',e=>errors.push(e.message));
    page.on('response',r=>{if(r.status()>=400&&/\.(?:css|js|png|jpg|jpeg|svg)(?:\?|$)/.test(r.url()))badAssets.push(r.url());});
    await page.goto(base);await page.locator('[data-action="spin"]').waitFor();
    assert(await page.locator('[data-action="spin"]').isEnabled());
    assert.equal(await page.locator('#app [data-action="wheel"], #app [data-action="spin"]').count(),0);
    assert.equal(await page.locator('.sandbox-bar').count(),0);
    assert.equal(await page.locator('a[href*="pruebas"],script[src*="pruebas"],link[href*="pruebas"]').count(),0);
    assert.equal(await page.locator('meta[name="robots"]').getAttribute('content'),'index,follow,max-image-preview:large');
    for(const width of [1440,390,320]){
      await page.setViewportSize({width,height:900});
      assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
      assert(await page.locator('dialog').evaluate(d=>d.scrollWidth<=d.clientWidth+1));
      await page.screenshot({path:root+`/bienvenida-${width}.png`,fullPage:true});
    }
    await page.keyboard.press('Escape');
    assert.equal(await page.locator('#app [data-action="wheel"], #app [data-action="spin"]').count(),0);
    assert((await page.locator('.signup-banner').innerText()).includes('Registrarme con Google'));
    await page.reload();await page.locator('[data-action="spin"]').waitFor();
    await page.locator('[data-action="spin"]').click();
    await page.waitForFunction(()=>document.querySelector('#wheel-disc')?.getAnimations().some(a=>a.currentTime>40));
    assert(await page.locator('[data-action="spin"]').isDisabled());
    await page.getByRole('heading',{name:'¡Te ganaste una promoción especial!'}).waitFor();
    await page.getByRole('button',{name:'Crear mi cuenta con Google',exact:true}).waitFor();
    assert(await page.locator('dialog').evaluate(d=>d.scrollWidth<=d.clientWidth+1));
    await page.screenshot({path:root+'/bienvenida-premio-320.png',fullPage:true});
    const prizeCode=await page.locator('dialog [data-action="copy"]').getAttribute('data-code');
    assert(/^CHAPI-[A-Z]{3}-\d{3,}$/.test(prizeCode));
    // No WhatsApp navigation/message is sent during this test.
    const wa=await page.evaluate(async code=>{
      let s=await(await fetch('/premia/api.php')).json();
      return(await fetch('/premia/api.php',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-Token':s.csrf},body:JSON.stringify({action:'whatsapp',code})})).json();
    },prizeCode);
    assert(decodeURIComponent(wa.whatsapp_url).includes(prizeCode));assert.equal(wa.codes[0].status,'Activo');
    await page.reload();await page.locator('.guest-prize-notice').waitFor();
    assert(!(await page.locator('dialog').evaluate(d=>d.open)));
    await page.getByRole('button',{name:'Ver mi premio',exact:true}).click();
    assert.equal(await page.locator('dialog [data-action="copy"]').getAttribute('data-code'),prizeCode);
    await page.keyboard.press('Escape');
    assert(!/8 visitas|4 horas|ocho visitas|cuatro horas/i.test(await page.locator('body').innerText()));
    const adminContext=await browser.newContext({storageState:await admin.http.storageState()});
    const adminPage=await adminContext.newPage();await adminPage.goto(base+'#promociones');await adminPage.getByRole('heading',{name:'Gestión de promociones'}).waitFor();
    assert.equal(await adminPage.getByRole('button',{name:'Probar ruleta'}).count(),0);assert(!/8 visitas|4 horas|ocho visitas|cuatro horas/i.test(await adminPage.locator('body').innerText()));
    const clientContext=await browser.newContext({storageState:await client.http.storageState(),viewport:{width:390,height:900}});
    const clientPage=await clientContext.newPage();clientPage.on('pageerror',e=>errors.push(e.message));
    await clientPage.goto(base);await clientPage.locator('[data-action="spin"]').waitFor();
    assert(await clientPage.locator('[data-action="spin"]').isEnabled());
    const ticket=client.state.campaign.ticket_id;
    let release,lost=false,wonCode,requests=0;const hold=new Promise(r=>release=r);
    await clientPage.route('**/premia/api.php',async route=>{
      if(route.request().method()==='POST'&&route.request().headers()['content-type']?.includes('application/json')&&route.request().postDataJSON().action==='spin'){
        requests++;
        if(!lost){lost=true;const response=await route.fetch();wonCode=(await response.json()).won_code;await hold;await route.abort('failed');return;}
      }
      await route.continue();
    });
    await clientPage.locator('[data-action="spin"]').click();
    await clientPage.waitForFunction(()=>document.querySelector('#wheel-disc')?.getAnimations().some(a=>a.currentTime>40));
    assert(await clientPage.locator('[data-action="spin"]').isDisabled());await clientPage.keyboard.press('Escape');assert(await clientPage.locator('dialog').evaluate(d=>d.open));
    release();await clientPage.locator('#modal-error').filter({hasText:/./}).waitFor();
    await clientPage.locator('[data-action="spin"]').click();
    await clientPage.getByRole('heading',{name:'¡Te ganaste una promoción especial!'}).waitFor();
    assert(wonCode&&/^CHAPI-[A-Z]{3}-\d{3,}$/.test(wonCode));assert.equal(requests,2);
    assert((await clientPage.locator('dialog').innerText()).includes(wonCode));
    const again=await client.call('spin',{ticket_id:ticket});assert.equal(again.won_code,wonCode);assert.equal(again.codes.length,1);
    assert.equal(again.codes[0].expires_at-again.codes[0].created_at,72*3600);
    await clientPage.screenshot({path:root+'/principal-giro-390.png',fullPage:true});
    await clientPage.keyboard.press('Escape');await clientPage.goto(base+'#perfil');await clientPage.locator('[data-form="upload_avatar"]').waitFor();assert(!/8 visitas|4 horas/i.test(await clientPage.locator('body').innerText()));
    const png=Buffer.from(await clientPage.evaluate(()=>{const c=document.createElement('canvas');c.width=c.height=50;c.getContext('2d').fillRect(0,0,50,50);return c.toDataURL().split(',')[1];}),'base64');
    await clientPage.setInputFiles('#avatar-file',{name:'qa.png',mimeType:'image/png',buffer:png});await clientPage.locator('[data-form="upload_avatar"] button[type="submit"]').click();
    await clientPage.locator('.profile-photo img[src^="/premia/avatar.php"]').waitFor();
    await clientPage.waitForFunction(()=>document.querySelector('.profile-photo img')?.naturalWidth===512);
    // Automatic reads update state; a refresh that started before an action must not overwrite it.
    await page.setViewportSize({width:1440,height:1000});await page.clock.install();await page.bringToFront();await page.goto(base);
    await page.locator('.signup-banner').waitFor();
    let reads=0;await page.route('**/premia/api.php',async route=>{
      if(route.request().method()==='GET'){
        reads++;const result=await(await route.fetch()).json();result.leaderboard.entries[0]={name:'Actualización automática QA',score:2};return route.fulfill({json:result});
      }
      await route.continue();
    });
    await page.clock.fastForward(31000);
    await page.getByText('Actualización automática QA',{exact:true}).waitFor();assert(reads>=1);
    assert.deepEqual(errors,[]);assert.deepEqual(badAssets,[]);
    console.log('PASS invitado sin registro, ruleta automática animada, premio conservado, WhatsApp sin redención, regla oculta, móvil, perfil, actualización, bienvenida de cuenta y reintento idempotente.');
  }finally{if(browser)await browser.close();for(const c of contexts)await c.dispose();}
})().catch(e=>{console.error(e);process.exitCode=1;});
