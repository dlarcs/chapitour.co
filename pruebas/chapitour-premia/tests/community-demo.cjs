const assert=require('node:assert/strict');
const fs=require('node:fs');
const {chromium,request}=require('playwright');
const base=process.env.CHAPITOUR_TEST_URL||'http://127.0.0.1:8794/pruebas/chapitour-premia/';
const socket=process.argv[2]||'';
if(!['127.0.0.1','localhost'].includes(new URL(base).hostname)||!/^\/private\/tmp\/chapitour-panel-qa\.[A-Za-z0-9]+\/mysql\.sock$/.test(socket))throw new Error('Solo QA local.');
const root=socket.replace('/mysql.sock','');
const fixtures=JSON.parse(fs.readFileSync(root+'/community-demo-ui.json'));
(async()=>{
  const http=await request.newContext();let browser;
  try {
    const response=await http.get(base+'api.php');assert.equal(response.status(),200);
    const state=await response.json();assert(!state.user);
    browser=await chromium.launch({channel:'chrome',headless:true});
    const page=await browser.newPage({viewport:{width:1440,height:1100}}),errors=[];
    page.on('pageerror',e=>errors.push(e.message));
    let realCount=0;
    // Render actual PHP-produced snapshots without changing shared QA data for the browser.
    await page.route('**/api.php',route=>{
      const input=route.request().postDataJSON()||{},fixture=fixtures[realCount];
      return route.fulfill({json:{...state,leaderboard:fixture.top,...(input.action==='leaderboard'?{ranking_page:Number(input.page)===2?fixture.next:fixture.full}:{})}});
    });
    for(const count of [0,5,19,20,21]) {
      realCount=count;
      await page.goto(base+'?qa-community='+count+'#comunidad');await page.locator('#comunidad .ranking-table').waitFor();
      assert.equal(await page.locator('#comunidad .ranking-table tbody tr').count(),10);
      assert.equal(await page.locator('#comunidad .ranking-demo-tag').count(),Math.max(0,10-count));
      assert.equal(await page.locator('#comunidad .ranking-demo-notice').count(),count<20?1:0);
      if(count>0)assert((await page.locator('#comunidad .ranking-table tbody tr').first().innerText()).includes('Participante QA'));
      await page.locator('#comunidad [data-action="ranking-page"]').click();
      await page.locator('.ranking-pagination').waitFor();
      const dialog=page.getByRole('dialog');
      assert.equal(await dialog.locator('.ranking-table tbody tr').count(),20);
      assert.equal(await dialog.locator('.ranking-demo-tag').count(),Math.max(0,20-count));
      assert.equal(await dialog.locator('.ranking-demo-notice').count(),count<20?1:0);
      const next=dialog.getByRole('button',{name:'Siguiente',exact:true});
      assert.equal(await next.isEnabled(),count>20);
      if(count>20) { await next.click();await page.getByText('Página 2',{exact:true}).waitFor();assert.equal(await dialog.locator('.ranking-table tbody tr').count(),1);assert.equal(await dialog.locator('.ranking-demo-tag').count(),0); }
      await page.keyboard.press('Escape');
    }
    realCount=0;await page.goto(base+'#comunidad');await page.locator('#comunidad .ranking-table').waitFor();
    for(const width of [1440,390,320]) {
      await page.setViewportSize({width,height:1100});
      assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
      await page.locator('#comunidad').screenshot({path:root+`/ranking-ejemplos-${width}.png`});
      await page.locator('#comunidad [data-action="ranking-page"]').click();await page.locator('.ranking-pagination').waitFor();
      assert(await page.getByRole('dialog').evaluate(el=>el.scrollWidth<=el.clientWidth+1));
      assert.equal(await page.getByRole('dialog').getByText('Perfil ficticio',{exact:true}).count(),20);
      await page.keyboard.press('Escape');
    }
    assert.deepEqual(errors,[]);
    console.log('PASS Chrome: avisos y etiquetas de ejemplo, prioridad real, sustitución 0/5/19/20/21, lista de 20, paginación y escritorio/móvil 390/320px.');
  } finally { if(browser)await browser.close();await http.dispose(); }
})().catch(e=>{console.error(e);process.exitCode=1;});
