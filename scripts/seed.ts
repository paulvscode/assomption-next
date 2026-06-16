/**
 * Seed script — pre-fills Sanity documents with the site's default content.
 * Safe to run multiple times: uses setIfMissing, so existing values are never overwritten.
 *
 * Usage:
 *   1. Add SANITY_API_WRITE_TOKEN to your .env.local
 *      (get it from sanity.io → project → API → Tokens → Add Editor token)
 *   2. npm run seed
 */

import { config } from "dotenv";
config({ path: ".env.local" });
import { createClient } from "@sanity/client";

const client = createClient({
  projectId:  process.env.NEXT_PUBLIC_SANITY_PROJECT_ID!,
  dataset:    process.env.NEXT_PUBLIC_SANITY_DATASET ?? "production",
  apiVersion: process.env.NEXT_PUBLIC_SANITY_API_VERSION ?? "2024-01-01",
  token:      process.env.SANITY_API_WRITE_TOKEN,
  useCdn:     false,
});

// ── Portable Text helpers ─────────────────────────────────────────────────────
let _k = 0;
const key = () => `k${++_k}`;

function pt(...texts: string[]) {
  return texts.map((text) => ({
    _type: "block",
    _key: key(),
    style: "normal",
    markDefs: [],
    children: [{ _type: "span", _key: key(), text, marks: [] }],
  }));
}

// ── Upsert helper ─────────────────────────────────────────────────────────────
async function seed(id: string, type: string, data: Record<string, unknown>) {
  await client.createIfNotExists({ _id: id, _type: type });
  await client.patch(id).setIfMissing(data).commit();
  console.log(`✓  ${id}`);
}

// ── Main ──────────────────────────────────────────────────────────────────────
async function main() {
  // ── Landing page ────────────────────────────────────────────────────────────
  await seed("landing", "landing", {
    tagline: {
      fr: "Le Havre · École catholique",
      en: "Le Havre · Catholic school",
    },
    h1: {
      fr: "École de l'Assomption",
      en: "École de l'Assomption",
    },
    h2: {
      fr: "Une école ouverte à l'autre",
      en: "A school open to others",
    },
    statement: {
      fr: "Située en plein cœur du Havre, l'école de l'Assomption est une école maternelle et primaire catholique sous contrat d'association avec l'État, ayant obtenu le Label « Ouverture à l'Internationale ». 300 enfants y sont accueillis dans un esprit d'ouverture et de bienveillance.",
      en: "Located in the heart of Le Havre, the École de l'Assomption is a Catholic nursery and primary school under contract with the State, having received Level 2 of the 'Ouverture à l'Internationale' Label. 300 children are welcomed here in a spirit of openness and kindness.",
    },
    cta: {
      fr: "En savoir plus",
      en: "Learn more",
    },
    ctaContact: {
      fr: "Nous contacter",
      en: "Contact us",
    },
    linksTitle: {
      fr: "Liens rapides",
      en: "Quick links",
    },
    contactTitle: {
      fr: "Nous contacter",
      en: "Contact us",
    },
    address: "32, rue Lord Kitchener\n76600 - Le Havre",
    phone:   "(+33) 02 35 43 60 68",
    email:   "dir.ec.assomption.lehavre@srec-hn.com",
    hours: {
      fr: "Lundi, mardi, jeudi, vendredi\n7h45 – 18h00",
      en: "Monday, Tuesday, Thursday, Friday\n7:45 am – 6:00 pm",
    },
  });

  // ── School presentation ──────────────────────────────────────────────────────
  await seed("schoolPresentation", "schoolPresentation", {
    title: {
      fr: "Bienvenue à l'école de l'Assomption !",
      en: "Welcome to the École de l'Assomption!",
    },
    body: {
      fr: pt(
        "Située en plein centre-ville du Havre, l'Assomption est une école maternelle et primaire catholique sous contrat d'association avec l'État ayant obtenu le niveau 2 du Label Ouverture à l'internationale. Elle accueille environ 300 enfants de la petite section au CM2, dans un esprit d'ouverture et de bienveillance.",
        "À l'école de l'Assomption, les besoins et les difficultés de chaque élève sont prises en compte. Les enseignants effectuent un suivi personnalisé des élèves, et des rencontres régulières avec les parents sont organisées. Les enfants peuvent bénéficier d'un travail de remédiation en petits groupes, effectué par l'enseignant du poste d'adaptation. Notre école compte également une ULIS (Unité Localisée pour l'Inclusion Scolaire).",
        "Forte du niveau 2 du Label « Ouverture à l'Internationale », notre école donne à l'enseignement de l'anglais une place de choix, et ce dès la petite section. En effet, tous les enfants, de la PS au CM2, apprennent l'anglais avec une intervenante attachée à l'école. Ils bénéficient d'un travail en petits groupes afin d'être dans les meilleures conditions pour cet apprentissage. Une classe anglophone d'Immersion Linguistique et Culturelle fonctionne également pour les enfants anglophones, ayant vécu à l'étranger ou se préparant à partir."
      ),
      en: pt(
        "Located in the heart of Le Havre, the Assomption is a Catholic nursery and primary school under contract with the State, having received Level 2 of the Label Ouverture à l'internationale. It welcomes around 300 children from nursery to Year 6, in a spirit of openness and kindness.",
        "At the École de l'Assomption, each pupil's needs and difficulties are taken into account. Teachers provide personalised follow-up for pupils, and regular meetings with parents are organised. Children can benefit from small-group remediation carried out by the teacher in the adaptation post. Our school also includes a ULIS unit (Unité Localisée pour l'Inclusion Scolaire).",
        "Proud of its Level 2 Label 'Ouverture à l'Internationale', our school gives English teaching a special place, from nursery onwards. All children, from PS to CM2, learn English with a specialist teacher attached to the school. They benefit from small-group work for the best learning conditions. An English-language class of Linguistic and Cultural Immersion also operates for English-speaking children who have lived abroad or are preparing to leave."
      ),
    },
    valuesTitle: {
      fr: "La communauté éducative de l'école de l'Assomption souhaite faire vivre nos valeurs au quotidien :",
      en: "The educational community of the École de l'Assomption wishes to live our values on a daily basis:",
    },
    values: [
      { _type: "value", _key: key(), fr: "Favoriser l'épanouissement de la personne", en: "Fostering personal development" },
      { _type: "value", _key: key(), fr: "Accueillir les différences",                en: "Welcoming differences" },
      { _type: "value", _key: key(), fr: "Cultiver notre ouverture sur le monde",     en: "Cultivating openness to the world" },
      { _type: "value", _key: key(), fr: "Éduquer à la solidarité et au respect",    en: "Educating towards solidarity and respect" },
    ],
  });

  console.log("\nDone. Open /studio and the fields will be pre-filled.");
}

main().catch((err) => { console.error(err); process.exit(1); });
