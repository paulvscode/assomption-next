type Locale = "fr" | "en";

const content: Record<Locale, { title: string; text: string; linkLabel: string }> = {
  fr: {
    title: "Restauration scolaire",
    text: "Les repas de cantine sont préparés par la société API Restauration.",
    linkLabel: "Consulter les menus sur c-est-pret.com",
  },
  en: {
    title: "Cafeteria & meals",
    text: "Cafeteria meals are prepared by API Restauration.",
    linkLabel: "View menus at c-est-pret.com",
  },
};

export default async function CafeteriaPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const c = content[(locale as Locale)] ?? content.fr;

  return (
    <div>
      <div className="border-b border-border bg-surface py-12">
        <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
          <h1 className="font-display text-3xl font-bold text-primary sm:text-4xl">{c.title}</h1>
        </div>
      </div>

      <div className="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
        <p className="text-base leading-relaxed text-[#2E2C31] sm:text-lg">{c.text}</p>
        <p className="mt-4 text-base leading-relaxed text-[#2E2C31] sm:text-lg">
          {locale === "fr"
            ? "Vous pouvez consulter les menus en cliquant sur le lien :"
            : "You can view the menus by clicking the link below:"}
        </p>
        <a
          href="https://www.c-est-pret.com"
          target="_blank"
          rel="noopener noreferrer"
          className="mt-6 inline-flex items-center gap-2 rounded-full bg-primary px-7 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"
        >
          {c.linkLabel} →
        </a>
      </div>
    </div>
  );
}
