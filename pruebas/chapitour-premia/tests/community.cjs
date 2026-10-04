const assert=require('node:assert/strict');
const fs=require('node:fs');
const {chromium,request}=require('playwright');
const base=process.env.CHAPITOUR_TEST_URL||'http://127.0.0.1:8794/pruebas/chapitour-premia/';
const socket=process.argv[2]||'';
if(!['127.0.0.1','localhost'].includes(new URL(base).hostname)||!/^\/private\/tmp\/chapitour-panel-qa\.[A-Za-z0-9]+\/mysql\.sock$/.test(socket))throw new Error('Solo QA local.');
const root=socket.replace('/mysql.sock',''),fixtures=JSON.parse(fs.readFileSync(root+'/community-ui.json'));
(async()=>{
  let browser;const contexts=[];
  async function actor(fixture){const http=await request.newContext();contexts.push(http);let state=await(await http.get(base+'api.php')).json();if(fixture){const r=await http.post(base+'api.php',{headers:{'X-CSRF-Token':state.csrf},data:{action:'login',email:fixture.email,password:'Qa-comunidad-456!'}});assert.equal(r.status(),200);state=await r.json();}return {http,state};}
  try{
    const first=await actor(fixtures.client),other=await actor(fixtures.other),guest=await actor();
    assert.equal((await guest.http.get(base+'avatar.php')).status(),404);
    browser=await chromium.launch({channel:'chrome',headless:true});
    const context=await browser.newContext({storageState:await first.http.storageState(),viewport:{width:1440,height:1050}});
    const page=await context.newPage(),errors=[];page.on('pageerror',e=>errors.push(e.message));
    await page.goto(base+'#perfil');await page.locator('[data-form="upload_avatar"]').waitFor();
    const image=async color=>Buffer.from(await page.evaluate(color=>{const c=document.createElement('canvas');c.width=640;c.height=360;const ctx=c.getContext('2d');ctx.fillStyle=color;ctx.fillRect(0,0,640,360);ctx.fillStyle='#fff';ctx.font='bold 55px sans-serif';ctx.fillText('PERFIL QA',130,200);return c.toDataURL('image/png').split(',')[1];},color),'base64');
    const blue=await image('#315bce'),red=await image('#c83982');
    await page.setInputFiles('#avatar-file',{name:'perfil.png',mimeType:'image/png',buffer:blue});
    await page.locator('#profile-photo-preview img').waitFor();
    await page.locator('[data-form="upload_avatar"] button[type="submit"]').click();
    await page.locator('.profile-photo img[src^="avatar.php"]').waitFor();
    const originalUrl=await page.locator('.profile-photo img').getAttribute('src');
    const download=await first.http.get(base+originalUrl);assert.equal(download.status(),200);assert.equal(download.headers()['content-type'],'image/jpeg');assert(download.headers()['cache-control'].includes('no-store'));const originalBytes=await download.body();assert(originalBytes.length<300000);
    await page.reload();await page.locator('.profile-photo img').waitFor();assert.equal(await page.locator('.profile-photo img').evaluate(img=>img.naturalWidth),512);
    await page.fill('[name="name"]','Chapi <b>QA</b>');await page.check('[name="ranking_visible"]');await page.locator('[data-form="profile"] button[type="submit"]').click();
    await page.getByText('Cambios guardados.',{exact:true}).waitFor();
    const fresh=await(await guest.http.get(base+'api.php')).json();assert(fresh.leaderboard.entries.every(e=>Object.keys(e).join(',')===(e.demo?'name,score,demo':'name,score')));
    assert(!JSON.stringify(fresh).includes(fixtures.client.email));assert(!JSON.stringify(fresh).includes('photo_url'));
    const publicPage=await browser.newPage({viewport:{width:1440,height:1100}});await publicPage.goto(base+'#comunidad');await publicPage.locator('#comunidad .ranking-table').waitFor();assert(await publicPage.locator('#explorar').evaluate(el=>el.nextElementSibling?.id==='comunidad'));
    assert.equal(await publicPage.locator('#comunidad .ranking-table img').count(),0);assert.equal(await publicPage.locator('#comunidad .ranking-table b').count(),0);assert((await publicPage.locator('#comunidad .ranking-table').innerText()).includes('Chapi <b>QA</b>'));
    await publicPage.locator('#comunidad').screenshot({path:root+'/ranking-desktop.png'});
    await publicPage.locator('[data-action="ranking-page"]').click();await publicPage.locator('.ranking-pagination').waitFor();await publicPage.locator('[data-action="ranking-page"][data-page="2"]').click();await publicPage.getByText('Página 2',{exact:true}).waitFor();await publicPage.keyboard.press('Escape');
    for(const width of [390,320]){await page.setViewportSize({width,height:900});assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));await page.screenshot({path:root+`/perfil-${width}.png`,fullPage:true});await publicPage.setViewportSize({width,height:900});assert(await publicPage.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));await publicPage.locator('#comunidad').screenshot({path:root+`/ranking-${width}.png`});}
    const malicious=Buffer.concat([red,Buffer.from('<?php echo "never execute"; ?>')]);
    const upload=(actor,buffer,mimeType='image/png',extra={})=>actor.http.post(base+'api.php',{headers:{'X-CSRF-Token':actor.state.csrf},multipart:{action:'upload_avatar',...extra,avatar:{name:'untrusted.php',mimeType,buffer}}});
    assert.equal((await upload(guest,red)).status(),403);
    assert.equal((await first.http.post(base+'api.php',{multipart:{action:'upload_avatar',avatar:{name:'x.png',mimeType:'image/png',buffer:red}}})).status(),403);
    const unsafe=await upload(other,malicious,'image/png',{client_id:String(fixtures.client.id)});assert.equal(unsafe.status(),200);
    const otherPhoto=await other.http.get(base+'avatar.php');const otherBytes=await otherPhoto.body();assert(!otherBytes.includes(Buffer.from('<?php')));assert.notDeepEqual(otherBytes,originalBytes);assert.deepEqual(await(await first.http.get(base+'avatar.php?id='+fixtures.other.id)).body(),originalBytes);
    const svg=Buffer.from('<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');assert.equal((await upload(first,svg)).status(),422);assert.equal((await upload(first,Buffer.alloc(5*1024*1024+9000,1))).status(),413);
    assert.deepEqual(await(await first.http.get(base+'avatar.php')).body(),originalBytes);
    await page.setInputFiles('#avatar-file',{name:'nueva.png',mimeType:'image/png',buffer:red});await page.locator('[data-form="upload_avatar"] button[type="submit"]').click();await page.waitForFunction(old=>{const src=document.querySelector('.profile-photo img')?.getAttribute('src');return src?.startsWith('avatar.php')&&src!==old;},originalUrl);assert.notEqual(await page.locator('.profile-photo img').getAttribute('src'),originalUrl);
    await page.locator('[data-action="remove-avatar"]').click();await page.waitForFunction(()=>!document.querySelector('.profile-photo img'));assert.equal((await first.http.get(base+originalUrl)).status(),404);assert.equal((await other.http.get(base+'avatar.php')).status(),200);
    await page.uncheck('[name="ranking_visible"]');await page.locator('[data-form="profile"] button[type="submit"]').click();await page.getByText('Cambios guardados.',{exact:true}).waitFor();
    const hidden=await(await guest.http.get(base+'api.php')).json();assert(!hidden.leaderboard.entries.some(e=>e.name==='Chapi <b>QA</b>'));
    assert.deepEqual(errors,[]);
    console.log('PASS Chrome: ranking público sin datos privados, paginación, nombre escapado, perfil móvil 320/390px, subir/cambiar/quitar foto, JPEG 512px, persistencia, CSRF, aislamiento y rechazo de archivos inválidos.');
  } finally {if(browser)await browser.close();for(const c of contexts)await c.dispose();}
})().catch(e=>{console.error(e);process.exitCode=1;});
