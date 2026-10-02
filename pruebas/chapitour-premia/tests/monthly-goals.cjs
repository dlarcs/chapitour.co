const assert=require('node:assert/strict');
const fs=require('node:fs');
const {chromium,request}=require('playwright');
const base=process.env.CHAPITOUR_TEST_URL||'http://127.0.0.1:8794/pruebas/chapitour-premia/';
const socket=process.argv[2]||'';
if(!['127.0.0.1','localhost'].includes(new URL(base).hostname)||!/^\/private\/tmp\/chapitour-panel-qa\.[A-Za-z0-9]+\/mysql\.sock$/.test(socket))throw new Error('Solo QA local.');
const root=socket.replace('/mysql.sock',''),fixture=JSON.parse(fs.readFileSync(root+'/monthly-goals-ui.json'));
(async()=>{
  const clients=[];let browser;
  async function login(){const http=await request.newContext();clients.push(http);const guest=await(await http.get(base+'api.php')).json();const r=await http.post(base+'api.php',{headers:{'X-CSRF-Token':guest.csrf},data:{action:'login',email:fixture.email,password:'Qa-metas-456!'}});assert.equal(r.status(),200);return {http,state:await r.json()};}
  try {
    const first=await login(),second=await login();
    browser=await chromium.launch({channel:'chrome',headless:true});
    const context=await browser.newContext({storageState:await first.http.storageState(),viewport:{width:1440,height:1100}});
    const page=await context.newPage(),errors=[];page.on('pageerror',e=>errors.push(e.message));
    await page.addInitScript(()=>{Object.defineProperty(navigator,'share',{value:async()=>{},configurable:true});});
    await page.goto(base+'#retos');await page.locator('[data-goal="fotografia"]').waitFor();
    assert.equal(await page.locator('.monthly-goals>.challenge').count(),3);
    assert((await page.locator('[data-goal="fotografia"] .progress-label').innerText()).includes('0 / 3'));
    assert((await page.locator('[data-goal="compartir"]').innerText()).includes('otro giro'));
    const before=await page.locator('[data-goal="compartir"] .progress-label').innerText();await page.locator('[data-action="share"]').click();assert.equal(await page.locator('[data-goal="compartir"] .progress-label').innerText(),before);
    await page.screenshot({path:root+'/metas-desktop.png',fullPage:true});
    await page.locator('[data-action="photo-progress"]').click();
    assert.equal(await page.locator('dialog input[type="file"]').count(),0);
    assert.equal(await page.locator('dialog select').count(),1);
    const photo=first.state.challenges.find(c=>c.type==='fotografia');
    await page.selectOption('#photo-place',photo.places[0].id);await page.locator('#photo-mentions').filter({hasText:'@chapitour.co'}).waitFor();
    await page.locator('[data-form="record_photo"] button[type="submit"]').click();await page.locator('.goal-total>strong').filter({hasText:'1/3'}).waitFor();
    await page.reload();await page.locator('[data-goal="fotografia"] .progress-label').filter({hasText:'1 / 3'}).waitFor();
    const calls=photo.places.map((p,i)=>{const c=i%2?first:second;return c.http.post(base+'api.php',{headers:{'X-CSRF-Token':c.state.csrf},data:{action:'record_photo',month:photo.month,business_id:p.id}});});
    const race=await Promise.all(calls);assert(race.every(r=>r.status()===200));
    const fresh=await(await first.http.get(base+'api.php')).json();assert.equal(fresh.challenges.find(c=>c.type==='fotografia').progress,3);
    await page.reload();await page.locator('[data-action="photo-progress"]').click();await page.locator('.goal-total>strong').filter({hasText:'3/3'}).waitFor();assert.equal(await page.locator('[data-form="record_photo"]').count(),0);await page.keyboard.press('Escape');
    await page.locator('[data-action="question-places"]').click();
    const text=await page.locator('dialog').innerText();for(const name of ['Capital Queer','Gran&Chela','Garage','Pictogramas','Street Grill'])assert(!text.includes(name));
    await page.locator('[data-action="answer-place"][data-id="r4"]').click();
    await page.selectOption('select[name="business_id"]',photo.places[0].id);
    for(const [name,value] of Object.entries({pais_1:'Perú',pais_2:'Perú',pais_3:'Chile'}))await page.fill(`[name="answer_${name}"]`,value);
    await page.locator('[data-form="answer_questions"] button[type="submit"]').click();await page.locator('#modal-error').filter({hasText:'tres países diferentes'}).waitFor();
    await page.fill('[name="answer_pais_2"]','Colombia');await page.locator('[data-form="answer_questions"] button[type="submit"]').click();await page.locator('.goal-total>strong').filter({hasText:'1/5'}).waitFor();
    assert(await page.locator('[data-action="answer-place"][data-id="r4"]').isDisabled());
    for(const width of [390,320]){await page.setViewportSize({width,height:844});assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));await page.screenshot({path:root+`/preguntas-${width}.png`,fullPage:true});}
    await page.keyboard.press('Escape');await page.screenshot({path:root+'/metas-mobile.png',fullPage:true});
    assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));assert.deepEqual(errors,[]);
    console.log('PASS Chrome escritorio/móvil: tres metas, compartir sin incrementos, solo selector de fotos, persistencia, límite 3/3 concurrente, preguntas anónimas, respuestas abiertas y sin desbordes.');
  } finally {if(browser)await browser.close();for(const c of clients)await c.dispose();}
})().catch(e=>{console.error(e);process.exitCode=1;});
