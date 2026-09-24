// getRandomValues también funciona al abrir la IP local desde un celular por HTTP.
export function nuevaSolicitud() {
  const bytes = new Uint8Array(18);
  crypto.getRandomValues(bytes);
  return Array.from(bytes, byte => byte.toString(16).padStart(2, '0')).join('');
}
