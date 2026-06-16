import { defineType } from "sanity";
import { localizedString, localizedRichText } from "../helpers/localized";

export const schoolBreaks = defineType({
  name: "schoolBreaks",
  title: "Calendrier des vacances",
  type: "document",
  fields: [
    localizedString("title", "Titre de la page"),
    localizedRichText("body", "Contenu"),
    {
      name: "calendarFile",
      title: "Calendrier (PDF)",
      type: "file",
      options: { accept: ".pdf" },
    },
  ],
  preview: {
    prepare: () => ({ title: "Calendrier des vacances" }),
  },
});
