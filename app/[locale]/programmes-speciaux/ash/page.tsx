type Locale = "fr" | "en";

const content: Record<Locale, { title: string; paragraphs: string[] }> = {
  fr: {
    title: "Dispositif ASH",
    paragraphs: [
      "L'enseignant responsable du poste ASH intervient à la demande des enseignants des classes. En cas de difficultés, il analyse la situation scolaire des enfants à partir d'observations, d'évaluations, de bilans et d'entretiens. Il intervient ensuite en prenant en charge les enfants par petits groupes, au sein de leur classe ou non.",
      "Il travaille sur l'acquisition des connaissances, mais aussi la méthodologie, en référence aux programmes de l'école primaire. Il favorise la concertation à l'intérieur de l'école entre l'élève, sa famille et l'enseignant.",
      "Si besoin et selon le cas de chaque enfant, il peut proposer des aides spécialisées extérieures.",
    ],
  },
  en: {
    title: "ASH programme",
    paragraphs: [
      "The teacher responsible for the ASH post intervenes at the request of class teachers. In cases of difficulty, they analyse each child's school situation through observations, assessments, evaluations and interviews. They then provide support by taking children in small groups, within or outside their class.",
      "The focus is on knowledge acquisition as well as methodology, in line with primary school programmes. The ASH teacher promotes collaboration within the school between the pupil, their family and the class teacher.",
      "If needed, and depending on each child's situation, specialised external support may also be proposed.",
    ],
  },
};

export default async function AshPage({
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

      <div className="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8 space-y-6">
        {c.paragraphs.map((p, i) => (
          <p key={i} className="text-base leading-relaxed text-[#2E2C31] sm:text-lg">
            {p}
          </p>
        ))}
      </div>
    </div>
  );
}
