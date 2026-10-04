'use strict';
const toggle = document.querySelector('.menu-toggle');
const navigation = document.querySelector('#navigation');
const mobile = window.matchMedia('(max-width: 800px)');
function closeMenu() {
  navigation.hidden = mobile.matches;
  toggle.setAttribute('aria-expanded', 'false');
}
toggle.addEventListener('click', () => {
  const open = toggle.getAttribute('aria-expanded') !== 'true';
  toggle.setAttribute('aria-expanded', String(open));
  navigation.hidden = !open;
});
navigation.addEventListener('click', event => {
  if (event.target.closest('a')) closeMenu();
});
document.addEventListener('keydown', event => {
  if (event.key === 'Escape' && mobile.matches && !navigation.hidden) {
    closeMenu();
    toggle.focus();
  }
});
mobile.addEventListener('change', closeMenu);
closeMenu();
