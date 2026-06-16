import { defineType } from "sanity";
import { localizedString } from "../helpers/localized";

export const otherDocuments = defineType({
  name: "otherDocuments",
  title: "Autres documents",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    {
      name: "documents",
      title: "Documents",
      type: "array",
      of: [
        {
          type: "object",
          fields: [
            localizedString("label", "Nom du document"),
            { name: "file", title: "Fichier", type: "file" },
          ],
          preview: {
            select: { title: "label.fr" },
          },
        },
      ],
    },
  ],
  preview: {
    prepare: () => ({ title: "Autres documents" }),
  },
});
