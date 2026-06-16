"use client";

import { useLocale } from "next-intl";
import { usePathname, useRouter } from "next/navigation";

export default function LanguageSwitcher() {
  const locale = useLocale();
  const pathname = usePathname();
  const router = useRouter();

  const otherLocale = locale === "fr" ? "en" : "fr";

  const switchLocale = () => {
    const segments = pathname.split("/");
    segments[1] = otherLocale;
    router.push(segments.join("/"));
  };

  return (
    <button
      onClick={switchLocale}
      aria-label={`Switch to ${otherLocale === "fr" ? "Français" : "English"}`}
      className="shrink-0 rounded-full border border-primary/30 px-3 py-1 text-[11px] font-semibold uppercase tracking-widest text-primary transition-colors hover:bg-primary hover:text-white"
    >
      {otherLocale}
    </button>
  );
}
