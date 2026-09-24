// Este botón repite el recorrido real usando únicamente la base de pruebas.
export function iniciarDemoRuleta(control, launcher) {
  if (!launcher) return;
  launcher.addEventListener('click', () => control.probar());
  launcher.hidden = false;
}
