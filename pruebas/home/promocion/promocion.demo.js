// Este botón repite el recorrido real usando únicamente la base de pruebas.
export function iniciarDemoRuleta(control, launcher) {
  if (!launcher) return;
  launcher.addEventListener('click', () => control.probar());
  document.querySelectorAll('[data-promo-test]').forEach(link => link.addEventListener('click', event => {
    event.preventDefault();
    const menu = document.querySelector('[aria-controls="chapitour-menu"]');
    if (menu && menu.getAttribute('aria-expanded') === 'true') menu.click();
    control.probar();
  }));
  launcher.hidden = false;
}
