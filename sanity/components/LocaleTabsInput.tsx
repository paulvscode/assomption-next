"use client";

import { useState } from "react";
import type { ObjectInputProps } from "sanity";

const LOCALES: { name: "fr" | "en"; label: string }[] = [
  { name: "fr", label: "FR" },
  { name: "en", label: "EN" },
];

function hasContent(value: unknown): boolean {
  if (typeof value === "string") return value.trim().length > 0;
  if (Array.isArray(value)) return value.length > 0;
  return false;
}

export function LocaleTabsInput(props: ObjectInputProps) {
  const [activeLocale, setActiveLocale] = useState<"fr" | "en">("fr");

  const value = (props.value ?? {}) as Record<string, unknown>;
  const visibleMembers = props.members.filter(
    (member) => member.kind !== "field" || member.name === activeLocale
  );

  return (
    <div>
      <div style={{ display: "flex", gap: 2, marginBottom: 8, padding: 2, borderRadius: 999, background: "#F5F5F5", width: "fit-content" }}>
        {LOCALES.map((locale) => {
          const active = locale.name === activeLocale;
          const filled = hasContent(value[locale.name]);
          return (
            <button
              key={locale.name}
              type="button"
              onClick={() => setActiveLocale(locale.name)}
              style={{
                display: "flex",
                alignItems: "center",
                gap: 6,
                border: "none",
                borderRadius: 999,
                padding: "4px 12px",
                fontSize: 11.5,
                fontWeight: 700,
                fontFamily: "inherit",
                cursor: "pointer",
                background: active ? "#FFFFFF" : "transparent",
                color: active ? "#2E2C31" : "#8B8892",
                boxShadow: active ? "0 1px 2px rgba(0,0,0,0.08)" : "none",
              }}
            >
              <span
                style={{
                  width: 6,
                  height: 6,
                  borderRadius: "50%",
                  border: "1.4px solid currentColor",
                  background: filled ? "currentColor" : "transparent",
                }}
              />
              {locale.label}
            </button>
          );
        })}
        {!hasContent(value.en) && (
          <span style={{ alignSelf: "center", marginLeft: 6, marginRight: 8, fontSize: 11, color: "#8B8892" }}>
            Anglais vide — le français sera affiché à la place
          </span>
        )}
      </div>

      {props.renderDefault({ ...props, members: visibleMembers })}
    </div>
  );
}
