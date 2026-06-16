type Locale = "fr" | "en";

const content: Record<
  Locale,
  {
    title: string;
    downloadLabel: string;
    list1Title: string;
    list1: string[];
    list2Title: string;
    list2: string[];
    comingSoon: string;
  }
> = {
  fr: {
    title: "Formulaire d'inscription",
    downloadLabel: "Téléchargez la fiche d'inscription au format PDF ci-dessous",
    list1Title: "Voici les documents à transmettre lors de l'inscription :",
    list1: [
      "Le carnet de santé de votre enfant (photocopies des pages des vaccinations).",
      "Le livret de famille (photocopies des pages des parents et de l'enfant).",
      "Un chèque d'acompte de 75 € (à l'ordre d'OGEC Assomption) qui sera déduit de la 1re facture.",
    ],
    list2Title: "Autres documents consultables :",
    list2: [
      "Convention de scolarisation",
      "Le projet éducatif",
      "Règlement intérieur",
    ],
    comingSoon: "Document à venir",
  },
  en: {
    title: "Application form",
    downloadLabel: "Download the application form in PDF format below",
    list1Title: "Documents required at enrolment:",
    list1: [
      "Your child's health record (photocopies of vaccination pages).",
      "Family record book (photocopies of parents' and child's pages).",
      "A deposit cheque of €75 (payable to OGEC Assomption), deducted from the first invoice.",
    ],
    list2Title: "Other documents available:",
    list2: [
      "Schooling agreement",
      "Educational project",
      "School rules",
    ],
    comingSoon: "Document coming soon",
  },
};

export default async function ApplicationFormPage({
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

      <div className="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8 space-y-10">
        {/* Download button (placeholder) */}
        <div>
          <p className="mb-4 text-base text-[#2E2C31]">{c.downloadLabel}</p>
          <div className="inline-flex items-center gap-2 rounded-xl border border-border bg-surface px-5 py-3 text-sm text-muted">
            ↓ {c.comingSoon}
          </div>
        </div>

        {/* Required documents */}
        <div>
          <p className="mb-4 font-semibold text-primary">{c.list1Title}</p>
          <ul className="space-y-3">
            {c.list1.map((item, i) => (
              <li key={i} className="flex items-start gap-3 text-sm leading-relaxed text-[#2E2C31]">
                <span className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary/60" />
                {item}
              </li>
            ))}
          </ul>
        </div>

        {/* Other documents */}
        <div className="rounded-2xl border border-border bg-surface p-6">
          <p className="mb-4 font-semibold text-primary">{c.list2Title}</p>
          <ul className="space-y-2.5">
            {c.list2.map((item, i) => (
              <li key={i} className="flex items-center gap-3 rounded-xl border border-border bg-white px-4 py-3 text-sm text-[#2E2C31]">
                <span className="text-muted">↓</span>
                {item}
                <span className="ml-auto text-xs text-muted">{c.comingSoon}</span>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  );
}
