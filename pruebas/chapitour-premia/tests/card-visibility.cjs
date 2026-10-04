// Frontend visibility flow; API authorization and persistence are covered by catalog-whatsapp.php.
const {chromium}=require('playwright');
const assert=require('node:assert/strict');
const businesses=[
  {
    "id": "2",
    "name": "Capital Queer",
    "category": "Bar",
    "path": "bar/CapitalQueer/index.php",
    "image": "bar/CapitalQueer/img/logoCapitalQueer.jpg",
    "icon": "sparkles",
    "color": "pink",
    "published": true,
    "page_ready": true
  },
  {
    "id": "3",
    "name": "Jimar Factory",
    "category": "Juegos y billar",
    "path": "juegos/JimarFactory/index.php",
    "image": "juegos/JimarFactory/img/logo.jpeg",
    "icon": "target",
    "color": "cyan",
    "published": true,
    "page_ready": true
  },
  {
    "id": "4",
    "name": "Garage Disco Bar",
    "category": "Gastrobar",
    "path": "gastrobar/GarageDiscoBar/index.php",
    "image": "gastrobar/GarageDiscoBar/img/general11.jpg",
    "icon": "music",
    "color": "purple",
    "published": true,
    "page_ready": true
  },
  {
    "id": "6",
    "name": "Gran&Chela Club",
    "category": "Bar y discoteca",
    "path": "bar/Gran&Chela_Club/index.php",
    "image": "bar/Gran&Chela_Club/img/logo.jpg",
    "icon": "beer",
    "color": "yellow",
    "published": true,
    "page_ready": true
  },
  {
    "id": "8",
    "name": "Pictogramas Café Bar",
    "category": "Café bar",
    "path": "bar/Pictograma/index.php",
    "image": "bar/Pictograma/img/logo.jpeg",
    "icon": "coffee",
    "color": "cyan",
    "published": true,
    "page_ready": true
  },
  {
    "id": "11",
    "name": "Canvas Tattoo",
    "category": "Tatuajes y piercings",
    "path": "experiencias/canvas-tattoo/index.php",
    "image": "experiencias/canvas-tattoo/logo.svg",
    "icon": "store",
    "color": "purple",
    "published": true,
    "page_ready": true
  },
  {
    "id": "13",
    "name": "Gastro Bar Street Grill",
    "category": "Gastronomía",
    "path": "gastronomia/streetgrill/index.php",
    "image": "gastronomia/streetgrill/img/logo.jpeg",
    "icon": "store",
    "color": "purple",
    "published": true,
    "page_ready": true
  },
  {
    "id": "14",
    "name": "BogoPork",
    "category": "Gastronomía",
    "path": "gastronomia/bogopork/index.php",
    "image": "gastronomia/bogopork/img/logo.jpg",
    "icon": "store",
    "color": "purple",
    "published": true,
    "page_ready": true
  }
];
const origin=process.env.CHAPITOUR_QA_ORIGIN||'http://127.0.0.1:8799';
if(!/^http:\/\/127\.0\.0\.1:\d+$/.test(origin))throw new Error('Solo servidor local de pruebas.');
(async()=>{
 const browser=await chromium.launch({channel:'chrome',headless:true});
 try {
  const page=await browser.newPage();const errors=[];page.on('pageerror',e=>errors.push(e.message));
  const state={csrf:'qa',user:{id:'staff-999',name:'Admin QA',role:'admin',email:'qa@example.invalid',must_change_password:false},businesses:[],promotions:[],codes:[],challenges:[],storage:'mysql',setup_required:false,server_time:Math.floor(Date.now()/1000),campaign:{enabled:false,eligible_promotions:0,can_spin:false},google_auth:{enabled:false},leaderboard:{ready:true,entries:[],total:0,month:'2026-10-01'}};
  let failNext=false,requests=0;
  await page.route('**/api.php',async route=>{
   if(route.request().method()==='POST'){
    const data=route.request().postDataJSON();assert.equal(data.action,'set_card_visibility');assert.equal(typeof data.visible,'boolean');requests++;
    if(failNext){failNext=false;await route.fulfill({status:503,json:{error:'No se pudo guardar. Intenta de nuevo.'}});return;}
    const b=state.businesses.find(b=>b.id===data.id);assert.ok(b?.page_ready);b.published=data.visible;
   }
   await route.fulfill({json:state});
  });
  for(const base of ['/','/pruebas/chapitour-premia/']){
   state.businesses=structuredClone(businesses);
   state.businesses.push({id:'99999',name:'Negocio sin página',category:'Aliado de Chapitour',published:false,page_ready:false,path:'bar/CapitalQueer/index.php',image:'',icon:'store',color:'purple'});
   await page.setViewportSize({width:1440,height:1000});
   await page.goto(origin+base+'#fichas');
   await page.getByRole('heading',{name:'Fichas públicas',exact:true}).waitFor();
   assert.equal(await page.locator('.visibility-card').count(),9);
   assert.equal(await page.locator('.cards-count').innerText(),'8 de 9 visibles');
   const pending=page.locator('.visibility-card').filter({hasText:'Negocio sin página'});
   assert.ok(await pending.getByRole('button',{name:'Mostrar ficha'}).isDisabled());
   assert.equal(await pending.locator('a').count(),0);
   const capital=page.locator('.visibility-card').filter({has:page.getByRole('heading',{name:'Capital Queer',exact:true})});
   await capital.getByRole('button',{name:'Ocultar ficha de Capital Queer',exact:true}).click();
   await capital.getByText('Oculta',{exact:true}).waitFor();
   assert.equal(await capital.locator('a.card-preview').count(),1);
   assert.equal(await page.locator('.cards-count').innerText(),'7 de 9 visibles');
   await page.reload();await capital.getByText('Oculta',{exact:true}).waitFor();
   await page.locator('.explore-nav').click();await page.locator('.business-card').first().waitFor();
   assert.equal(await page.locator('.business-card').count(),7);
   assert.equal(await page.locator('.business-card').filter({hasText:'Capital Queer'}).count(),0);
   await page.goto(origin+base+'#fichas');await capital.getByText('Oculta',{exact:true}).waitFor();
   failNext=true;await capital.getByRole('button',{name:'Mostrar ficha de Capital Queer',exact:true}).click();
   await page.locator('#toast').filter({hasText:'No se pudo guardar'}).waitFor();
   assert.ok(await capital.getByRole('button',{name:'Mostrar ficha de Capital Queer',exact:true}).isEnabled());
   assert.equal(state.businesses.find(b=>b.name==='Capital Queer').published,false);
   await capital.getByRole('button',{name:'Mostrar ficha de Capital Queer',exact:true}).click();
   await capital.getByText('Visible',{exact:true}).waitFor();
   assert.equal(await page.locator('.cards-count').innerText(),'8 de 9 visibles');
   const label=base==='/'?'principal':'pruebas';
   await page.screenshot({path:'/private/tmp/chapitour-fichas-'+label+'-desktop.png',fullPage:true});
   for(const width of [390,320]){
    await page.setViewportSize({width,height:844});
    assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),false,'Desborde en '+width);
    if(width===390)await page.screenshot({path:'/private/tmp/chapitour-fichas-'+label+'-mobile.png',fullPage:true});
   }
   await page.goto(origin+base+'#explorar');await page.locator('.business-card').first().waitFor();
   const names=await page.locator('.business-card h3').allTextContents();assert.equal(names.length,8);assert.equal(names[0],'Canvas Tattoo');assert.equal(names[5],'Gastro Bar Street Grill');assert.equal(names[7],'Capital Queer');
   state.businesses.forEach(b=>b.published=false);await page.reload();await page.getByRole('heading',{name:'Más lugares por descubrir'}).waitFor();
   assert.equal(await page.locator('.business-card').count(),0);
   console.log('PASS '+label+': mostrar/ocultar, recarga, recuperación de error, enlace conservado, página pendiente, orden y vista vacía; 1440, 390 y 320 px.');
  }
  assert.equal(requests,6);assert.deepEqual(errors,[]);
 }finally{await browser.close();}
})().catch(e=>{console.error(e);process.exit(1)});
