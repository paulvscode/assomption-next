"use client";

import dynamic from "next/dynamic";
import config from "@/sanity.config";
import LogoutButton from "@/components/ui/LogoutButton";

const NextStudio = dynamic(
  () => import("next-sanity/studio").then((m) => m.NextStudio),
  { ssr: false }
);

export default function StudioPage() {
  return (
    <div className="flex h-svh flex-col">
      {/* Admin bar */}
      <div className="flex shrink-0 items-center justify-between border-b border-border bg-white px-5 py-2.5">
        <span className="font-display text-sm font-semibold text-primary">
          École Assomption — Studio
        </span>
        <div className="flex items-center gap-3">
          <a
            href="/fr"
            className="text-xs text-muted hover:text-primary transition-colors"
          >
            ← Voir le site
          </a>
          <LogoutButton />
        </div>
      </div>

      {/* Sanity Studio fills the rest — min-h-0 lets Studio manage its own overflow */}
      <div className="flex-1 min-h-0">
        <NextStudio config={config} />
      </div>
    </div>
  );
}
