import { defineType, defineField } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const schoolPresentation = defineType({
  name: "schoolPresentation",
  title: "Présentation de l'école",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    localizedRichText("body", "Contenu principal (paragraphes)"),
    localizedString("valuesTitle", "Titre — Nos valeurs"),
    defineField({
      name: "values",
      title: "Valeurs (liste)",
      type: "array",
      of: [
        {
          type: "object",
          name: "value",
          title: "Valeur",
          fields: [
            { name: "fr", title: "Valeur (FR)", type: "string" },
            { name: "en", title: "Valeur (EN)", type: "string" },
          ],
          preview: { select: { title: "fr" } },
        },
      ],
    }),
    defineField({ name: "image1", title: "Image 1", type: "image", options: { hotspot: true } }),
    defineField({ name: "image2", title: "Image 2", type: "image", options: { hotspot: true } }),
    defineField({ name: "image3", title: "Image 3", type: "image", options: { hotspot: true } }),
    defineField({ name: "image4", title: "Image 4", type: "image", options: { hotspot: true } }),
  ],
  preview: {
    prepare: () => ({ title: "Présentation de l'école" }),
  },
});
