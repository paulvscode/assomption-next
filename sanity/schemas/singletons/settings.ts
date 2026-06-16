import { defineType } from "sanity";
import { ColorPickerInput } from "../../components/ColorPickerInput";

export const settings = defineType({
  name: "settings",
  title: "Paramètres du site",
  type: "document",
  fields: [
    {
      name: "logo",
      title: "Logo",
      type: "image",
      description: "Logo affiché dans la barre de navigation. Format recommandé : PNG transparent, ratio ~3:1 (ex. 300×100 px).",
      options: { hotspot: false },
    },
    {
      name: "accentColor",
      title: "Couleur d'accent",
      type: "string",
      description: "Couleur des séparateurs et de la section liens rapides. Par défaut : #8BB1C5.",
      components: { input: ColorPickerInput },
    },
  ],
  preview: {
    prepare: () => ({ title: "Paramètres du site" }),
  },
});
