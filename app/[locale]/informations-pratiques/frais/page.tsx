type Locale = "fr" | "en";

const content: Record<
  Locale,
  { title: string; files: { label: string }[]; comingSoon: string }
> = {
  fr: {
    title: "Frais de scolarité",
    files: [
      { label: "Frais de scolarité" },
      { label: "Téléchargez ici l'autorisation de prélèvement" },
    ],
    comingSoon: "Document à venir",
  },
  en: {
    title: "Fees",
    files: [
      { label: "School fees" },
      { label: "Download the direct debit authorisation" },
    ],
    comingSoon: "Document coming soon",
  },
};

export default async function FeesPage({
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
        <div className="space-y-4">
          {c.files.map((file, i) => (
            <div
              key={i}
              className="flex items-center gap-3 rounded-xl border border-border bg-surface px-5 py-4 text-sm"
            >
              <span className="text-muted">↓</span>
              <span className="font-medium text-[#2E2C31]">{file.label}</span>
              <span className="ml-auto text-xs text-muted">{c.comingSoon}</span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
