import { Ruleta } from './ruleta.js';

// Solo una vista de prueba: no emite premios ni registra giros o referidos.
const aliados = [
  { id: 1, nombre: 'Street Grill' },
  { id: 2, nombre: 'Capital Queer' },
  { id: 3, nombre: 'Jimar Factory' },
  { id: 4, nombre: 'Garage Disco Bar' },
  { id: 5, nombre: 'Pictogramas Café Bar' },
  { id: 6, nombre: 'Gran&Chela Club' },
];

export function iniciarDemoRuleta(original, launcher) {
  if (!launcher) return;

  // Reutiliza el diseño antes de que el controlador real cambie su estado.
  const dialog = original.cloneNode(true);
  dialog.id = 'chapi-promo-demo';
  dialog.removeAttribute('open');
  dialog.querySelectorAll('[data-promo-screen]:not([data-promo-screen="wheel"]), .chapi-promo__error, .chapi-promo__sr-only').forEach(node => node.remove());
  const ids = new Map();
  dialog.querySelectorAll('[id]').forEach(node => {
    const oldId = node.id;
    node.id = oldId.replace('chapi-promo-', 'chapi-demo-');
    ids.set(oldId, node.id);
  });
  for (const node of [dialog, ...dialog.querySelectorAll('[aria-labelledby], [aria-describedby], [for]')]) {
    for (const attr of ['aria-labelledby', 'aria-describedby', 'for']) {
      if (node.hasAttribute(attr)) node.setAttribute(attr, node.getAttribute(attr).split(/\s+/).map(id => ids.get(id) || id).join(' '));
    }
  }
  const $ = selector => dialog.querySelector(selector);
  $('[data-promo-screen="wheel"]').hidden = false;
  $('.chapi-promo__preview').textContent = 'Demostración';
  $('.chapi-promo__intro > .chapi-promo__description').textContent = 'Prueba cómo se ve y gira la ruleta con los seis aliados de Chapinero.';
  $('#chapi-demo-available').textContent = 'Giros de prueba ilimitados';
  $('#chapi-demo-duration').textContent = 'Sin premios reales';
  $('#chapi-demo-wheel-caption').textContent = '6 aliados. Una vista de prueba.';
  $('.chapi-promo__sticker').textContent = '¡PRUÉBALA!';
  $('.chapi-promo__footer p').textContent = 'Demostración visual · No genera códigos ni consume oportunidades';
  const steps = $('.chapi-promo__steps').querySelectorAll('li');
  ['01 · Simula un giro', '02 · Mira el resultado', '03 · Prueba otra vez'].forEach((text, i) => { steps[i].textContent = text; });

  const spin = $('#chapi-demo-spin');
  const label = spin.firstElementChild;
  const status = $('#chapi-demo-spin-status');
  label.textContent = 'Simular giro';
  spin.disabled = false;
  status.textContent = 'Puedes probar todas las veces que quieras.';
  status.setAttribute('aria-atomic', 'true');
  const wheel = new Ruleta($('#chapi-demo-wheel'));
  wheel.render(aliados);
  document.body.append(dialog);
  let spinning = false;

  launcher.addEventListener('click', () => {
    if (!dialog.open) dialog.showModal();
    document.documentElement.classList.add('chapi-promo-demo-open');
    $('#chapi-demo-title').focus({ preventScroll: true });
  });
  dialog.querySelectorAll('[data-promo-close]').forEach(button => button.addEventListener('click', () => dialog.close()));
  dialog.addEventListener('close', () => {
    document.documentElement.classList.remove('chapi-promo-demo-open');
    launcher.focus({ preventScroll: true });
  });
  dialog.addEventListener('click', event => {
    if (event.target !== dialog) return;
    const r = dialog.getBoundingClientRect();
    if (event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom) dialog.close();
  });
  spin.addEventListener('click', async () => {
    if (spinning) return;
    spinning = true;
    spin.disabled = true;
    spin.setAttribute('aria-busy', 'true');
    label.textContent = 'Girando…';
    status.textContent = 'Simulando un giro de prueba.';
    try {
      const aliado = aliados[Math.floor(Math.random() * aliados.length)];
      await wheel.animate(aliado.id);
      status.textContent = `Resultado de prueba: ${aliado.nombre}. No es un premio canjeable.`;
    } catch {
      status.textContent = 'No pudimos mostrar el giro. Puedes intentarlo de nuevo.';
    } finally {
      spinning = false;
      spin.disabled = false;
      spin.setAttribute('aria-busy', 'false');
      label.textContent = 'Volver a probar';
    }
  });
  launcher.hidden = false;
}
