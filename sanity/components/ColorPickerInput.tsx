"use client";

import { set, unset } from "sanity";
import type { StringInputProps } from "sanity";

export function ColorPickerInput({ value, onChange, elementProps }: StringInputProps) {
  const hex = value || "#8BB1C5";

  return (
    <div style={{ display: "flex", alignItems: "center", gap: 12 }}>
      <input
        {...elementProps}
        type="color"
        value={hex}
        onChange={(e) =>
          onChange(e.target.value ? set(e.target.value) : unset())
        }
        style={{
          width: 48,
          height: 36,
          padding: 2,
          borderRadius: 6,
          border: "1px solid #ccc",
          cursor: "pointer",
          backgroundColor: "transparent",
        }}
      />
      <span style={{ fontFamily: "monospace", fontSize: 14, color: "#666" }}>
        {hex.toUpperCase()}
      </span>
    </div>
  );
}
