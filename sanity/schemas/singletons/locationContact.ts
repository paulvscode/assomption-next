import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const locationContact = defineType({
  name: "locationContact",
  title: "Localisation & contact",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    {
      name: "address",
      title: "Adresse",
      type: "string",
    },
    {
      name: "phone",
      title: "Téléphone",
      type: "string",
    },
    {
      name: "email",
      title: "Email",
      type: "string",
    },
    {
      name: "mapEmbedUrl",
      title: "URL d'intégration Google Maps",
      type: "url",
      description: "URL src de l'iframe Google Maps",
    },
    localizedRichText("openingHours", "Horaires d'ouverture"),
    localizedRichText("additionalInfo", "Informations complémentaires"),
  ],
  preview: {
    prepare: () => ({ title: "Localisation & contact" }),
  },
});
