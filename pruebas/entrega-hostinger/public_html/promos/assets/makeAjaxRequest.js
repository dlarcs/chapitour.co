export class ApiError extends Error {
  constructor(message, code, status) { super(message); this.code = code; this.status = status; }
}

export function createAjaxClient(endpoint) {
  let tokenPromise;
  const csrf = () => tokenPromise ||= (async () => {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 15000);
    try {
      const response = await fetch(`${endpoint}?action=csrf`, { credentials: 'same-origin', cache: 'no-store', signal: controller.signal });
      const result = await response.json();
      if (!response.ok || !result.success) throw new Error(result.message || 'No fue posible iniciar la sesión.');
      return result.data.csrf;
    } finally { clearTimeout(timeout); }
  })().catch(error => { tokenPromise = null; throw error; });

  return async function makeAjaxRequest(action, data = {}, retryCsrf = true) {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 15000);
    try {
      const token = await csrf();
      const response = await fetch(endpoint, {
        method: 'POST', credentials: 'same-origin', cache: 'no-store', signal: controller.signal,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': token },
        body: JSON.stringify({ ...data, action }),
      });
      const result = await response.json();
      if (!response.ok || !result.success) {
        if (result.code === 'CSRF' && retryCsrf) { tokenPromise = null; return await makeAjaxRequest(action, data, false); }
        throw new ApiError(result.message || 'No fue posible completar la acción.', result.code, response.status);
      }
      return result.data;
    } catch (error) {
      if (error.name === 'AbortError') throw new ApiError('La solicitud tardó demasiado. Puedes reintentar sin duplicar tu giro.', 'TIMEOUT', 0);
      if (error instanceof ApiError) throw error;
      throw new ApiError('No pudimos conectar. Comprueba tu conexión e intenta de nuevo.', 'NETWORK', 0);
    } finally { clearTimeout(timeout); }
  };
}
