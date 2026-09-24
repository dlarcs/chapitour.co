import { PromocionControl } from './promocion.control.js?v=20260924-mobile';
import { iniciarDemoRuleta } from './promocion.demo.js?v=20260924-mobile';
const dialog = document.getElementById('chapi-promo');
if (dialog && typeof dialog.showModal === 'function') {
  const control = new PromocionControl(dialog);
  iniciarDemoRuleta(control, document.getElementById('chapi-promo-demo-launcher'));
  control.iniciar();
}
