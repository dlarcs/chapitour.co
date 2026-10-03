const assert=require('node:assert/strict');
const fs=require('node:fs');
const {chromium,request}=require('playwright');
const base=process.env.CHAPITOUR_TEST_URL||'http://127.0.0.1:8798/';
const socket=process.argv[2]||'';
if(!['127.0.0.1','localhost'].includes(new URL(base).hostname)||!/^\/private\/tmp\/chapitour-panel-qa\.[A-Za-z0-9]+\/mysql\.sock$/.test(socket))throw new Error('Solo QA local.');
const root=socket.replace('/mysql.sock','');
const fixtures=JSON.parse(fs.readFileSync(root+'/community-demo-ui.json'));
(async()=>{
  const http=await request.newContext();let browser;
  try {
    const response=await http.get(base+'premia/api.php');assert.equal(response.status(),200);
    const state=await response.json();assert(!state.user);
    browser=await chromium.launch({channel:'chrome',headless:true});
    const page=await browser.newPage({viewport:{width:1440,height:1100}}),errors=[];
    page.on('pageerror',e=>errors.push(e.message));
    let realCount=0;
    // Render actual PHP-produced snapshots without changing shared QA data for the browser.
    await page.route('**/api.php',route=>{
      const input=route.request().postDataJSON()||{},fixture=fixtures[realCount];
      return route.fulfill({json:{...state,campaign:{...state.campaign,can_spin:false},leaderboard:fixture.top,...(input.action==='leaderboard'?{ranking_page:Number(input.page)===2?fixture.next:fixture.full}:{})}});
    });
    for(const count of [0,5,19,20,21]) {
      realCount=count;
      await page.goto(base+'?qa-community='+count+'#comunidad');await page.locator('#comunidad .ranking-card').waitFor();
      assert.equal(await page.locator('#comunidad .ranking-table .ranking-entry').count(),Math.min(10,count));
      assert.equal(await page.locator('#comunidad .ranking-examples-heading').count(),0);
      assert.equal(await page.locator('#comunidad .ranking-demo-notice').count(),0);
      if(count>0)assert((await page.locator('#comunidad .ranking-table .ranking-entry').first().innerText()).includes('Participante QA'));
      if(count===0){assert.equal(await page.locator('#comunidad .ranking-empty').count(),1);assert.equal(await page.locator('#comunidad [data-action="ranking-page"]').count(),0);continue;}
      await page.locator('#comunidad [data-action="ranking-page"]').click();
      await page.locator('.ranking-pagination').waitFor();
      const dialog=page.getByRole('dialog');
      assert.equal(await dialog.locator('.ranking-table .ranking-entry').count(),Math.min(20,count));
      assert.equal(await dialog.locator('.ranking-examples-heading').count(),0);
      assert.equal(await dialog.locator('.ranking-demo-notice').count(),0);
      const next=dialog.getByRole('button',{name:'Siguiente',exact:true});
      assert.equal(await next.isEnabled(),count>20);
      if(count>20) { await next.click();await page.getByText('Página 2',{exact:true}).waitFor();assert.equal(await dialog.locator('.ranking-table .ranking-entry').count(),1);assert.equal(await dialog.locator('.ranking-examples-heading').count(),0); }
      await page.keyboard.press('Escape');
    }
    realCount=5;await page.goto(base+'#comunidad');await page.locator('#comunidad .ranking-card').waitFor();
    for(const width of [1440,390,320]) {
      await page.setViewportSize({width,height:1100});
      assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
      await page.locator('#comunidad').screenshot({path:root+`/ranking-real-${width}.png`});
      await page.locator('#comunidad [data-action="ranking-page"]').click();await page.locator('.ranking-pagination').waitFor();
      assert(await page.getByRole('dialog').evaluate(el=>el.scrollWidth<=el.clientWidth+1));
      assert.equal(await page.getByRole('dialog').getByText('Perfil ficticio',{exact:true}).count(),0);
      await page.keyboard.press('Escape');
    }
    assert.deepEqual(errors,[]);
    console.log('PASS Chrome: solo registros reales, estado vacío, conteos 0/5/19/20/21, sin avisos ni perfiles de ejemplo, paginación y móvil 390/320px.');
  } finally { if(browser)await browser.close();await http.dispose(); }
})().catch(e=>{console.error(e);process.exitCode=1;});
