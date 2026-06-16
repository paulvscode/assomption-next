import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const applicationForm = defineType({
  name: "applicationForm",
  title: "Formulaire d'inscription",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    localizedRichText("intro", "Introduction"),
    {
      name: "formFiles",
      title: "Formulaires à télécharger",
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
    localizedRichText("additionalInfo", "Informations complémentaires"),
  ],
  preview: {
    prepare: () => ({ title: "Formulaire d'inscription" }),
  },
});
