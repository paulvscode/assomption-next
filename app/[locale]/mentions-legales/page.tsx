type Locale = "fr" | "en";

const content: Record<Locale, { title: string; sections: { heading: string; body: string }[] }> = {
  fr: {
    title: "Mentions légales",
    sections: [
      {
        heading: "Éditeur du site",
        body: `Nom : École de l'Assomption
Statut : École catholique privée sous contrat d'association avec l'État
Adresse : 32, rue Lord Kitchener — 76600 Le Havre
Téléphone : 02 35 43 60 68
Email : dir.ec.assomption.lehavre@srec-hn.com`,
      },
      {
        heading: "Directeur de la publication",
        body: "Le directeur de l'École de l'Assomption",
      },
      {
        heading: "Hébergeur",
        body: `OVH SAS
2 rue Kellermann — 59100 Roubaix
France
www.ovh.com`,
      },
      {
        heading: "Propriété intellectuelle",
        body: "L'ensemble des contenus présents sur ce site (textes, images, logos, graphismes) est la propriété exclusive de l'École de l'Assomption, sauf mention contraire. Toute reproduction, même partielle, est interdite sans autorisation écrite préalable.",
      },
      {
        heading: "Responsabilité",
        body: "L'École de l'Assomption s'efforce d'assurer l'exactitude et la mise à jour des informations diffusées sur ce site. Elle se réserve le droit de corriger ou de modifier le contenu à tout moment et sans préavis. L'école ne saurait être tenue responsable de l'utilisation faite des informations disponibles sur le site.",
      },
    ],
  },
  en: {
    title: "Legal Notice",
    sections: [
      {
        heading: "Publisher",
        body: `Name: École de l'Assomption
Status: Private Catholic school under contract with the French State
Address: 32, rue Lord Kitchener — 76600 Le Havre, France
Phone: +33 2 35 43 60 68
Email: dir.ec.assomption.lehavre@srec-hn.com`,
      },
      {
        heading: "Publication Director",
        body: "The director of École de l'Assomption",
      },
      {
        heading: "Hosting Provider",
        body: `OVH SAS
2 rue Kellermann — 59100 Roubaix
France
www.ovh.com`,
      },
      {
        heading: "Intellectual Property",
        body: "All content on this website (texts, images, logos, graphics) is the exclusive property of École de l'Assomption, unless otherwise stated. Any reproduction, even partial, is prohibited without prior written permission.",
      },
      {
        heading: "Liability",
        body: "École de l'Assomption strives to ensure the accuracy and currency of the information published on this site. It reserves the right to correct or modify content at any time without notice. The school cannot be held liable for any use made of the information available on the site.",
      },
    ],
  },
};

export default async function MentionsLegalesPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const c = content[(locale as Locale)] ?? content.fr;

  return (
    <div className="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
      <h1 className="mb-12 font-display text-3xl font-bold text-primary sm:text-4xl">
        {c.title}
      </h1>

      <div className="space-y-10">
        {c.sections.map((section) => (
          <div key={section.heading}>
            <h2 className="mb-3 font-display text-lg font-semibold text-primary">
              {section.heading}
            </h2>
            <p className="whitespace-pre-line text-sm leading-relaxed text-[#2E2C31]/80">
              {section.body}
            </p>
          </div>
        ))}
      </div>
    </div>
  );
}
