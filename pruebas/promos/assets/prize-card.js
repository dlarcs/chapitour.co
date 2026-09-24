const node = (tag, text, className) => {
  const n = document.createElement(tag);
  if (text !== undefined) n.textContent = text;
  if (className) n.className = className;
  return n;
};
const dates = new Intl.DateTimeFormat('es-CO', { dateStyle: 'medium', timeStyle: 'short', timeZone: 'America/Bogota' });
export function prizeCard(prize, onClaim) {
  const card = node('article', undefined, 'prize-card');
  const top = node('div', undefined, 'prize-card__top');
  top.append(node('span', prize.categoria, 'eyebrow'), node('span', prize.estado, `badge ${prize.estado}`));
  card.append(top, node('h3', prize.negocio), node('p', prize.titulo, 'prize-card__benefit'));
  if (prize.descripcion) card.append(node('p', prize.descripcion));
  const code = node('div', undefined, 'prize-card__code'), copy = node('button', 'Copiar código', 'subtle');
  copy.type = 'button';
  const feedback = node('p', '', 'prize-card__feedback'); feedback.setAttribute('role', 'status');
  copy.addEventListener('click', async () => { try { await navigator.clipboard.writeText(prize.codigo); feedback.textContent = 'Código copiado.'; } catch { feedback.textContent = `Copia este código: ${prize.codigo}`; } });
  code.append(node('code', prize.codigo), copy); card.append(code, feedback);
  card.append(node('p', `Dirección: ${prize.direccion || 'Por confirmar con el negocio'}`, 'prize-card__address'));
  card.append(node('p', `Tienes ${prize.vigencia_horas} horas desde el giro para redimir. Vence: ${prize.vence_texto}.`, 'prize-card__expiry'));
  card.append(node('p', prize.condiciones, 'prize-card__terms'));
  const timeline = node('ol', undefined, 'prize-timeline'); timeline.setAttribute('aria-label', 'Seguimiento del código');
  for (const step of prize.seguimiento) {
    const item = node('li'); item.append(node('strong', step.estado), node('span', dates.format(new Date(step.fecha))), node('p', step.detalle)); timeline.append(item);
  }
  card.append(timeline);
  const details = node('details', undefined, 'whatsapp-preview');
  details.append(node('summary', 'Mensaje listo para WhatsApp'), node('p', prize.mensaje_whatsapp)); card.append(details);
  if (prize.url_whatsapp && prize.estado === 'activo') {
    const link = node('a', 'Reclama por WhatsApp ↗', 'primary whatsapp-button');
    link.href = prize.url_whatsapp; link.target = '_blank'; link.rel = 'noopener noreferrer';
    link.addEventListener('click', event => {
      if (Date.now() >= Date.parse(prize.vence_at)) { event.preventDefault(); feedback.textContent = 'El código ha vencido. Actualiza tu historial.'; return; }
      onClaim?.(prize);
    }); card.append(link);
  } else card.append(node('p', prize.estado === 'activo' ? 'El negocio debe configurar su WhatsApp.' : `Código ${prize.estado}.`, 'muted'));
  card.append(node('small', 'La redención la confirma el negocio cuando presentes tu código.'));
  return card;
}
