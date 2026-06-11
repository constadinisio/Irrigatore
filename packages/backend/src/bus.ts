import { EventEmitter } from "node:events";
import type { TelemetryPayload } from "@irrigatore/shared";

export interface TelemetryEvent {
  deviceKey: string;
  payload: TelemetryPayload;
}

class TypedBus extends EventEmitter {
  emitTelemetry(ev: TelemetryEvent): void {
    this.emit("telemetry", ev);
  }

  onTelemetry(fn: (ev: TelemetryEvent) => void): void {
    this.on("telemetry", fn);
  }

  offTelemetry(fn: (ev: TelemetryEvent) => void): void {
    this.off("telemetry", fn);
  }
}

export const bus = new TypedBus();
