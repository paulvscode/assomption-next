type Locale = "fr" | "en";

const content: Record<
  Locale,
  {
    title: string;
    mainStatement: string;
    sets: { paragraphs: string[] }[];
    largeText: string[];
  }
> = {
  fr: {
    title: "Mission pastorale",
    mainStatement:
      "L'école de l'Assomption accueille tout enfant dont la famille connaît et accepte le projet pastoral présenté à l'inscription.",
    sets: [
      {
        paragraphs: [
          "Notre école est un établissement catholique, sous tutelle diocésaine. Elle respecte donc les orientations données par l'Enseignement catholique et par l'évêque, Mgr Jean-Luc Brunin. Son prêtre référent est le Père Marcel Maurin. Ainsi, elle souhaite faire vivre au quotidien les valeurs de solidarité, de respect, d'écoute, en référence à l'Évangile. Dans les classes, un temps d'éveil religieux est régulièrement proposé.",
          "En maternelles, CP et CE1, les enfants découvrent et échangent grâce au parcours « Cadeaux de Dieu ».",
        ],
      },
      {
        paragraphs: [
          "Des activités concrètes, parlantes pour les plus jeunes, sont mises en place dans les classes au moyen d'un chevalet, d'un CD de chants et contes bibliques et autres supports proposés sur le site du parcours.",
        ],
      },
      {
        paragraphs: [
          "En CE2, CM1, CM2, ce temps est complété par un parcours de culture chrétienne, « Anne et Léo ». Les enfants découvrent la Bible, la vie de Jésus, les nombreuses références religieuses présentes dans notre patrimoine culturel (littérature, peinture, architecture…).",
        ],
      },
    ],
    largeText: [
      "L'année est marquée par deux temps forts : Noël et Pâques. Ces fêtes, expliquées aux enfants, sont l'occasion d'actions de solidarité au bénéfice d'associations caritatives.",
      "Une célébration de Noël rassemble tous les élèves et les familles qui le souhaitent.",
      "Un « bol de riz » est proposé à tous le Vendredi Saint, avant Pâques.",
      "Les temps d'éveil religieux permettent à certains d'approfondir leur Foi, à d'autres d'acquérir des connaissances afin de faire ensuite des choix, à d'autres encore d'effectuer des comparaisons avec une religion différente. Ce sont des moments d'échange, d'écoute, de partage, dans le respect des différences.",
      "Pour les enfants qui souhaitent approfondir la Foi chrétienne, des groupes de catéchèse paroissiale se réunissent à l'école. Ils sont ouverts aux enfants à partir du CE2. Ils permettent la préparation des sacrements tels que le baptême, la première communion.",
    ],
  },
  en: {
    title: "Pastoral mission",
    mainStatement:
      "The École de l'Assomption welcomes every child whose family knows and accepts the pastoral project presented at enrolment.",
    sets: [
      {
        paragraphs: [
          "Our school is a Catholic institution under diocesan authority. It therefore follows the guidelines given by Catholic Education and by the bishop, Mgr Jean-Luc Brunin. Its reference priest is Father Marcel Maurin. The school wishes to live out daily the values of solidarity, respect and listening, in reference to the Gospel. In the classrooms, a time of religious awakening is regularly offered.",
          "In nursery, CP and CE1, children discover and share through the 'Cadeaux de Dieu' programme.",
        ],
      },
      {
        paragraphs: [
          "Concrete, engaging activities for younger children are put in place in the classrooms through an easel, a CD of songs and biblical stories, and other materials available on the programme's website.",
        ],
      },
      {
        paragraphs: [
          "In CE2, CM1 and CM2, this time is complemented by a Christian culture programme, 'Anne et Léo'. Children discover the Bible, the life of Jesus, and the many religious references in our cultural heritage (literature, painting, architecture…).",
        ],
      },
    ],
    largeText: [
      "The year is marked by two key moments: Christmas and Easter. These celebrations, explained to the children, are an opportunity for solidarity actions benefiting charitable organisations.",
      "A Christmas celebration brings together all pupils and families who wish to attend.",
      "A 'bowl of rice' is offered to all on Good Friday, before Easter.",
      "Moments of religious awakening allow some to deepen their Faith, others to acquire knowledge in order to make informed choices, and others still to make comparisons with a different religion. They are moments of exchange, listening and sharing, in respect for differences.",
      "For children who wish to deepen their Christian Faith, parish catechism groups meet at school. They are open to children from CE2 onwards and allow preparation for sacraments such as baptism and first communion.",
    ],
  },
};

export default async function PastoralMissionPage({
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
        {/* Main statement */}
        <p className="mb-12 font-display text-xl font-semibold italic leading-relaxed text-primary sm:text-2xl">
          {c.mainStatement}
        </p>

        {/* 3 text + image sets */}
        <div className="space-y-12">
          {c.sets.map((set, i) => (
            <div key={i} className="flex flex-col gap-8 lg:flex-row">
              <div className="flex-1 space-y-4">
                {set.paragraphs.map((p, j) => (
                  <p key={j} className="text-base leading-relaxed text-[#2E2C31]">
                    {p}
                  </p>
                ))}
              </div>
              <div className="h-52 w-full shrink-0 overflow-hidden rounded-2xl bg-[#ECEAED] lg:h-auto lg:w-64" />
            </div>
          ))}
        </div>

        {/* Large text block */}
        <div className="mt-16 rounded-2xl border border-border bg-surface p-8">
          <div className="space-y-5">
            {c.largeText.map((p, i) => (
              <p key={i} className="text-base leading-relaxed text-[#2E2C31]">
                {p}
              </p>
            ))}
          </div>
        </div>

        {/* Large image placeholder */}
        <div className="mt-10 aspect-video overflow-hidden rounded-2xl bg-[#ECEAED]" />
      </div>
    </div>
  );
}
