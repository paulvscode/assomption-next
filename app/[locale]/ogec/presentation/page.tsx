type Locale = "fr" | "en";

const content: Record<
  Locale,
  {
    title: string;
    headline: string;
    intro: string[];
    domainsTitle: string;
    domains: { name: string; description: string }[];
    closingText: string;
    compositionTitle: string;
    members: { role: string; name: string }[];
  }
> = {
  fr: {
    title: "Présentation de l'OGEC",
    headline: "L'OGEC : des bénévoles engagés pour notre projet",
    intro: [
      "L'OGEC (Organisme de Gestion de l'Enseignement Catholique) est composé de bénévoles, parents d'élèves ou anciens parents d'élèves qui se réunissent en Conseils d'Administration 5 à 6 fois par an. Son rôle : donner les moyens à l'établissement de mettre en œuvre son projet pédagogique, éducatif et pastoral. L'établissement étant sous contrat d'association avec l'État, les enseignants sont rémunérés par l'Éducation Nationale et le programme est conforme à ses directives.",
    ],
    domainsTitle: "L'OGEC intervient dans les domaines suivants",
    domains: [
      {
        name: "Social",
        description:
          "En tant qu'employeur du personnel non pris en charge par l'État : personnel administratif et éducatif (ASEM, assistante pédagogique).",
      },
      {
        name: "Économique et financier",
        description:
          "Il perçoit les contributions des familles et des collectivités locales et gère les dépenses.",
      },
      {
        name: "Juridique",
        description: "Comme personnalité juridique de l'établissement.",
      },
      {
        name: "Gestion immobilière",
        description:
          "Pour l'entretien courant des locaux mais aussi leur rénovation, entièrement à la charge de l'OGEC.",
      },
    ],
    closingText:
      "L'OGEC assure une gestion rigoureuse et prudente de l'établissement afin de maintenir la contribution des familles à un niveau raisonnable tout en offrant un cadre de travail adapté à nos enfants et au personnel.",
    compositionTitle: "Composition de l'OGEC de l'école de l'Assomption",
    members: [
      { role: "Présidente", name: "Mme Christine Cormerais" },
      { role: "Vice-président", name: "M Pierre Avenel" },
      { role: "Trésorier", name: "Mme Sophie Corrihons" },
      { role: "Secrétaire", name: "Mme Clarisse Abo-Dib" },
    ],
  },
  en: {
    title: "OGEC presentation",
    headline: "OGEC: volunteers committed to our project",
    intro: [
      "The OGEC (Organisme de Gestion de l'Enseignement Catholique) is composed of volunteers — current or former parents — who meet in Board of Directors sessions 5 to 6 times a year. Its role: to provide the school with the means to implement its pedagogical, educational and pastoral project. As the school is under a state contract, teachers are paid by the Éducation Nationale and the curriculum follows its directives.",
    ],
    domainsTitle: "The OGEC operates in the following areas",
    domains: [
      {
        name: "Social",
        description:
          "As employer of staff not covered by the State: administrative and educational staff (ASEM, teaching assistant).",
      },
      {
        name: "Economic and financial",
        description:
          "It collects contributions from families and local authorities and manages expenditure.",
      },
      {
        name: "Legal",
        description: "As the legal entity of the establishment.",
      },
      {
        name: "Property management",
        description:
          "For the routine upkeep of the premises as well as renovation, entirely at the OGEC's expense.",
      },
    ],
    closingText:
      "The OGEC ensures rigorous and prudent management of the school in order to keep family contributions at a reasonable level while providing a suitable working environment for our children and staff.",
    compositionTitle: "OGEC board — École de l'Assomption",
    members: [
      { role: "President", name: "Mme Christine Cormerais" },
      { role: "Vice-president", name: "M Pierre Avenel" },
      { role: "Treasurer", name: "Mme Sophie Corrihons" },
      { role: "Secretary", name: "Mme Clarisse Abo-Dib" },
    ],
  },
};

export default async function OgecPresentationPage({
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
          <p className="mt-3 font-display text-lg italic text-muted">{c.headline}</p>
        </div>
      </div>

      <div className="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8 space-y-10">
        {/* Intro */}
        <div className="space-y-5">
          {c.intro.map((p, i) => (
            <p key={i} className="text-base leading-relaxed text-[#2E2C31]">
              {p}
            </p>
          ))}
        </div>

        {/* Domains */}
        <div>
          <h2 className="mb-5 font-display text-xl font-semibold text-primary">
            {c.domainsTitle}
          </h2>
          <div className="grid gap-4 sm:grid-cols-2">
            {c.domains.map((d, i) => (
              <div key={i} className="rounded-2xl border border-border bg-white p-5 shadow-sm">
                <p className="mb-1.5 font-semibold text-primary">{d.name}</p>
                <p className="text-sm leading-relaxed text-[#2E2C31]">{d.description}</p>
              </div>
            ))}
          </div>
        </div>

        <p className="text-base leading-relaxed text-[#2E2C31]">{c.closingText}</p>

        {/* Composition */}
        <div className="rounded-2xl border border-border bg-surface p-6">
          <h2 className="mb-5 font-display text-lg font-semibold text-primary">
            {c.compositionTitle}
          </h2>
          <ul className="space-y-2">
            {c.members.map((m, i) => (
              <li key={i} className="flex gap-2 text-sm text-[#2E2C31]">
                <span className="font-semibold text-primary">{m.role} :</span>
                {m.name}
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  );
}
