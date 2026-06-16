import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const ulisPage = defineType({
  name: "ulisPage",
  title: "ULIS",
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
    prepare: () => ({ title: "ULIS" }),
  },
});
