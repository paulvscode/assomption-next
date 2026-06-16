type Locale = "fr" | "en";

const content: Record<Locale, { title: string; paragraphs: string[] }> = {
  fr: {
    title: "Programme anglais",
    paragraphs: [
      "L'anglais est un apprentissage fondamental à l'école de l'Assomption. C'est pourquoi, dès la Petite Section, Laëtitia, intervenante en anglais, prend en charge les enfants. Elle intervient une, puis deux fois par semaine pendant 30, puis 45 minutes.",
      "Les activités s'effectuent par demi-classe. Les groupes étant peu nombreux, les élèves peuvent s'exprimer fréquemment et facilement. Le travail est d'abord centré sur l'oral (imprégnation de la langue, de son accent tonique, de sa syntaxe…) à travers des activités vivantes et variées.",
      "Quand ils sont aptes à le faire, les enfants abordent l'écrit en lien avec différents thèmes. Ils acquièrent donc, en plus d'une aisance à l'oral, de bonnes bases à l'écrit. Ainsi, beaucoup d'élèves choisissent de poursuivre leur scolarité dans une 6e avec option spécifique en anglais.",
      "L'anglais est aussi développé à l'école dans le cadre de la classe anglophone, qui s'adresse à des enfants ayant un profil spécifique.",
    ],
  },
  en: {
    title: "English programme",
    paragraphs: [
      "English is a fundamental subject at the École de l'Assomption. From nursery onwards, Laëtitia, the English specialist, takes charge of the children. She intervenes once, then twice a week for 30, then 45 minutes.",
      "Activities take place in half-classes. With small group sizes, pupils can express themselves frequently and easily. The work is first centred on oral skills (language immersion, stress patterns, syntax…) through lively and varied activities.",
      "When ready, children approach written English linked to different themes. They thus acquire, alongside oral fluency, solid written foundations. As a result, many pupils choose to continue their schooling in a secondary school with a specific English option.",
      "English is also developed at school through the English-language class, which caters to children with a specific profile.",
    ],
  },
};

export default async function EnglishProgramPage({
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
