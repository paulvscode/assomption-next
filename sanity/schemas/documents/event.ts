import { defineType } from "sanity";
import { localizedString, localizedText, localizedRichText } from "../helpers/localized";

export const event = defineType({
  name: "event",
  title: "Événement",
  type: "document",
  fields: [
    localizedString("title", "Titre"),
    {
      name: "startDate",
      title: "Date de début",
      type: "datetime",
      validation: (r) => r.required(),
    },
    {
      name: "endDate",
      title: "Date de fin",
      type: "datetime",
    },
    localizedString("location", "Lieu"),
    {
      name: "coverImage",
      title: "Image",
      type: "image",
      options: { hotspot: true },
    },
    localizedText("excerpt", "Résumé court"),
    localizedRichText("body", "Description complète"),
  ],
  orderings: [
    {
      title: "Date (prochains)",
      name: "startDateAsc",
      by: [{ field: "startDate", direction: "asc" }],
    },
  ],
  preview: {
    select: { title: "title.fr", subtitle: "startDate", media: "coverImage" },
    prepare: ({ title, subtitle, media }) => ({
      title: title || "Sans titre",
      subtitle: subtitle ? new Date(subtitle).toLocaleDateString("fr-FR") : "",
      media,
    }),
  },
});
