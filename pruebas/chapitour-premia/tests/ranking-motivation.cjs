const assert = require('node:assert/strict');
const fs = require('node:fs');
const { chromium } = require('playwright');
const base = process.env.CHAPITOUR_TEST_URL || 'http://127.0.0.1:8798/';
if (!['127.0.0.1', 'localhost'].includes(new URL(base).hostname)) throw Error('Solo QA local.');
const output = fs.mkdtempSync('/private/tmp/chapitour-ranking-motivation-');

function fixture() {
  const places = [1, 2, 3].map(id => ({ id: String(id), name: `Aliado QA ${id}`, instagram: 'aliado_qa', complete: id < 3 }));
  return {
    csrf: 'qa', server_time: Math.floor(Date.now() / 1000), codes: [], promotions: [], google_auth: { enabled: false },
    campaign: { can_spin: false },
    businesses: [{ id: '3', name: 'Aliado QA', published: true, path: '1.1.bartin/index.php', image: 'home/img/bar2.png', color: 'pink', icon: 'store', category: 'Gastronomía' }],
    leaderboard: { ready: true, month: '2026-10-01', entries: [{ name: 'Camila Pérez', score: 50 }, { name: 'Ana María López', score: 40 }], total: 2 },
    user: { id: 'client-qa', role: 'client', name: 'Ana María López', email: 'qa@example.invalid', city: 'Bogotá', created_at: 1790812800,
      community: { ready: true, score: 40, position: 2, points_to_climb: 10, visible: true, breakdown: { photos: 30, questions: 5, visits: 5, sharing: 0 } } },
    challenges: [
      { type: 'fotografia', ready: true, progress: 2, target: 3, month: '2026-10-01', places },
      { type: 'preguntas', ready: true, progress: 1, target: 5, month: '2026-10-01', version: 1, locations: places,
        places: [1, 2, 3, 4, 5].map(id => ({ id: `r${id}`, name: `Pregunta ${id}`, complete: id === 1, questions: [{ id: 'respuesta', label: '¿Qué descubriste?' }] })) }
    ]
  };
}

(async () => {
  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  try {
    for (const path of ['', 'pruebas/chapitour-premia/']) {
      const page = await browser.newPage({ viewport: { width: 1440, height: 1000 } });
      const errors = [], actions = [];
      page.on('pageerror', error => errors.push(error.message));
      let state = fixture();
      await page.route('**/api.php', route => {
        const input = route.request().postDataJSON() || {};
        actions.push(input.action || 'state');
        if (input.action === 'record_photo') {
          state.challenges[0].progress = 3;
          state.challenges[0].places.forEach(place => { place.complete = true; });
          state.user.community.breakdown.photos = 45;
          state.user.community.score = 55;
          state.user.community.position = 1;
          state.user.community.points_to_climb = null;
        }
        return route.fulfill({ json: state });
      });
      let revision = 0;
      const load = async update => {
        state = fixture();
        update?.(state);
        await page.goto(base + path + '?qa=motivation-' + (++revision) + '#comunidad');
        await page.locator('#comunidad .ranking-card').waitFor();
      };
      const card = page.locator('.ranking-motivation');
      await load();
      assert((await card.innerText()).includes('10 puntos para alcanzar la siguiente posición'));
      assert.equal(await card.locator('.ranking-points').innerText(), '+15 puntos');
      assert(await page.locator('#explorar').evaluate(el => el.nextElementSibling.id === 'comunidad'));
      for (const width of [1440, 390, 320]) {
        await page.setViewportSize({ width, height: 1000 });
        assert(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1));
        await card.screenshot({ path: `${output}/${path ? 'legacy' : 'main'}-${width}.png` });
      }
      await card.getByRole('button', { name: 'Registrar mi lugar' }).click();
      await page.locator('#photo-place').selectOption('3');
      await page.locator('[data-form="record_photo"] button[type="submit"]').click();
      await page.getByText('Lugar registrado. Cada negocio suma una sola vez.', { exact: true }).waitFor();
      await page.keyboard.press('Escape');
      assert.equal(await card.locator('.ranking-points').innerText(), '+5 puntos');
      assert((await card.innerText()).includes('¡Vas en primer lugar!'));
      assert(!(await card.innerText()).includes('Te faltan'));
      await card.getByRole('button', { name: 'Responder una pregunta' }).click();
      await page.getByRole('heading', { name: 'Preguntas y pistas', exact: true }).waitFor();
      await page.keyboard.press('Escape');

      await load(s => { s.user.community.points_to_climb = 1; });
      assert((await card.innerText()).includes('Te falta 1 punto'));
      await load(s => { s.user.community.visible = false; s.user.community.position = null; });
      assert((await card.innerText()).includes('Tu nombre está oculto'));
      assert(!(await card.innerText()).includes('Te faltan'));
      assert.equal(await card.getByRole('link', { name: 'Mostrarme desde Mi perfil' }).getAttribute('href'), '#perfil');
      await load(s => { s.user.community.ready = false; });
      assert((await card.innerText()).includes('Tus metas se están preparando'));
      assert.equal(await card.locator('.ranking-points, .ranking-progress').count(), 0);

      const finish = s => {
        s.challenges.forEach(challenge => { challenge.progress = challenge.target; challenge.places.forEach(place => { place.complete = true; }); });
        s.user.community.breakdown.photos = 45;
        s.user.community.breakdown.questions = 25;
      };
      await load(finish);
      assert((await card.innerText()).includes('Tus próximas visitas válidas'));
      assert.equal(await card.locator('.ranking-points').count(), 0);
      await load(s => { finish(s); s.user.community.breakdown.visits = 10; });
      assert((await card.innerText()).includes('¡Completaste tus metas de fotos, preguntas y visitas!'));
      assert.equal(await card.locator('.ranking-points').count(), 0);
      await load(s => { s.challenges[0].places = []; });
      assert.equal(await card.locator('.ranking-points').innerText(), '+5 puntos');
      await load(s => { s.user.community.breakdown.photos = 45; });
      assert.equal(await card.locator('.ranking-points').innerText(), '+5 puntos');

      await load(s => { s.user = null; s.challenges = []; });
      assert.equal(await card.locator('.ranking-progress').count(), 0);
      await card.getByRole('button', { name: 'Quiero ganarme el tour' }).click();
      await page.getByRole('heading', { name: 'Crear mi cuenta con Google', exact: true }).waitFor();
      await page.keyboard.press('Escape');
      await load(s => { s.user.role = 'ally'; });
      assert.equal(await card.count(), 0);
      assert(actions.includes('record_photo'));
      assert.deepEqual(errors, []);
      await page.close();
    }
    console.log('PASS Chrome: ambas portadas, sugerencia foto/pregunta, avance tras registrar, límites, primer lugar, privacidad, invitado, metas pendientes y móvil 390/320px. Capturas: ' + output);
  } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exitCode = 1; });
