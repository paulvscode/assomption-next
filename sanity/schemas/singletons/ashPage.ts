import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const ashPage = defineType({
  name: "ashPage",
  title: "Dispositif ASH",
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
  ],
  preview: {
    prepare: () => ({ title: "Dispositif ASH" }),
  },
});
