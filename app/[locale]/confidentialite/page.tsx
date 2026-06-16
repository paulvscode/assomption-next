type Locale = "fr" | "en";

const content: Record<Locale, { title: string; sections: { heading: string; body: string }[] }> = {
  fr: {
    title: "Politique de confidentialité",
    sections: [
      {
        heading: "Responsable du traitement",
        body: `École de l'Assomption
32, rue Lord Kitchener — 76600 Le Havre
dir.ec.assomption.lehavre@srec-hn.com`,
      },
      {
        heading: "Données collectées",
        body: "Ce site ne collecte aucune donnée personnelle de manière automatique. Aucun cookie de suivi, aucun pixel de tracking, et aucun formulaire de collecte ne sont utilisés. Les seules informations présentes sur ce site sont les coordonnées de l'école, affichées à titre informatif.",
      },
      {
        heading: "Cookies",
        body: "Ce site n'utilise aucun cookie.",
      },
      {
        heading: "Hébergement des données",
        body: "Ce site est hébergé par OVH SAS (2 rue Kellermann, 59100 Roubaix, France). Les données de navigation transitent par leurs serveurs conformément à leur propre politique de confidentialité.",
      },
      {
        heading: "Vos droits (RGPD)",
        body: `Conformément au Règlement Général sur la Protection des Données (RGPD — UE 2016/679), vous disposez des droits suivants :
– Droit d'accès à vos données
– Droit de rectification
– Droit à l'effacement
– Droit d'opposition

Pour exercer ces droits, contactez-nous par email : dir.ec.assomption.lehavre@srec-hn.com`,
      },
      {
        heading: "Contact",
        body: "Pour toute question relative à la présente politique, vous pouvez nous écrire à : dir.ec.assomption.lehavre@srec-hn.com",
      },
    ],
  },
  en: {
    title: "Privacy Policy",
    sections: [
      {
        heading: "Data Controller",
        body: `École de l'Assomption
32, rue Lord Kitchener — 76600 Le Havre, France
dir.ec.assomption.lehavre@srec-hn.com`,
      },
      {
        heading: "Data Collected",
        body: "This website does not collect any personal data automatically. No tracking cookies, tracking pixels, or data collection forms are used. The only information present on this site is the school's contact details, displayed for informational purposes.",
      },
      {
        heading: "Cookies",
        body: "This website does not use any cookies.",
      },
      {
        heading: "Data Hosting",
        body: "This website is hosted by OVH SAS (2 rue Kellermann, 59100 Roubaix, France). Browsing data passes through their servers in accordance with their own privacy policy.",
      },
      {
        heading: "Your Rights (GDPR)",
        body: `Under the General Data Protection Regulation (GDPR — EU 2016/679), you have the following rights:
– Right of access to your data
– Right to rectification
– Right to erasure
– Right to object

To exercise these rights, contact us by email: dir.ec.assomption.lehavre@srec-hn.com`,
      },
      {
        heading: "Contact",
        body: "For any questions regarding this policy, you may write to us at: dir.ec.assomption.lehavre@srec-hn.com",
      },
    ],
  },
};

export default async function ConfidentialitePage({
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
