type Locale = "fr" | "en";

const content: Record<Locale, { title: string; paragraphs: string[] }> = {
  fr: {
    title: "ULIS",
    paragraphs: [
      "L'Unité Localisée pour l'Inclusion Scolaire est un dispositif collectif d'aide à la scolarisation. Il développe et consolide l'autonomie personnelle, sociale de l'enfant, ainsi que ses apprentissages et l'amélioration de sa communication. Le dispositif peut accueillir 14 élèves, âgés de 6 à 11 ans, en situation de handicap.",
      "Les élèves sont orientés sur décision de la MDPH, puis sont inscrits dans une classe de l'école, en fonction de leur Projet Personnalisé de Scolarisation. Ils participent aux activités proposées de leur classe et leur emploi du temps est aménagé en début d'année en fonction de leurs besoins spécifiques et peut être réaménagé durant l'année, selon les compétences ciblées. Un élève notifié en unité localisée pour l'inclusion scolaire profite d'heures dans sa classe avec son professeur et l'accompagnement de l'AESH-co du dispositif, s'il y a, ou d'un AESH-i ou encore d'un AESH-mut si notifiée.",
      "Il bénéficie d'heures avec la coordonnatrice, au sein du dispositif et/ou dans sa classe. Le professeur et la coordonnatrice travaillent ensemble en co-intervention et/ou en co-enseignement. Cette émulation d'équipe pédagogique permet d'anticiper les adaptations et favorise l'appartenance de l'élève à son groupe classe.",
      "L'unité possède de nombreux matériels pédagogiques et pratique la ludopédagogie. L'espace y est flexible pour adapter l'environnement aux besoins des élèves. L'élève peut ainsi retrouver sa concentration et sa motivation dans ses apprentissages. Afin d'apporter de l'affection et/ou du réconfort aux enfants, des médiateurs animaliers complètent le dispositif en faisant preuve de calme et d'amitié.",
      "Le dispositif ULIS de l'Assomption est basé sur une pédagogie active et positive, afin que chaque enfant puisse s'épanouir à l'école.",
    ],
  },
  en: {
    title: "ULIS",
    paragraphs: [
      "The ULIS (Unité Localisée pour l'Inclusion Scolaire) is a collective support unit for school inclusion. It develops and consolidates each child's personal and social autonomy, as well as their learning and communication. The unit can accommodate 14 pupils aged 6 to 11 with disabilities.",
      "Pupils are referred by the MDPH and enrolled in a class according to their Personal Schooling Project. They participate in their class activities, with a timetable tailored at the start of the year to meet their specific needs — adjustable throughout the year based on targeted skills. Each pupil benefits from time in their class with their teacher and the support of the AESH-co, AESH-i, or AESH-mut as notified.",
      "Pupils also benefit from sessions with the ULIS coordinator, within the unit and/or in the classroom. The teacher and coordinator work together through co-intervention and/or co-teaching. This team approach anticipates adaptations and reinforces the pupil's sense of belonging to their class group.",
      "The unit has a wide range of teaching materials and uses play-based pedagogy. The space is flexible to adapt the environment to pupils' needs, helping children regain concentration and motivation in their learning. Animal mediators also complete the unit, providing affection and comfort through calm and friendly interaction.",
      "The ULIS at the Assomption is based on active and positive pedagogy, so that every child can flourish at school.",
    ],
  },
};

export default async function UlisPage({
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
