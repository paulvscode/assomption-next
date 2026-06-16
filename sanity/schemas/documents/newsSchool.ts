import { defineType } from "sanity";
import { localizedString, localizedText, localizedRichText } from "../helpers/localized";

export const newsSchool = defineType({
  name: "newsSchool",
  title: "Actualité école",
  type: "document",
  fields: [
    localizedString("title", "Titre"),
    {
      name: "publishedAt",
      title: "Date de publication",
      type: "date",
      options: { dateFormat: "DD/MM/YYYY" },
      validation: (r) => r.required(),
    },
    {
      name: "coverImage",
      title: "Image",
      type: "image",
      options: { hotspot: true },
    },
    localizedText("excerpt", "Résumé court"),
    localizedRichText("body", "Contenu"),
  ],
  orderings: [
    {
      title: "Date (plus récent)",
      name: "publishedAtDesc",
      by: [{ field: "publishedAt", direction: "desc" }],
    },
  ],
  preview: {
    select: { title: "title.fr", subtitle: "publishedAt", media: "coverImage" },
    prepare: ({ title, subtitle, media }) => ({
      title: title || "Sans titre",
      subtitle,
      media,
    }),
  },
});
