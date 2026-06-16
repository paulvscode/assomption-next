"use client";

import dynamic from "next/dynamic";
import config from "@/sanity.apel.config";
import LogoutButton from "@/components/ui/LogoutButton";

const NextStudio = dynamic(
  () => import("next-sanity/studio").then((m) => m.NextStudio),
  { ssr: false }
);

export default function ApelStudioPage() {
  return (
    <div className="flex h-svh flex-col">
      <div className="flex shrink-0 items-center justify-between border-b border-border bg-white px-5 py-2.5">
        <span className="font-display text-sm font-semibold text-primary">
          APEL — Actualités & Événements
        </span>
        <div className="flex items-center gap-3">
          <a href="/fr" className="text-xs text-muted hover:text-primary transition-colors">
            ← Voir le site
          </a>
          <LogoutButton logoutPath="/api/auth/logout-apel" redirectTo="/login-apel" />
        </div>
      </div>
      <div className="flex-1 min-h-0">
        <NextStudio config={config} />
      </div>
    </div>
  );
}
