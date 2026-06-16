import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const fees = defineType({
  name: "fees",
  title: "Frais de scolarité",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    localizedRichText("body", "Contenu"),
    {
      name: "feesFile",
      title: "Grille tarifaire (PDF)",
      type: "file",
      options: { accept: ".pdf" },
    },
  ],
  preview: {
    prepare: () => ({ title: "Frais de scolarité" }),
  },
});
