import { describe, it, expect, afterAll } from "vitest";
import postgres from "postgres";

const url = process.env.DATABASE_URL!;
const sql = postgres(url, { max: 1 });
afterAll(async () => { await sql.end(); });

describe("seed", () => {
  it("crea un device de desarrollo con dos zonas", async () => {
    const devs = await sql`SELECT id, device_key FROM devices WHERE device_key = 'dev-device-001'`;
    expect(devs.length).toBe(1);
    const zns = await sql`SELECT name FROM zones WHERE device_id = ${devs[0].id} ORDER BY name`;
    expect(zns.map((z) => z.name)).toEqual(["z1", "z2"]);
  });
});
