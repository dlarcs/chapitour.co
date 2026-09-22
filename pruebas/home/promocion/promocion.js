import { PromocionControl } from './promocion.control.js';
import { iniciarDemoRuleta } from './promocion.demo.js';
const dialog = document.getElementById('chapi-promo');
if (dialog && typeof dialog.showModal === 'function') {
  iniciarDemoRuleta(dialog, document.getElementById('chapi-promo-demo-launcher'));
  new PromocionControl(dialog).iniciar();
}
