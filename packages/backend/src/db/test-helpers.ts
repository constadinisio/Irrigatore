import type { describe } from "vitest";

/**
 * Gatea suites de integración: si no hay DATABASE_URL, se skipean
 * en vez de fallar con conexión rechazada. Devuelve describe o describe.skip.
 */
export function requireDb(d: typeof describe): typeof describe {
  return (process.env.DATABASE_URL ? d : d.skip) as typeof describe;
}
