import { defineType, defineField } from "sanity";
import { localizedString, localizedText } from "../helpers/localized";

export const professorsPage = defineType({
  name: "professorsPage",
  title: "Page Équipe enseignante",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    localizedText("intro", "Introduction"),
    defineField({
      name: "teamPhoto",
      title: "Photo de l'équipe",
      type: "image",
      options: { hotspot: true },
    }),
  ],
  preview: {
    prepare: () => ({ title: "Page Équipe enseignante" }),
  },
});
