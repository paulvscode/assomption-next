type Locale = "fr" | "en";

type EnrollmentCase = { description: string; condition: string };

const content: Record<
  Locale,
  {
    title: string;
    subtitle: string;
    cases: EnrollmentCase[];
    text: string;
    activitiesTitle: string;
    activities: string[];
    finalWord: string;
  }
> = {
  fr: {
    title: "La Classe anglophone d'Immersion Linguistique et Culturelle",
    subtitle:
      "La Classe anglophone d'Immersion Linguistique et Culturelle doit permettre :",
    cases: [
      {
        description:
          "Aux enfants en partance pour l'étranger d'avoir un contact approfondi avec la langue anglaise.",
        condition:
          "Les parents présentent à l'école un projet de départ à l'étranger certifié par l'entreprise.",
      },
      {
        description:
          "Aux enfants arrivant d'un pays étranger d'être pris en charge en anglais et de maintenir ainsi leur niveau de langue.",
        condition:
          "Les parents transmettent à l'école le livret scolaire de l'enfant.",
      },
      {
        description:
          "Aux enfants de père et/ou mère anglophone(s) vivant en France de poursuivre des apprentissages linguistiques en anglais.",
        condition:
          "La langue anglaise est parlée quotidiennement à la maison.",
      },
    ],
    text:
      "Les élèves sont regroupés et inscrits dans une classe « ordinaire » qui correspond à leur tranche d'âge. Ils constituent des groupes de 3 à 10 maximum. Conformément à un emploi du temps fixé par l'enseignante en début d'année, et en concertation avec l'enseignant de la classe de référence, chaque groupe est pris en charge 45 minutes par jour.",
    activitiesTitle:
      "Les activités se font uniquement en anglais dans les domaines suivants",
    activities: [
      "Compréhension écrite",
      "Production écrite",
      "Compréhension de l'oral",
      "Production orale",
    ],
    finalWord:
      "Elles correspondent aux exigences du Cadre européen commun de référence pour les langues.",
  },
  en: {
    title: "The English-language Immersion Class",
    subtitle: "The English-language Class of Linguistic and Cultural Immersion is designed to:",
    cases: [
      {
        description:
          "Allow children moving abroad to develop in-depth contact with the English language.",
        condition:
          "Parents present the school with a company-certified relocation project.",
      },
      {
        description:
          "Support children arriving from an English-speaking country in maintaining their language level.",
        condition: "Parents provide the school with the child's school report.",
      },
      {
        description:
          "Enable children with one or both English-speaking parents living in France to continue developing their English language skills.",
        condition: "English is spoken daily at home.",
      },
    ],
    text:
      "Pupils are grouped and enrolled in a 'regular' class corresponding to their age group. Groups consist of 3 to 10 pupils maximum. According to a timetable set by the teacher at the start of the year, and in consultation with the reference class teacher, each group receives 45 minutes of instruction per day.",
    activitiesTitle: "All activities are conducted exclusively in English, covering:",
    activities: [
      "Reading comprehension",
      "Written production",
      "Listening comprehension",
      "Oral production",
    ],
    finalWord:
      "These correspond to the requirements of the Common European Framework of Reference for Languages.",
  },
};

export default async function EnglishClassPage({
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
          <h1 className="font-display text-2xl font-bold text-primary sm:text-3xl">{c.title}</h1>
        </div>
      </div>

      <div className="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
        <p className="mb-8 text-lg font-semibold text-primary">{c.subtitle}</p>

        {/* Enrollment cases */}
        <div className="space-y-5">
          {c.cases.map((item, i) => (
            <div key={i} className="rounded-2xl border border-border bg-white p-6 shadow-sm">
              <p className="text-base leading-relaxed text-[#2E2C31]">{item.description}</p>
              <p className="mt-3 text-sm text-muted">
                <span className="font-semibold text-primary">Condition : </span>
                {item.condition}
              </p>
            </div>
          ))}
        </div>

        {/* Operational text */}
        <p className="mt-10 text-base leading-relaxed text-[#2E2C31]">{c.text}</p>

        {/* Activities */}
        <div className="mt-8 rounded-2xl border border-border bg-surface p-6">
          <p className="mb-4 font-semibold text-primary">{c.activitiesTitle}</p>
          <ul className="grid gap-2 sm:grid-cols-2">
            {c.activities.map((a, i) => (
              <li key={i} className="flex items-center gap-2.5 text-sm text-[#2E2C31]">
                <span className="h-1.5 w-1.5 shrink-0 rounded-full bg-primary/60" />
                {a}
              </li>
            ))}
          </ul>
        </div>

        <p className="mt-6 text-sm italic text-muted">{c.finalWord}</p>
      </div>
    </div>
  );
}
