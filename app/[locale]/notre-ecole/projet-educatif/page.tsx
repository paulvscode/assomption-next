type Locale = "fr" | "en";

type Card = { title: string; items: string[] };

const content: Record<Locale, { title: string; cards: Card[] }> = {
  fr: {
    title: "Projet éducatif",
    cards: [
      {
        title: "Une école qui favorise le développement de la personne",
        items: [
          "Développer les talents, les compétences de chacun.",
          "Offrir à chaque élève la possibilité d'acquérir des connaissances solides et un raisonnement efficace.",
          "Favoriser la relation aux autres.",
          "Promouvoir le dialogue parents-enseignants pour que chacun, dans son rôle, aide l'enfant à dépasser les difficultés.",
        ],
      },
      {
        title: "Une école qui accueille les différences",
        items: [
          "Accueillir chaque enfant tel qu'il est.",
          "Adapter les situations d'apprentissage.",
          "Favoriser la connaissance de l'autre, percevoir la différence comme une richesse.",
        ],
      },
      {
        title: "Une école ouverte sur le monde",
        items: [
          "Préparer l'enfant à l'autonomie.",
          "Favoriser la curiosité au-delà de l'environnement familier.",
          "Développer des connaissances et compétences dans la langue anglaise.",
          "Sensibiliser et éduquer au monde numérique.",
        ],
      },
      {
        title: "Une école qui éduque à la solidarité et au respect",
        items: [
          "Donner des responsabilités.",
          "Développer les occasions d'échange entre enfants de classes différentes.",
          "Participer à des actions de solidarité.",
          "Vivre au quotidien les valeurs de l'Évangile.",
        ],
      },
    ],
  },
  en: {
    title: "Educational project",
    cards: [
      {
        title: "A school that fosters personal development",
        items: [
          "Developing the talents and skills of each person.",
          "Offering each pupil the opportunity to acquire solid knowledge and effective reasoning.",
          "Fostering relationships with others.",
          "Promoting parent-teacher dialogue so that each, in their role, helps the child overcome difficulties.",
        ],
      },
      {
        title: "A school that welcomes differences",
        items: [
          "Welcoming each child as they are.",
          "Adapting learning situations.",
          "Fostering knowledge of others, perceiving difference as a richness.",
        ],
      },
      {
        title: "A school open to the world",
        items: [
          "Preparing children for autonomy.",
          "Fostering curiosity beyond the familiar environment.",
          "Developing knowledge and skills in the English language.",
          "Raising awareness of and educating about the digital world.",
        ],
      },
      {
        title: "A school that educates towards solidarity and respect",
        items: [
          "Giving responsibilities.",
          "Developing opportunities for exchange between children from different classes.",
          "Participating in solidarity actions.",
          "Living the values of the Gospel daily.",
        ],
      },
    ],
  },
};

export default async function EducationalProjectPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const c = content[(locale as Locale)] ?? content.fr;

  return (
    <div>
      <div className="border-b border-border bg-surface py-12">
        <div className="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
          <h1 className="font-display text-3xl font-bold text-primary sm:text-4xl">
            {c.title}
          </h1>
        </div>
      </div>

      <div className="mx-auto max-w-5xl px-4 py-14 sm:px-6 lg:px-8">
        <div className="grid gap-6 sm:grid-cols-2">
          {c.cards.map((card, i) => (
            <div key={i} className="rounded-2xl border border-border bg-white p-7 shadow-sm">
              <h2 className="mb-5 font-display text-lg font-semibold text-primary">
                {card.title}
              </h2>
              <ul className="space-y-3">
                {card.items.map((item, j) => (
                  <li
                    key={j}
                    className="flex items-start gap-3 text-sm leading-relaxed text-[#2E2C31]"
                  >
                    <span className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary/60" />
                    {item}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
