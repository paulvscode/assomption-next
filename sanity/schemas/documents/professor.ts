import { defineType } from "sanity";
import { localizedString, localizedText } from "../helpers/localized";

export const professor = defineType({
  name: "professor",
  title: "Enseignant / Membre de l'équipe",
  type: "document",
  fields: [
    {
      name: "name",
      title: "Nom complet",
      type: "string",
      validation: (r) => r.required(),
    },
    localizedString("role", "Rôle / Matière"),
    {
      name: "photo",
      title: "Photo",
      type: "image",
      options: { hotspot: true },
    },
    localizedText("bio", "Biographie courte"),
    {
      name: "order",
      title: "Ordre d'affichage",
      type: "number",
      description: "Nombre plus petit = affiché en premier",
    },
  ],
  orderings: [
    {
      title: "Ordre d'affichage",
      name: "orderAsc",
      by: [{ field: "order", direction: "asc" }],
    },
    {
      title: "Nom",
      name: "nameAsc",
      by: [{ field: "name", direction: "asc" }],
    },
  ],
  preview: {
    select: { title: "name", subtitle: "role.fr", media: "photo" },
  },
});
