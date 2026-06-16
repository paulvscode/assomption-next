"use client";

import { useState, useRef, useEffect } from "react";
import Link from "next/link";
import { useTranslations, useLocale } from "next-intl";

type Child = { labelKey: string; href: string };
type NavItem = { labelKey: string; children: Child[] };

export default function Navigation() {
  const t = useTranslations("nav");
  const locale = useLocale();
  const [openMenu, setOpenMenu] = useState<string | null>(null);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [openMobileSection, setOpenMobileSection] = useState<string | null>(null);
  const navRef = useRef<HTMLDivElement>(null);

  const base = `/${locale}`;

  const navItems: NavItem[] = [
    {
      labelKey: "ourSchool",
      children: [
        { labelKey: "schoolPresentation", href: `${base}/notre-ecole/presentation` },
        { labelKey: "educationalProject", href: `${base}/notre-ecole/projet-educatif` },
        { labelKey: "schoolProject",      href: `${base}/notre-ecole/projet-etablissement` },
        { labelKey: "pastoralMission",    href: `${base}/notre-ecole/mission-pastorale` },
        { labelKey: "professors",         href: `${base}/notre-ecole/equipe-enseignante` },
        { labelKey: "englishProgram",     href: `${base}/notre-ecole/programme-anglais` },
      ],
    },
    {
      labelKey: "specialPrograms",
      children: [
        { labelKey: "ash",          href: `${base}/programmes-speciaux/ash` },
        { labelKey: "ulis",         href: `${base}/programmes-speciaux/ulis` },
        { labelKey: "englishClass", href: `${base}/programmes-speciaux/classe-anglophone` },
      ],
    },
    {
      labelKey: "practicalInfo",
      children: [
        { labelKey: "locationContact", href: `${base}/informations-pratiques/localisation-contact` },
        { labelKey: "cafeteria",       href: `${base}/informations-pratiques/restauration` },
        { labelKey: "application",     href: `${base}/informations-pratiques/inscription` },
        { labelKey: "fees",            href: `${base}/informations-pratiques/frais` },
        { labelKey: "backToSchool",    href: `${base}/informations-pratiques/rentree` },
        { labelKey: "schoolBreaks",    href: `${base}/informations-pratiques/vacances` },
        { labelKey: "documents",       href: `${base}/informations-pratiques/documents` },
      ],
    },
    {
      labelKey: "apel",
      children: [
        { labelKey: "apelPresentation", href: `${base}/apel/presentation` },
        { labelKey: "apelNews",         href: `${base}/apel/actualites` },
        { labelKey: "events",           href: `${base}/apel/evenements` },
      ],
    },
    {
      labelKey: "ogec",
      children: [
        { labelKey: "ogecPresentation", href: `${base}/ogec/presentation` },
        { labelKey: "ogecNews",         href: `${base}/ogec/actualites` },
      ],
    },
  ];

  /* Close mobile menu on outside click */
  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (navRef.current && !navRef.current.contains(e.target as Node)) {
        setMobileOpen(false);
      }
    };
    document.addEventListener("mousedown", handler);
    return () => document.removeEventListener("mousedown", handler);
  }, []);

  return (
    <div ref={navRef} className="relative">

      {/* ── Desktop ── */}
      <ul className="hidden lg:flex items-center">
        {navItems.map((item) => (
          <li
            key={item.labelKey}
            className="relative"
            onMouseEnter={() => setOpenMenu(item.labelKey)}
            onMouseLeave={() => setOpenMenu(null)}
          >
            <button
              className="flex items-center gap-1 px-3 py-2 text-[13px] font-medium uppercase tracking-[0.06em] text-primary transition-opacity hover:opacity-60"
              aria-expanded={openMenu === item.labelKey}
            >
              {t(item.labelKey as Parameters<typeof t>[0])}
              <ChevronDown
                className={`w-3.5 h-3.5 transition-transform duration-200 ${
                  openMenu === item.labelKey ? "rotate-180" : ""
                }`}
              />
            </button>

            {/* Dropdown panel */}
            <div
              className={`absolute left-0 top-full pt-2 transition-all duration-200 ${
                openMenu === item.labelKey
                  ? "opacity-100 translate-y-0 pointer-events-auto"
                  : "opacity-0 -translate-y-1 pointer-events-none"
              }`}
            >
              <ul className="min-w-56 rounded-xl border border-border bg-white py-1.5 shadow-lg">
                {item.children.map((child) => (
                  <li key={child.labelKey}>
                    <Link
                      href={child.href}
                      className="block px-4 py-2 text-[13px] text-primary hover:bg-surface transition-colors"
                      onClick={() => setOpenMenu(null)}
                    >
                      {t(child.labelKey as Parameters<typeof t>[0])}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
          </li>
        ))}
      </ul>

      {/* ── Mobile toggle ── */}
      <button
        className="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg text-primary hover:bg-surface transition-colors"
        onClick={() => setMobileOpen(!mobileOpen)}
        aria-label="Menu"
        aria-expanded={mobileOpen}
      >
        {mobileOpen ? <XIcon /> : <MenuIcon />}
      </button>

      {/* ── Mobile panel ── */}
      {mobileOpen && (
        <div className="lg:hidden fixed inset-x-0 top-16 sm:top-26.5 z-50 h-[calc(100dvh-64px)] sm:h-[calc(100dvh-106px)] overflow-y-auto bg-white border-t border-border shadow-xl">
          <div className="divide-y divide-border">
            {navItems.map((item) => {
              const isOpen = openMobileSection === item.labelKey;
              return (
                <div key={item.labelKey}>
                  <button
                    className="flex w-full items-center justify-between px-5 py-4 text-[13px] font-medium uppercase tracking-[0.06em] text-primary"
                    onClick={() =>
                      setOpenMobileSection(isOpen ? null : item.labelKey)
                    }
                  >
                    {t(item.labelKey as Parameters<typeof t>[0])}
                    <ChevronDown
                      className={`w-4 h-4 transition-transform duration-200 ${
                        isOpen ? "rotate-180" : ""
                      }`}
                    />
                  </button>

                  {isOpen && (
                    <ul className="bg-surface pb-2">
                      {item.children.map((child) => (
                        <li key={child.labelKey}>
                          <Link
                            href={child.href}
                            className="block px-8 py-3 text-[13px] text-primary hover:opacity-60 transition-opacity"
                            onClick={() => setMobileOpen(false)}
                          >
                            {t(child.labelKey as Parameters<typeof t>[0])}
                          </Link>
                        </li>
                      ))}
                    </ul>
                  )}
                </div>
              );
            })}
          </div>
        </div>
      )}
    </div>
  );
}

/* ── Inline icons ── */
function ChevronDown({ className }: { className?: string }) {
  return (
    <svg
      className={className}
      viewBox="0 0 16 16"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
    >
      <path d="M4 6l4 4 4-4" />
    </svg>
  );
}

function MenuIcon() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.75" strokeLinecap="round" className="w-5 h-5">
      <path d="M4 7h16M4 12h16M4 17h16" />
    </svg>
  );
}

function XIcon() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.75" strokeLinecap="round" className="w-5 h-5">
      <path d="M6 18L18 6M6 6l12 12" />
    </svg>
  );
}
