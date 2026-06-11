import { defineConfig } from "vitest/config";

export default defineConfig({
  test: {
    env: {
      // Provide a fake URL so db/client.ts doesn't throw at module init time
      // when running tests that don't need a real DB (e.g. the WS live test).
      // If DATABASE_URL is already set in the process env (e.g. from .env sourcing),
      // that value takes precedence at the shell level.
      DATABASE_URL:
        process.env.DATABASE_URL ?? "postgresql://fake:fake@localhost:5432/fake",
    },
  },
});
