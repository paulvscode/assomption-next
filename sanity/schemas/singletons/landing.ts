import { defineType, defineField } from "sanity";
import { localizedString, localizedText } from "../helpers/localized";

const inGroup = <T extends { name: string }>(field: T, group: string) => ({ ...field, group });

const qlImage = (name: string, title: string, group: string) =>
  defineField({ name, title, type: "image", options: { hotspot: true }, group });

export const landing = defineType({
  name: "landing",
  title: "Page d'accueil",
  type: "document",
  groups: [
    { name: "banniere", title: "Bannière", default: true },
    { name: "actualites", title: "Actualités" },
    { name: "hero", title: "Bandeau principal" },
    { name: "liens", title: "Liens rapides" },
    { name: "contact", title: "Contact" },
  ],
  fields: [
    // ── Bannière d'annonce ────────────────────────────────────────────────
    defineField({
      name: "bannerEnabled",
      title: "Bannière active",
      type: "boolean",
      description: "Activer pour afficher la bannière en haut de la page d'accueil.",
      initialValue: false,
      group: "banniere",
    }),
    inGroup(localizedString("bannerText", "Texte de la bannière"), "banniere"),

    // ── Actualités ────────────────────────────────────────────────────────
    defineField({
      name: "newsEnabled",
      title: "Section actualités active",
      type: "boolean",
      description: "Activer pour afficher les dernières actualités sur la page d'accueil.",
      initialValue: true,
      group: "actualites",
    }),

    // ── Hero ──────────────────────────────────────────────────────────────
    inGroup(localizedString("tagline",    "Accroche (ex : Le Havre · École catholique)"), "hero"),
    inGroup(localizedString("h1",         "Titre principal — H1"), "hero"),
    inGroup(localizedString("h2",         "Sous-titre — H2"), "hero"),
    inGroup(localizedText(  "statement",  "Paragraphe d'introduction"), "hero"),
    inGroup(localizedString("cta",        "Bouton principal (ex : En savoir plus)"), "hero"),
    inGroup(localizedString("ctaContact", "Bouton contact (ex : Nous contacter)"), "hero"),
    defineField({ name: "heroImage", title: "Image hero", type: "image", options: { hotspot: true }, group: "hero" }),

    // ── Liens rapides ─────────────────────────────────────────────────────
    inGroup(localizedString("linksTitle", "Titre — Liens rapides"), "liens"),
    qlImage("qlEquipe",              "Lien rapide — L'équipe", "liens"),
    qlImage("qlProjetEducatif",      "Lien rapide — Le projet éducatif", "liens"),
    qlImage("qlProjetEtablissement", "Lien rapide — Le projet d'établissement", "liens"),
    qlImage("qlProjetPastoral",      "Lien rapide — Le projet pastoral", "liens"),
    qlImage("qlAnglais",             "Lien rapide — L'anglais", "liens"),
    qlImage("qlClassesSpecifiques",  "Lien rapide — Les classes spécifiques", "liens"),

    // ── Contact ───────────────────────────────────────────────────────────
    inGroup(localizedString("contactTitle", "Titre — Section contact"), "contact"),
    defineField({ name: "address", title: "Adresse",    type: "text",   rows: 2, group: "contact" }),
    defineField({ name: "phone",   title: "Téléphone",  type: "string", group: "contact" }),
    defineField({ name: "email",   title: "Email",      type: "string", group: "contact" }),
    inGroup(localizedString("hours", "Horaires d'ouverture"), "contact"),
  ],
  preview: {
    prepare: () => ({ title: "Page d'accueil" }),
  },
});
