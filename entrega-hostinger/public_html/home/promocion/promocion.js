import { PromocionControl } from './promocion.control.js';
const dialog = document.getElementById('chapi-promo');
if (dialog && typeof dialog.showModal === 'function') new PromocionControl(dialog).iniciar();
