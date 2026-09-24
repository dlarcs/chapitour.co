import { PromocionControl } from './promocion.control.js';
import { iniciarDemoRuleta } from './promocion.demo.js';
const dialog = document.getElementById('chapi-promo');
if (dialog && typeof dialog.showModal === 'function') {
  const control = new PromocionControl(dialog);
  iniciarDemoRuleta(control, document.getElementById('chapi-promo-demo-launcher'));
  control.iniciar();
}
