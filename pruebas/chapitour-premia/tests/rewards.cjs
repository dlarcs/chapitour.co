const assert=require('node:assert/strict');
const {execFileSync}=require('node:child_process');
const {chromium,request}=require('playwright');
const base='http://127.0.0.1:8792/pruebas/chapitour-premia/';
const socket=process.argv[2];
if(!/^\/private\/tmp\/chapitour-panel-qa\.[A-Za-z0-9]+\/mysql\.sock$/.test(socket||''))throw Error('Only isolated QA');
const fixture=mode=>process.stdout.write(execFileSync('/Applications/XAMPP/xamppfiles/bin/php',[__dirname+'/rewards-fixtures.php',mode,socket]));
(async()=>{
  fixture('clock');fixture('seed');
  const contexts=[];
  async function actor(email){
    const http=await request.newContext();contexts.push(http);
    let state=await(await http.get(base+'api.php')).json();
    const a={http,get state(){return state;},async call(action,data={},expected=200){
      const r=await http.post(base+'api.php',{headers:{'X-CSRF-Token':state.csrf},data:{action,...data}});const b=await r.json();
      assert.equal(r.status(),expected,JSON.stringify(b));if(r.ok())state=b;return b;
    },async refresh(){state=await(await http.get(base+'api.php')).json();return state;}};
    if(email)await a.call('login',{email,password:email==='laurazoro@gmail.com'?'Qa-admin-new-456!':'Qa-ruleta-456!'});return a;
  }
  const admin=await actor('laurazoro@gmail.com'),guest=await actor();
  await guest.call('visit',{},403);await admin.call('visit',{},403);await admin.call('spin',{ticket_id:'1'},403);
  const client=await actor('ruleta-400@example.invalid'),second=await actor('ruleta-400@example.invalid');
  await Promise.all([client.call('visit',{timestamp:9999999999,visits:999}),second.call('visit')]);
  assert.equal(client.state.campaign.ticket_id,second.state.campaign.ticket_id);
  const ticket=client.state.campaign.ticket_id;assert(ticket);assert.equal(client.state.campaign.can_spin,false);
  await client.call('spin',{ticket_id:ticket},422);
  const p=admin.state.promotions.find(p=>p.business_id==='1');
  await admin.call('save_promotion',{...p,description:'Oferta de prueba aislada',publication:'approved',whatsapp:'10000000',included:'Servicio QA',hours:'Horario QA',restrictions:'Solo QA',confirmed:true});
  await client.refresh();assert.equal(client.state.campaign.can_spin,true);
  assert.deepEqual(client.state.campaign.wheel_business_ids,['1']);
  assert.equal(client.state.campaign.visits_per_reward,8);assert.equal(client.state.campaign.new_visit_after,14400);assert.equal(client.state.campaign.monthly_visit_reset,true);
  const browser=await chromium.launch({headless:true,executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',args:['--no-sandbox']});
  let lostCode;
  try {
    const page=await browser.newPage({viewport:{width:1280,height:960}});const errors=[];page.on('pageerror',e=>errors.push(e.message));
    await page.goto(base);await page.getByRole('button',{name:'Iniciar sesión',exact:true}).first().click();
    await page.locator('input[name="email"]').fill('ruleta-400@example.invalid');await page.locator('input[name="password"]').fill('Qa-ruleta-456!');
    await page.locator('dialog button[type="submit"]').click();await page.locator('.reward-ready').waitFor();
    assert(!/8|4|7 de/.test(await page.locator('.return-card').innerText()));
    await page.locator('.reward-ready [data-action="preview-wheel"]').click();
    assert(!(await page.locator('[data-action="spin"]').isDisabled()));
    let lose=true;
    await page.route('**/api.php',async route=>{
      if(lose&&route.request().method()==='POST'&&route.request().postDataJSON().action==='spin'){
        lose=false;const response=await route.fetch();const data=await response.json();lostCode=data.won_code;assert(lostCode);await route.abort('failed');
      }else await route.continue();
    });
    await page.locator('[data-action="spin"]').click();await page.locator('#modal-error').filter({hasText:/./}).waitFor();
    await page.locator('[data-action="spin"]').click();await page.waitForFunction(()=>document.querySelector('dialog').getAttribute('aria-busy')==='true');
    await page.keyboard.press('Escape');assert(await page.locator('dialog').evaluate(el=>el.open));
    await page.getByRole('heading',{name:'¡Te ganaste una promoción especial!'}).waitFor();
    assert((await page.locator('dialog').innerText()).includes(lostCode));
    await page.screenshot({path:'/private/tmp/chapitour-panels-qa/ruleta-premio-desktop.png',fullPage:true});
    await page.setViewportSize({width:390,height:844});await page.screenshot({path:'/private/tmp/chapitour-panels-qa/ruleta-premio-mobile.png',fullPage:true});
    assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
    assert.deepEqual(errors,[]);
    console.log('PASS rueda animada, premio correcto, bloqueo durante giro, recuperacion de respuesta perdida, vistas desktop y movil');
  } finally {await browser.close();}
  const retry=await Promise.all([client.call('spin',{ticket_id:ticket}),second.call('spin',{ticket_id:ticket})]);
  assert(retry.every(r=>r.won_code===lostCode));assert.equal(client.state.campaign.can_spin,false);
  await client.call('visit');assert.equal(client.state.campaign.ticket_id,null);
  const won=client.state.codes.find(c=>c.code===lostCode);assert.equal(won.expires_at-won.created_at,72*3600);
  const wa=await client.call('whatsapp',{code:lostCode});assert(new URL(wa.whatsapp_url).searchParams.get('text').includes(lostCode));assert.equal(client.state.codes.find(c=>c.code===lostCode).status,'Activo');
  const one=await actor('ruleta-401@example.invalid'),two=await actor('ruleta-402@example.invalid');
  await one.call('visit');await two.call('visit');
  await one.call('spin',{ticket_id:ticket},403);
  fixture('cap');
  const raced=await Promise.all([one,two].map(a=>a.http.post(base+'api.php',{headers:{'X-CSRF-Token':a.state.csrf},data:{action:'spin',ticket_id:a.state.campaign.ticket_id}})));
  assert.deepEqual(raced.map(r=>r.status()).sort(),[200,422],JSON.stringify(await Promise.all(raced.map(r=>r.json()))));
  fixture('verify');
  for(const c of contexts)await c.dispose();
  console.log('PASS permisos, visitas concurrentes, una emision por ciclo, reintentos, propiedad del giro, WhatsApp y carrera por ultimo cupo');
})().catch(e=>{console.error(e);process.exitCode=1;});
