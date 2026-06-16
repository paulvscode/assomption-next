import Link from "next/link";
import { useLocale, useTranslations } from "next-intl";

export default function Footer() {
  const locale = useLocale();
  const tn = useTranslations("nav");
  const tf = useTranslations("footer");
  const tc = useTranslations("common");

  const base = `/${locale}`;

  const columns = [
    {
      heading: tn("ourSchool"),
      links: [
        { label: tn("schoolPresentation"), href: `${base}/notre-ecole/presentation` },
        { label: tn("educationalProject"),  href: `${base}/notre-ecole/projet-educatif` },
        { label: tn("pastoralMission"),     href: `${base}/notre-ecole/mission-pastorale` },
        { label: tn("professors"),          href: `${base}/notre-ecole/equipe-enseignante` },
        { label: tn("englishProgram"),      href: `${base}/notre-ecole/programme-anglais` },
      ],
    },
    {
      heading: tn("practicalInfo"),
      links: [
        { label: tn("locationContact"), href: `${base}/informations-pratiques/localisation-contact` },
        { label: tn("application"),     href: `${base}/informations-pratiques/inscription` },
        { label: tn("fees"),            href: `${base}/informations-pratiques/frais` },
        { label: tn("cafeteria"),       href: `${base}/informations-pratiques/restauration` },
        { label: tn("schoolBreaks"),    href: `${base}/informations-pratiques/vacances` },
      ],
    },
    {
      heading: "APEL & OGEC",
      links: [
        { label: tn("apelPresentation"), href: `${base}/apel/presentation` },
        { label: tn("apelNews"),         href: `${base}/apel/actualites` },
        { label: tn("events"),           href: `${base}/apel/evenements` },
        { label: tn("ogecPresentation"), href: `${base}/ogec/presentation` },
        { label: tn("ogecNews"),         href: `${base}/ogec/actualites` },
      ],
    },
  ];

  return (
    <footer className="bg-primary text-white">

      {/* Main grid */}
      <div className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div className="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">

          {/* Brand column */}
          <div>
            <p className="font-display text-lg font-semibold tracking-wide">
              {tc("schoolName")}
            </p>
            <p className="mt-3 text-sm leading-relaxed text-white/60">
              {locale === "fr"
                ? "Une école catholique engagée dans l'excellence éducative."
                : "A Catholic school committed to educational excellence."}
            </p>
            <Link
              href={`${base}/informations-pratiques/localisation-contact`}
              className="mt-4 inline-block text-sm text-white/70 underline-offset-2 hover:text-white hover:underline transition-colors"
            >
              {tc("contactUs")} →
            </Link>
          </div>

          {/* Link columns */}
          {columns.map((col) => (
            <div key={col.heading}>
              <p className="text-[11px] font-semibold uppercase tracking-widest text-white/40 mb-4">
                {col.heading}
              </p>
              <ul className="space-y-2.5">
                {col.links.map((link) => (
                  <li key={link.href}>
                    <Link
                      href={link.href}
                      className="text-sm text-white/70 hover:text-white transition-colors"
                    >
                      {link.label}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
      </div>

      {/* Bottom bar */}
      <div className="border-t border-white/10">
        <div className="mx-auto flex max-w-7xl flex-col items-center gap-2 px-4 py-5 text-xs text-white/40 sm:flex-row sm:justify-between sm:px-6 lg:px-8">
          <p>© {new Date().getFullYear()} {tc("schoolName")} — {tf("rights")}</p>
          <div className="flex gap-5">
            <Link href={`${base}/mentions-legales`} className="hover:text-white/70 transition-colors">
              {tf("legalNotice")}
            </Link>
            <Link href={`${base}/confidentialite`} className="hover:text-white/70 transition-colors">
              {tf("privacyPolicy")}
            </Link>
          </div>
        </div>
      </div>

    </footer>
  );
}
