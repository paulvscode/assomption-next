import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const schoolProject = defineType({
  name: "schoolProject",
  title: "Projet d'établissement",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    localizedRichText("body", "Contenu"),
    {
      name: "coverImage",
      title: "Image de couverture",
      type: "image",
      options: { hotspot: true },
    },
    {
      name: "document",
      title: "Document PDF",
      type: "file",
      options: { accept: ".pdf" },
    },
  ],
  preview: {
    prepare: () => ({ title: "Projet d'établissement" }),
  },
});
