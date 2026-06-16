import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const cafeteria = defineType({
  name: "cafeteria",
  title: "Restauration scolaire",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    localizedRichText("body", "Contenu"),
    {
      name: "menuFile",
      title: "Menu (PDF)",
      type: "file",
      options: { accept: ".pdf" },
    },
    {
      name: "coverImage",
      title: "Image",
      type: "image",
      options: { hotspot: true },
    },
  ],
  preview: {
    prepare: () => ({ title: "Restauration scolaire" }),
  },
});
