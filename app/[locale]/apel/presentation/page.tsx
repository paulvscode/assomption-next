type Locale = "fr" | "en";

const content: Record<
  Locale,
  {
    title: string;
    intro: string[];
    compositionTitle: string;
    members: { role: string; name: string }[];
    contactTitle: string;
    contactItems: string[];
    signupText: string;
    signupLink: string;
    closing: string;
  }
> = {
  fr: {
    title: "Présentation de l'APEL",
    intro: [
      "L'APEL (Association des Parents d'élèves de l'Enseignement Libre) est une association loi 1901. Ses membres sont bénévoles.",
      "Son rôle est de favoriser les rencontres entre les parents, de les accompagner, de les représenter, notamment dans les conseils d'établissement. L'APEL est l'interlocuteur du chef d'établissement.",
      "Les membres de l'APEL se réunissent régulièrement. Ils préparent les manifestations qui ponctuent l'année scolaire. Ils soutiennent les projets éducatifs de l'école. Ils travaillent en lien avec le chef d'établissement.",
      "L'APEL a une représentation départementale (UDAPEL) et nationale (UNAPEL). Pour davantage d'informations, rendez-vous sur le site http://www.apel.fr",
      "Lors de l'inscription, la plupart des familles adhèrent à l'association. Elles sont invitées à l'Assemblée Générale annuelle. Elles reçoivent à domicile la revue « Famille et Éducation » qui traite de questions diverses au travers de reportages, conseils de spécialistes, témoignages.",
    ],
    compositionTitle: "Composition de l'APEL",
    members: [
      { role: "Présidente", name: "Laurence Glovert" },
      { role: "Présidente adjointe", name: "Charlène Guilbert" },
      { role: "Trésorière", name: "Florentine Avenel" },
      { role: "Trésorière adjointe", name: "Lisa-Marie Arfelli" },
      { role: "Secrétaire", name: "Amel Luu" },
      { role: "Secrétaire adjointe", name: "Stéphanie Peillex" },
    ],
    contactTitle: "Nous contacter",
    contactItems: [
      "Par courrier : la boîte aux lettres de l'APEL est située dans le hall principal, avant la porte d'entrée du secrétariat.",
      "Par mail : apel.assomption76@gmail.com",
    ],
    signupText:
      "Pour être contacté(e) et informé(e) de tous les événements qui jalonnent la vie de vos enfants au sein de l'école, laissez-nous votre adresse mail :",
    signupLink: "S'inscrire à la liste de diffusion",
    closing: "L'équipe de l'APEL est à votre écoute.",
  },
  en: {
    title: "APEL presentation",
    intro: [
      "The APEL (Association des Parents d'élèves de l'Enseignement Libre) is a non-profit association. Its members are volunteers.",
      "Its role is to foster connections between parents, support and represent them, particularly in school governing bodies. The APEL is the main point of contact for the head teacher.",
      "APEL members meet regularly. They organise events that punctuate the school year, support the school's educational projects, and work closely with the head teacher.",
      "The APEL has a departmental (UDAPEL) and national (UNAPEL) presence. For more information, visit http://www.apel.fr",
      "At enrolment, most families join the association. They are invited to the Annual General Meeting and receive the 'Famille et Éducation' magazine at home, covering various topics through features, expert advice and testimonials.",
    ],
    compositionTitle: "APEL board",
    members: [
      { role: "President", name: "Laurence Glovert" },
      { role: "Deputy president", name: "Charlène Guilbert" },
      { role: "Treasurer", name: "Florentine Avenel" },
      { role: "Deputy treasurer", name: "Lisa-Marie Arfelli" },
      { role: "Secretary", name: "Amel Luu" },
      { role: "Deputy secretary", name: "Stéphanie Peillex" },
    ],
    contactTitle: "Contact us",
    contactItems: [
      "By post: the APEL letterbox is located in the main hall, before the entrance to the secretary's office.",
      "By email: apel.assomption76@gmail.com",
    ],
    signupText:
      "To be kept informed of all events in your children's school life, leave us your email address:",
    signupLink: "Subscribe to the mailing list",
    closing: "The APEL team is here to listen to you.",
  },
};

export default async function ApelPresentationPage({
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
        {/* Intro paragraphs */}
        <div className="space-y-5">
          {c.intro.map((p, i) => (
            <p key={i} className="text-base leading-relaxed text-[#2E2C31]">
              {p}
            </p>
          ))}
        </div>

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

        {/* Contact */}
        <div>
          <h2 className="mb-4 font-display text-lg font-semibold text-primary">
            {c.contactTitle}
          </h2>
          <ul className="space-y-3">
            {c.contactItems.map((item, i) => (
              <li key={i} className="flex items-start gap-3 text-sm leading-relaxed text-[#2E2C31]">
                <span className="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-primary/60" />
                {item}
              </li>
            ))}
          </ul>
        </div>

        {/* Signup */}
        <div className="rounded-2xl border border-primary/20 bg-white p-6">
          <p className="mb-4 text-sm leading-relaxed text-[#2E2C31]">{c.signupText}</p>
          <a
            href="http://eepurl.com/ckpGAL"
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-2 rounded-full bg-primary px-6 py-2.5 text-sm font-semibold text-white transition-opacity hover:opacity-90"
          >
            {c.signupLink} →
          </a>
        </div>

        <p className="text-sm italic text-muted">{c.closing}</p>
      </div>
    </div>
  );
}
