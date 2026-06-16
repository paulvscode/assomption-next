import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const backToSchool = defineType({
  name: "backToSchool",
  title: "Rentrée scolaire",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    localizedRichText("body", "Contenu"),
    {
      name: "documents",
      title: "Documents à télécharger",
      type: "array",
      of: [
        {
          type: "object",
          fields: [
            { name: "label", title: "Nom du document", type: "string" },
            { name: "file", title: "Fichier", type: "file" },
          ],
          preview: {
            select: { title: "label" },
          },
        },
      ],
    },
  ],
  preview: {
    prepare: () => ({ title: "Rentrée scolaire" }),
  },
});
