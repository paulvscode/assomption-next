import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const apelPresentation = defineType({
  name: "apelPresentation",
  title: "APEL — Présentation",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    localizedRichText("body", "Contenu"),
    {
      name: "coverImage",
      title: "Image",
      type: "image",
      options: { hotspot: true },
    },
  ],
  preview: {
    prepare: () => ({ title: "APEL — Présentation" }),
  },
});
