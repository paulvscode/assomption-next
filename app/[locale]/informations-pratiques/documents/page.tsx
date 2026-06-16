type Locale = "fr" | "en";

const content: Record<
  Locale,
  { title: string; intro: string; items: string[]; comingSoon: string }
> = {
  fr: {
    title: "Autres documents",
    intro: "Vous pouvez consulter ici :",
    items: [
      "Le règlement intérieur",
      "La convention de scolarisation 2024-2025",
      "L'assurance scolaire Saint-Christophe",
    ],
    comingSoon: "À venir",
  },
  en: {
    title: "Other documents",
    intro: "You can consult the following documents here:",
    items: [
      "School rules",
      "Schooling agreement 2024-2025",
      "Saint-Christophe school insurance",
    ],
    comingSoon: "Coming soon",
  },
};

export default async function OtherDocumentsPage({
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
        <p className="mb-6 text-base text-[#2E2C31]">{c.intro}</p>
        <ul className="space-y-3">
          {c.items.map((item, i) => (
            <li
              key={i}
              className="flex items-center gap-3 rounded-xl border border-border bg-surface px-5 py-4 text-sm"
            >
              <span className="text-muted">↓</span>
              <span className="font-medium text-[#2E2C31]">{item}</span>
              <span className="ml-auto text-xs text-muted">{c.comingSoon}</span>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}
