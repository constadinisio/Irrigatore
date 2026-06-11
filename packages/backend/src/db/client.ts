import { drizzle } from "drizzle-orm/postgres-js";
import postgres from "postgres";
import * as schema from "./schema.js";

// No lanzamos en la carga del módulo: postgres() es lazy (conecta en la
// primera query). La validación de que DATABASE_URL exista en producción
// la hace el bootstrap vía loadConfig() en config.ts. Esto permite importar
// la cadena (p. ej. buildServer en el test de WS) sin una DB real, mientras
// los tests de integración se gatean con requireDb (process.env.DATABASE_URL).
const url = process.env.DATABASE_URL ?? "";

export const sql = postgres(url);
export const db = drizzle(sql, { schema });
