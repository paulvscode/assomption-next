import Image from "next/image";
import { client } from "@/sanity/lib/client";
import { singletonQuery } from "@/sanity/lib/queries";
import { urlFor } from "@/sanity/lib/image";
import PortableTextRenderer from "@/components/ui/PortableTextRenderer";
import type { PortableTextBlock } from "@portabletext/types";

type Locale = "fr" | "en";

// ── Static fallback content ───────────────────────────────────────────────────
const fallback: Record<Locale, {
  title: string;
  paragraphs: string[];
  valuesTitle: string;
  values: string[];
}> = {
  fr: {
    title: "Bienvenue à l'école de l'Assomption !",
    paragraphs: [
      "Située en plein centre-ville du Havre, l'Assomption est une école maternelle et primaire catholique sous contrat d'association avec l'État ayant obtenu le niveau 2 du Label Ouverture à l'internationale. Elle accueille environ 300 enfants de la petite section au CM2, dans un esprit d'ouverture et de bienveillance.",
      "À l'école de l'Assomption, les besoins et les difficultés de chaque élève sont prises en compte. Les enseignants effectuent un suivi personnalisé des élèves, et des rencontres régulières avec les parents sont organisées. Les enfants peuvent bénéficier d'un travail de remédiation en petits groupes, effectué par l'enseignant du poste d'adaptation. Notre école compte également une ULIS (Unité Localisée pour l'Inclusion Scolaire).",
      "Forte du niveau 2 du Label « Ouverture à l'Internationale », notre école donne à l'enseignement de l'anglais une place de choix, et ce dès la petite section. En effet, tous les enfants, de la PS au CM2, apprennent l'anglais avec une intervenante attachée à l'école. Ils bénéficient d'un travail en petits groupes afin d'être dans les meilleures conditions pour cet apprentissage. Une classe anglophone d'Immersion Linguistique et Culturelle fonctionne également pour les enfants anglophones, ayant vécu à l'étranger ou se préparant à partir.",
    ],
    valuesTitle: "La communauté éducative de l'école de l'Assomption souhaite faire vivre nos valeurs au quotidien :",
    values: [
      "Favoriser l'épanouissement de la personne",
      "Accueillir les différences",
      "Cultiver notre ouverture sur le monde",
      "Éduquer à la solidarité et au respect",
    ],
  },
  en: {
    title: "Welcome to the École de l'Assomption!",
    paragraphs: [
      "Located in the heart of Le Havre, the Assomption is a Catholic nursery and primary school under contract with the State, having received Level 2 of the Label Ouverture à l'internationale. It welcomes around 300 children from nursery to Year 6, in a spirit of openness and kindness.",
      "At the École de l'Assomption, each pupil's needs and difficulties are taken into account. Teachers provide personalised follow-up for pupils, and regular meetings with parents are organised. Children can benefit from small-group remediation carried out by the teacher in the adaptation post. Our school also includes a ULIS unit (Unité Localisée pour l'Inclusion Scolaire).",
      "Proud of its Level 2 Label 'Ouverture à l'Internationale', our school gives English teaching a special place, from nursery onwards. All children, from PS to CM2, learn English with a specialist teacher attached to the school. They benefit from small-group work for the best learning conditions. An English-language class of Linguistic and Cultural Immersion also operates for English-speaking children who have lived abroad or are preparing to leave.",
    ],
    valuesTitle: "The educational community of the École de l'Assomption wishes to live our values on a daily basis:",
    values: [
      "Fostering personal development",
      "Welcoming differences",
      "Cultivating openness to the world",
      "Educating towards solidarity and respect",
    ],
  },
};

// ── Page ─────────────────────────────────────────────────────────────────────
export default async function SchoolPresentationPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const f = fallback[(locale as Locale)] ?? fallback.fr;
  const l = locale as Locale;

  const page = await client.fetch(singletonQuery("schoolPresentation"));

  const title       = (page?.title?.[l] as string | undefined)?.trim()       || f.title;
  const valuesTitle = (page?.valuesTitle?.[l] as string | undefined)?.trim() || f.valuesTitle;

  // Rich text body: use Sanity blocks if present, otherwise fall back to plain paragraphs
  const bodyBlocks = page?.body?.[l] as PortableTextBlock[] | undefined;
  const hasBody    = Array.isArray(bodyBlocks) && bodyBlocks.length > 0;

  // Values list: Sanity array of { fr, en } objects, else static strings
  type SanityValue = { fr?: string; en?: string };
  const sanityValues = page?.values as SanityValue[] | undefined;
  const values: string[] =
    Array.isArray(sanityValues) && sanityValues.length > 0
      ? sanityValues.map((v) => v[l] ?? v.fr ?? "").filter(Boolean)
      : f.values;

  // Images
  const imgFields = ["image1", "image2", "image3", "image4"] as const;
  const images = imgFields.map((field) =>
    page?.[field] ? urlFor(page[field]).width(600).height(600).url() : null
  );

  return (
    <div>
      {/* Page header */}
      <div className="border-b border-border bg-surface py-12">
        <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
          <h1 className="font-display text-3xl font-bold text-primary sm:text-4xl">{title}</h1>
        </div>
      </div>

      <div className="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">

        {/* Body — rich text from Sanity or plain fallback paragraphs */}
        {hasBody ? (
          <PortableTextRenderer value={bodyBlocks!} />
        ) : (
          <div className="space-y-6">
            {f.paragraphs.map((p, i) => (
              <p key={i} className="text-base leading-relaxed text-[#2E2C31] sm:text-lg">{p}</p>
            ))}
          </div>
        )}

        {/* Values */}
        <div className="mt-12 rounded-2xl border border-border bg-surface p-8">
          <h2 className="mb-5 font-display text-lg font-semibold text-primary">{valuesTitle}</h2>
          <ul className="space-y-3">
            {values.map((v, i) => (
              <li key={i} className="flex items-start gap-3 text-[#2E2C31]">
                <span className="mt-2 h-2 w-2 shrink-0 rounded-full bg-primary" />
                {v}
              </li>
            ))}
          </ul>
        </div>

        {/* Images */}
        <div className="mt-12 grid grid-cols-2 gap-4 lg:grid-cols-4">
          {images.map((url, i) => (
            <div key={i} className="relative aspect-square overflow-hidden rounded-xl bg-[#ECEAED]">
              {url && (
                <Image src={url} alt="" fill className="object-cover" />
              )}
            </div>
          ))}
        </div>

      </div>
    </div>
  );
}
