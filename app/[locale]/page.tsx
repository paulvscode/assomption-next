import Link from "next/link";
import Image from "next/image";
import { client } from "@/sanity/lib/client";
import { singletonQuery, newsSchoolPreviewQuery } from "@/sanity/lib/queries";
import { urlFor } from "@/sanity/lib/image";

type Locale = "fr" | "en";

// ── Static fallback content ───────────────────────────────────────────────────
const fallback: Record<Locale, {
  tagline: string;
  h1: string;
  h2: string;
  statement: string;
  cta: string;
  ctaContact: string;
  linksTitle: string;
  links: { title: string; href: string }[];
  contactTitle: string;
  addressLabel: string;
  phoneLabel: string;
  emailLabel: string;
  hoursLabel: string;
  address: string;
  phone: string;
  email: string;
  hours: string;
}> = {
  fr: {
    tagline:      "Le Havre · École catholique",
    h1:           "École de l'Assomption",
    h2:           "Une école ouverte à l'autre",
    statement:    "Située en plein cœur du Havre, l'école de l'Assomption est une école maternelle et primaire catholique sous contrat d'association avec l'État, ayant obtenu le Label « Ouverture à l'Internationale ». 300 enfants y sont accueillis dans un esprit d'ouverture et de bienveillance.",
    cta:          "En savoir plus",
    ctaContact:   "Nous contacter",
    linksTitle:   "Liens rapides",
    links: [
      { title: "L'équipe",                href: "notre-ecole/equipe-enseignante" },
      { title: "Le projet éducatif",      href: "notre-ecole/projet-educatif" },
      { title: "Le projet d'établissement", href: "notre-ecole/projet-etablissement" },
      { title: "Le projet pastoral",      href: "notre-ecole/mission-pastorale" },
      { title: "L'anglais",               href: "notre-ecole/programme-anglais" },
      { title: "Les classes spécifiques", href: "programmes-speciaux/ash" },
    ],
    contactTitle: "Nous contacter",
    addressLabel: "Adresse",
    phoneLabel:   "Téléphone",
    emailLabel:   "Email",
    hoursLabel:   "Horaires d'ouverture",
    address:      "32, rue Lord Kitchener\n76600 - Le Havre",
    phone:        "(+33) 02 35 43 60 68",
    email:        "dir.ec.assomption.lehavre@srec-hn.com",
    hours:        "Lundi, mardi, jeudi, vendredi\n7h45 – 18h00",
  },
  en: {
    tagline:      "Le Havre · Catholic school",
    h1:           "École de l'Assomption",
    h2:           "A school open to others",
    statement:    "Located in the heart of Le Havre, the École de l'Assomption is a Catholic nursery and primary school under contract with the State, having received Level 2 of the 'Ouverture à l'Internationale' Label. 300 children are welcomed here in a spirit of openness and kindness.",
    cta:          "Learn more",
    ctaContact:   "Contact us",
    linksTitle:   "Quick links",
    links: [
      { title: "Our team",             href: "notre-ecole/equipe-enseignante" },
      { title: "Educational project",  href: "notre-ecole/projet-educatif" },
      { title: "School project",       href: "notre-ecole/projet-etablissement" },
      { title: "Pastoral mission",     href: "notre-ecole/mission-pastorale" },
      { title: "English programme",    href: "notre-ecole/programme-anglais" },
      { title: "Specific classes",     href: "programmes-speciaux/ash" },
    ],
    contactTitle: "Contact us",
    addressLabel: "Address",
    phoneLabel:   "Phone",
    emailLabel:   "Email",
    hoursLabel:   "Opening hours",
    address:      "32, rue Lord Kitchener\n76600 - Le Havre",
    phone:        "(+33) 02 35 43 60 68",
    email:        "dir.ec.assomption.lehavre@srec-hn.com",
    hours:        "Monday, Tuesday, Thursday, Friday\n7:45 am – 6:00 pm",
  },
};

// ── Page ─────────────────────────────────────────────────────────────────────
export default async function LandingPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const f = fallback[(locale as Locale)] ?? fallback.fr;
  const l = locale as Locale;

  const [page, siteSettings, latestNews] = await Promise.all([
    client.fetch(singletonQuery("landing")),
    client.fetch(singletonQuery("settings")).catch(() => null),
    client.fetch(newsSchoolPreviewQuery).catch(() => []),
  ]);

  // Helpers: pick Sanity value if non-empty, else fall back
  const s = (field: string, fb: string) =>
    (page?.[field]?.[l] as string | undefined)?.trim() || fb;
  const plain = (field: string, fb: string) =>
    (page?.[field] as string | undefined)?.trim() || fb;

  const accentColor = (siteSettings?.accentColor as string | undefined)?.trim() || "#8BB1C5";

  // Resolved text values
  const tagline      = s("tagline",      f.tagline);
  const h1           = s("h1",           f.h1);
  const h2           = s("h2",           f.h2);
  const statement    = s("statement",    f.statement);
  const cta          = s("cta",          f.cta);
  const ctaContact   = s("ctaContact",   f.ctaContact);
  const linksTitle   = s("linksTitle",   f.linksTitle);
  const contactTitle = s("contactTitle", f.contactTitle);
  const address      = plain("address",  f.address);
  const phone        = plain("phone",    f.phone);
  const email        = plain("email",    f.email);
  const hours        = s("hours",        f.hours);

  // Images
  const heroImageUrl = page?.heroImage
    ? urlFor(page.heroImage).width(1920).height(1080).url()
    : null;

  const qlFields = [
    "qlEquipe", "qlProjetEducatif", "qlProjetEtablissement",
    "qlProjetPastoral", "qlAnglais", "qlClassesSpecifiques",
  ] as const;
  const quickLinkImages = qlFields.map((field) =>
    page?.[field] ? urlFor(page[field]).width(640).height(360).url() : null
  );

  const bannerEnabled = page?.bannerEnabled === true;
  const bannerText = (page?.bannerText?.[l] as string | undefined)?.trim();

  return (
    <div>
      {/* ── Banner ───────────────────────────────────────────────────────── */}
      {bannerEnabled && bannerText && (
        <div className="w-full bg-amber-400 px-4 py-3 text-center text-sm font-semibold text-amber-950 shadow-sm">
          {bannerText}
        </div>
      )}

      {/* ── Hero ─────────────────────────────────────────────────────────── */}
      <section className="relative flex min-h-[88dvh] items-center justify-center overflow-hidden bg-primary text-white">
        {heroImageUrl && (
          <Image
            src={heroImageUrl}
            alt=""
            fill
            priority
            className="object-cover opacity-40 animate-hero-zoom"
          />
        )}
        <div className="relative z-10 mx-auto max-w-4xl px-6 text-center text-white [text-shadow:2px_2px_8px_rgba(0,0,0,0.7)]">
          <p className="mb-4 text-xs font-semibold uppercase tracking-[0.35em] text-white/60">
            {tagline}
          </p>
          <h1 className="font-display text-4xl font-bold leading-tight text-white sm:text-6xl lg:text-7xl">
            {h1}
          </h1>
          <p className="mt-4 font-display text-xl italic text-white sm:text-2xl">
            {h2}
          </p>
          <p className="mx-auto mt-6 max-w-2xl text-base leading-relaxed text-white sm:text-lg">
            {statement}
          </p>
          <div className="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
            <Link
              href={`/${locale}/notre-ecole/presentation`}
              className="rounded-full bg-white px-8 py-3.5 text-sm font-semibold text-primary shadow transition-opacity hover:opacity-90 text-shadow-none"
            >
              {cta}
            </Link>
            <Link
              href={`/${locale}/informations-pratiques/localisation-contact`}
              className="rounded-full border border-white/40 px-8 py-3.5 text-sm font-semibold text-white transition-all hover:bg-white/10 text-shadow-none"
            >
              {ctaContact}
            </Link>
          </div>
        </div>
      </section>

      {/* ── Actualités ───────────────────────────────────────────────────── */}
      {page?.newsEnabled !== false && latestNews?.length > 0 && (
        <section className="bg-surface py-16">
          <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div className="mb-10 flex items-end justify-between">
              <h2 className="font-display text-3xl font-bold text-primary">
                {l === "fr" ? "Actualités" : "News"}
              </h2>
              <Link
                href={`/${locale}/actualites`}
                className="text-sm font-semibold text-primary/60 hover:text-primary transition-colors"
              >
                {l === "fr" ? "Voir tout →" : "See all →"}
              </Link>
            </div>

            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
              {latestNews.map((article: {
                _id: string;
                title?: { fr?: string; en?: string };
                publishedAt?: string;
                coverImage?: unknown;
                excerpt?: { fr?: string; en?: string };
              }) => {
                const title = article.title?.[l] || article.title?.fr || "";
                const excerpt = article.excerpt?.[l] || article.excerpt?.fr || "";
                const imageUrl = article.coverImage
                  ? urlFor(article.coverImage).width(640).height(360).url()
                  : null;
                const date = article.publishedAt
                  ? new Date(article.publishedAt).toLocaleDateString(
                      l === "fr" ? "fr-FR" : "en-GB",
                      { day: "numeric", month: "long", year: "numeric" }
                    )
                  : null;

                return (
                  <Link
                    key={article._id}
                    href={`/${locale}/actualites/${article._id}`}
                    className="group flex flex-col overflow-hidden rounded-2xl border border-border bg-white shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5"
                  >
                    <div className="relative aspect-video bg-surface">
                      {imageUrl && (
                        <Image src={imageUrl} alt={title} fill className="object-cover" />
                      )}
                    </div>
                    <div className="flex flex-1 flex-col p-5">
                      {date && (
                        <p className="mb-2 text-xs font-semibold uppercase tracking-widest text-muted">
                          {date}
                        </p>
                      )}
                      <h3 className="font-display text-base font-bold text-primary line-clamp-2">
                        {title}
                      </h3>
                      {excerpt && (
                        <p className="mt-2 text-sm leading-relaxed text-[#2E2C31]/70 line-clamp-3">
                          {excerpt}
                        </p>
                      )}
                    </div>
                  </Link>
                );
              })}
            </div>
          </div>
        </section>
      )}

      {/* Pattern divider: hero → quick links */}
      <div aria-hidden="true">
        <svg width="100%" height="56" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <pattern id="diamonds-top" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
              <rect width="28" height="28" fill="white"/>
              <polygon points="14,4 24,14 14,24 4,14" fill={accentColor}/>
            </pattern>
          </defs>
          <rect width="100%" height="56" fill="url(#diamonds-top)"/>
        </svg>
      </div>

      {/* ── Quick links ──────────────────────────────────────────────────── */}
      <section className="bg-white py-16">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <h2 className="mb-10 text-center font-display text-3xl font-bold text-primary">
            {linksTitle}
          </h2>
          <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            {f.links.map((link, i) => (
              <Link
                key={link.href}
                href={`/${locale}/${link.href}`}
                className="group flex flex-col overflow-hidden rounded-2xl border border-border bg-white shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5"
              >
                <div className="relative aspect-video">
                  {quickLinkImages[i] ? (
                    <Image
                      src={quickLinkImages[i]!}
                      alt={link.title}
                      fill
                      className="object-cover"
                    />
                  ) : (
                    <div className="h-full w-full bg-surface" />
                  )}
                </div>
                <div className="flex items-center justify-between px-5 py-4">
                  <span className="font-display text-[1.0625rem] font-semibold text-primary">
                    {link.title}
                  </span>
                  <span className="transition-transform duration-200 group-hover:translate-x-1" style={{ color: accentColor }}>
                    →
                  </span>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Pattern divider: quick links → contact */}
      <div aria-hidden="true">
        <svg width="100%" height="56" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <pattern id="diamonds-bottom" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
              <rect width="28" height="28" fill="white"/>
              <polygon points="14,4 24,14 14,24 4,14" fill={accentColor}/>
            </pattern>
          </defs>
          <rect width="100%" height="56" fill="url(#diamonds-bottom)"/>
        </svg>
      </div>

      {/* ── Contact ──────────────────────────────────────────────────────── */}
      <section className="bg-primary-dark py-20">
        <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
          <h2 className="mb-10 text-center font-display text-3xl font-bold text-white">
            {contactTitle}
          </h2>
          <div className="grid gap-8 text-center sm:grid-cols-3">
            <div>
              <p className="mb-2 text-xs font-semibold uppercase tracking-widest text-white/60">
                {f.addressLabel}
              </p>
              <p className="whitespace-pre-line font-semibold text-white">{address}</p>
            </div>
            <div className="space-y-4">
              <div>
                <p className="mb-1 text-xs font-semibold uppercase tracking-widest text-white/60">
                  {f.phoneLabel}
                </p>
                <a href={`tel:${phone.replace(/\s/g, "")}`} className="font-semibold text-white hover:underline">
                  {phone}
                </a>
              </div>
              <div>
                <p className="mb-1 text-xs font-semibold uppercase tracking-widest text-white/60">
                  {f.emailLabel}
                </p>
                <a href={`mailto:${email}`} className="break-all text-sm font-semibold text-white hover:underline">
                  {email}
                </a>
              </div>
            </div>
            <div>
              <p className="mb-2 text-xs font-semibold uppercase tracking-widest text-white/60">
                {f.hoursLabel}
              </p>
              <p className="whitespace-pre-line font-semibold text-white">{hours}</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
