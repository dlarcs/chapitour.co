const ns = 'http://www.w3.org/2000/svg';
const node = (tag, attrs = {}, text) => { const n = document.createElementNS(ns, tag); for (const [k, v] of Object.entries(attrs)) n.setAttribute(k, v); if (text) n.textContent = text; return n; };
export class Ruleta {
  constructor(svg) { this.svg = svg; this.rotation = -18; this.negocios = []; }
  render(negocios) {
    this.negocios = negocios;
    this.svg.replaceChildren(node('circle', { cx: 200, cy: 200, r: 198, fill: '#241b32', stroke: '#8c719f' }));
    const palette = [['#ff459e', '#291224'], ['#bda2f5', '#241631'], ['#f0ff79', '#282c12'], ['#754bb3', '#fff'], ['#f5b7d6', '#32162b'], ['#563974', '#fff']];
    const step = 360 / Math.max(negocios.length, 1);
    negocios.forEach((b, i) => {
      const [fill, ink] = palette[i % palette.length], angle = step / 2 * Math.PI / 180;
      const x = 183 * Math.sin(angle), y = 200 - 183 * Math.cos(angle);
      const g = node('g', { transform: `rotate(${step * i} 200 200)` });
      g.append(negocios.length === 1 ? node('circle', { cx: 200, cy: 200, r: 183, fill }) : node('path', { d: `M200 200L${200 - x} ${y}A183 183 0 ${step > 180 ? 1 : 0} 1 ${200 + x} ${y}Z`, fill, stroke: '#17111f', 'stroke-width': 2 }));
      const words = b.nombre.split(' '), mid = Math.ceil(words.length / 2);
      const text = node('text', { x: 200, y: 76, 'text-anchor': 'middle', fill: ink });
      text.append(node('tspan', { x: 200 }, words.slice(0, mid).join(' ')), node('tspan', { x: 200, dy: 18 }, words.slice(mid).join(' ')));
      g.append(text, node('text', { x: 200, y: 121, 'text-anchor': 'middle', fill: ink, opacity: '.65' }, '✦')); this.svg.append(g);
    });
    for (let i = 0; i < 36; i++) this.svg.append(node('circle', { cx: 200 + Math.sin(i * Math.PI / 18) * 193, cy: 200 - Math.cos(i * Math.PI / 18) * 193, r: 1.7, fill: '#e9daf5', opacity: '.8' }));
    this.svg.setAttribute('aria-label', `Ruleta de ${negocios.map(b => b.nombre).join(', ')}`);
  }
  async animate(businessId) {
    const index = this.negocios.findIndex(b => Number(b.id) === Number(businessId));
    if (index < 0) return;
    const target = (360 - index * 360 / this.negocios.length) % 360;
    this.rotation = Math.ceil(this.rotation / 360) * 360 + 1800 + target;
    const reduced = matchMedia('(prefers-reduced-motion: reduce)');
    this.svg.style.transition = reduced.matches ? 'none' : 'transform 4200ms cubic-bezier(.16,.03,.12,1)';
    this.svg.style.transform = `rotate(${this.rotation}deg)`;
    if (reduced.matches) return;
    await new Promise(resolve => { let timer; const finish = () => { clearTimeout(timer); this.svg.removeEventListener('transitionend', end); reduced.removeEventListener('change', change); resolve(); }; const end = e => { if (e.propertyName === 'transform') finish(); }; const change = e => { if (e.matches) finish(); }; this.svg.addEventListener('transitionend', end); reduced.addEventListener('change', change); timer = setTimeout(finish, 4350); });
  }
}
