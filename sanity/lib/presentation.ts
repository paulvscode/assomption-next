import { defineLocations, presentationTool } from "sanity/presentation";

export const presentation = presentationTool({
  title: "Aperçu en direct",
  previewUrl: {
    previewMode: {
      enable: "/api/draft-mode/enable",
    },
  },
  resolve: {
    locations: {
      landing: defineLocations({
        select: { id: "_id" },
        resolve: () => ({
          locations: [
            { title: "Page d'accueil — Français", href: "/fr" },
            { title: "Page d'accueil — English", href: "/en" },
          ],
        }),
      }),
    },
  },
});
