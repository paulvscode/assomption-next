import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const ogecPresentation = defineType({
  name: "ogecPresentation",
  title: "OGEC — Présentation",
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
    prepare: () => ({ title: "OGEC — Présentation" }),
  },
});
