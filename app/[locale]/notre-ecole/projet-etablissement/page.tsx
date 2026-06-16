type Locale = "fr" | "en";

type ListSection = { title?: string; items: string[] };
type Card = {
  title: string;
  description: string;
  date: string;
  sections: ListSection[];
};

const content: Record<Locale, { title: string; intro: string; cards: Card[] }> = {
  fr: {
    title: "Projet d'établissement",
    intro:
      "Le projet d'établissement concrétise les choix pédagogiques et éducatifs au travers de projets d'école, de cycle, de classe.",
    cards: [
      {
        title: "Une école qui favorise le développement de la personne",
        description:
          "L'équipe éducative souhaite faire de l'école de l'Assomption un lieu accueillant, à l'écoute de chacun.",
        date: "2024 - 2025",
        sections: [
          {
            items: [
              "Rénovation de la classe de CE1.",
              "Équipement des classes : changement des bureaux et des chaises de la classe de CM1.",
              "Poursuite de l'utilisation de Class Dojo, lien entre la famille et l'école.",
              "Développement de la communication par la refonte du site internet de l'école.",
            ],
          },
        ],
      },
      {
        title: "Une école qui accueille les différences",
        description:
          "L'équipe pédagogique souhaite offrir un suivi particulier à chaque enfant. Les enseignants reçoivent régulièrement les parents lors de réunions collectives ou d'entretiens individuels. Les enfants qui rencontrent des difficultés dans les apprentissages peuvent bénéficier d'une prise en charge en petits groupes par l'enseignant du poste d'adaptation (ou poste ASH). Enfin, une douzaine d'élèves sont accueillis en ULIS par une enseignante spécialisée.",
        date: "2024 - 2025",
        sections: [
          {
            items: [
              "20mn de lecture par jour proposées aux enfants de CP au 1er trimestre pour bien démarrer en lecture.",
              "Aide aux devoirs pour les élèves de primaire proposée par les enseignants.",
              "Poursuite d'un temps d'APC hebdomadaire dans tous les cycles pour travailler la compréhension de lecture.",
              "Inclusion des enfants d'ULIS dans les classes « ordinaires ».",
            ],
          },
        ],
      },
      {
        title: "Une école ouverte sur le monde",
        description:
          "Des classes de découverte sont organisées en cycle 2 et en cycle 3. L'anglais est développé et mis en valeur à l'école de l'Assomption : tous les élèves, de la PS au CM2, participent à des cours d'anglais dispensés par un intervenant en demi-groupes-classes. La Classe anglophone d'Immersion Linguistique et Culturelle prend en charge, par petits groupes, des enfants anglophones ou se préparant à partir vivre en pays anglophone.",
        date: "2024 - 2025",
        sections: [
          {
            items: [
              "Classe de découverte en Bretagne en juin 2025 pour les CE1 et CE2.",
              "Les élèves des classes élémentaires sont pris en charge chaque semaine en demi-groupes par une intervenante en informatique.",
              "Participation de la classe de CE1 au projet « Little Planet Fraternity » : échanges en visio avec une école au Kenya et une en Haïti.",
              "Proposition d'initiation au mandarin sur les temps périscolaires.",
            ],
          },
        ],
      },
      {
        title: "Une école qui éduque à la solidarité et au respect",
        description:
          "Notre école souhaite éduquer à la solidarité et au respect, se référant aux valeurs de l'enseignement catholique. Cela se traduit autant que possible dans tous les aspects de la vie quotidienne, ainsi que dans les moments de culture chrétienne et d'éveil religieux animés par les enseignants dans les classes.",
        date: "2024 - 2025",
        sections: [
          {
            title: "Culture religieuse :",
            items: [
              "Parcours « Cadeaux de Dieu » en maternelle, CP et CE1 ; parcours « Anne et Léo » en CE2, CM1, CM2.",
              "Catéchisme à l'école à partir du CE2 et éveil à la foi de la Grande Section au CE1, sur inscription.",
              "Préparation de « temps forts » : Nativité de Marie, interventions du Père Maurin dans les classes, célébrations de Noël et Pâques.",
            ],
          },
          {
            title: "Solidarité / prévention :",
            items: [
              "Participation à des actions de solidarité en faveur d'associations : chiens-guides, Resto du cœur, Secours Catholique…",
              "Formation des enseignants à la lutte contre le harcèlement.",
            ],
          },
        ],
      },
    ],
  },
  en: {
    title: "School project",
    intro:
      "The school project translates educational and pedagogical choices into school, cycle and class projects.",
    cards: [
      {
        title: "A school that fosters personal development",
        description:
          "The teaching team wishes to make the École de l'Assomption a welcoming place, attentive to everyone.",
        date: "2024 - 2025",
        sections: [
          {
            items: [
              "Renovation of the CE1 classroom.",
              "Classroom equipment: replacement of desks and chairs in the CM1 classroom.",
              "Continued use of Class Dojo as a link between families and school.",
              "Development of communications through a redesigned school website.",
            ],
          },
        ],
      },
      {
        title: "A school that welcomes differences",
        description:
          "The teaching team wishes to offer personalised support to each child. Teachers regularly meet parents in collective meetings or individual interviews. Children who encounter learning difficulties can receive small-group support from the teacher in the adaptation post (ASH). Around twelve pupils are also welcomed in ULIS by a specialist teacher.",
        date: "2024 - 2025",
        sections: [
          {
            items: [
              "20 minutes of reading per day for Year 1 children during the first term to get them started in reading.",
              "Homework help for primary pupils offered by teachers.",
              "Continued weekly APC time across all cycles to work on reading comprehension.",
              "Inclusion of ULIS children in 'regular' classes.",
            ],
          },
        ],
      },
      {
        title: "A school open to the world",
        description:
          "Discovery trips are organised in Cycle 2 and Cycle 3. English is developed at the École de l'Assomption: all pupils, from PS to CM2, take part in English lessons given by a specialist in half-class groups. The English-language class of Linguistic and Cultural Immersion supports English-speaking children or those preparing to live in an English-speaking country, in small groups.",
        date: "2024 - 2025",
        sections: [
          {
            items: [
              "Discovery trip to Brittany in June 2025 for CE1 and CE2.",
              "Primary class pupils receive weekly half-group IT lessons from a specialist.",
              "Participation of the CE1 class in the 'Little Planet Fraternity' project: video exchanges with a school in Kenya and one in Haiti.",
              "Introduction to Mandarin offered as an extra-curricular activity.",
            ],
          },
        ],
      },
      {
        title: "A school that educates towards solidarity and respect",
        description:
          "Our school wishes to educate towards solidarity and respect, drawing on the values of Catholic education. This is reflected as much as possible in all aspects of daily life, as well as in moments of Christian culture and religious awakening led by teachers in the classroom.",
        date: "2024 - 2025",
        sections: [
          {
            title: "Religious culture:",
            items: [
              "'Cadeaux de Dieu' programme in nursery, CP and CE1; 'Anne et Léo' programme in CE2, CM1, CM2.",
              "Catechism at school from CE2, and religious awakening from Grande Section to CE1, on registration.",
              "Preparation of key moments: Nativity of Mary, visits by Father Maurin to classes, Christmas and Easter celebrations.",
            ],
          },
          {
            title: "Solidarity / prevention:",
            items: [
              "Participation in solidarity actions for associations: guide dogs, Resto du cœur, Secours Catholique…",
              "Teacher training in anti-bullying.",
            ],
          },
        ],
      },
    ],
  },
};

export default async function SchoolProjectPage({
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
          <p className="mt-4 max-w-3xl text-base text-muted">{c.intro}</p>
        </div>
      </div>

      <div className="mx-auto max-w-5xl px-4 py-14 sm:px-6 lg:px-8 space-y-10">
        {c.cards.map((card, i) => {
          const isEven = i % 2 === 0;
          return (
            <div
              key={i}
              className={`flex flex-col gap-8 lg:flex-row ${isEven ? "" : "lg:flex-row-reverse"}`}
            >
              {/* Text side */}
              <div className="flex-1 rounded-2xl border border-border bg-white p-8 shadow-sm">
                <span className="mb-3 inline-block rounded-full border border-border bg-surface px-3 py-1 text-xs font-semibold uppercase tracking-widest text-muted">
                  {card.date}
                </span>
                <h2 className="mb-4 font-display text-xl font-bold text-primary">
                  {card.title}
                </h2>
                <p className="mb-6 text-sm leading-relaxed text-[#2E2C31]">
                  {card.description}
                </p>
                {card.sections.map((section, si) => (
                  <div key={si} className={si > 0 ? "mt-4" : ""}>
                    {section.title && (
                      <p className="mb-2 text-sm font-semibold text-primary">{section.title}</p>
                    )}
                    <ul className="space-y-2">
                      {section.items.map((item, ii) => (
                        <li
                          key={ii}
                          className="flex items-start gap-2.5 text-sm leading-relaxed text-[#2E2C31]"
                        >
                          <span className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary/60" />
                          {item}
                        </li>
                      ))}
                    </ul>
                  </div>
                ))}
              </div>
              {/* Image placeholder */}
              <div className="h-64 w-full shrink-0 overflow-hidden rounded-2xl bg-[#ECEAED] lg:h-auto lg:w-72" />
            </div>
          );
        })}
      </div>
    </div>
  );
}
