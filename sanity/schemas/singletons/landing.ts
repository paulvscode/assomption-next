import { defineType, defineField } from "sanity";
import { localizedString, localizedText } from "../helpers/localized";

const qlImage = (name: string, title: string) =>
  defineField({ name, title, type: "image", options: { hotspot: true } });

export const landing = defineType({
  name: "landing",
  title: "Page d'accueil",
  type: "document",
  fields: [
    // ── Bannière d'annonce ────────────────────────────────────────────────
    defineField({
      name: "bannerEnabled",
      title: "Bannière active",
      type: "boolean",
      description: "Activer pour afficher la bannière en haut de la page d'accueil.",
      initialValue: false,
    }),
    localizedString("bannerText", "Texte de la bannière"),

    // ── Actualités ────────────────────────────────────────────────────────
    defineField({
      name: "newsEnabled",
      title: "Section actualités active",
      type: "boolean",
      description: "Activer pour afficher les dernières actualités sur la page d'accueil.",
      initialValue: true,
    }),

    // ── Hero ──────────────────────────────────────────────────────────────
    localizedString("tagline",    "Accroche (ex : Le Havre · École catholique)"),
    localizedString("h1",         "Titre principal — H1"),
    localizedString("h2",         "Sous-titre — H2"),
    localizedText(  "statement",  "Paragraphe d'introduction"),
    localizedString("cta",        "Bouton principal (ex : En savoir plus)"),
    localizedString("ctaContact", "Bouton contact (ex : Nous contacter)"),
    defineField({ name: "heroImage", title: "Image hero", type: "image", options: { hotspot: true } }),

    // ── Liens rapides ─────────────────────────────────────────────────────
    localizedString("linksTitle", "Titre — Liens rapides"),
    qlImage("qlEquipe",              "Lien rapide — L'équipe"),
    qlImage("qlProjetEducatif",      "Lien rapide — Le projet éducatif"),
    qlImage("qlProjetEtablissement", "Lien rapide — Le projet d'établissement"),
    qlImage("qlProjetPastoral",      "Lien rapide — Le projet pastoral"),
    qlImage("qlAnglais",             "Lien rapide — L'anglais"),
    qlImage("qlClassesSpecifiques",  "Lien rapide — Les classes spécifiques"),

    // ── Contact ───────────────────────────────────────────────────────────
    localizedString("contactTitle", "Titre — Section contact"),
    defineField({ name: "address", title: "Adresse",    type: "text",   rows: 2 }),
    defineField({ name: "phone",   title: "Téléphone",  type: "string" }),
    defineField({ name: "email",   title: "Email",      type: "string" }),
    localizedString("hours", "Horaires d'ouverture"),
  ],
  preview: {
    prepare: () => ({ title: "Page d'accueil" }),
  },
});
