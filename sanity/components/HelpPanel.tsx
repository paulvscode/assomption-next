"use client";

import type { UserViewComponent } from "sanity/structure";

type HelpContent = {
  intro: string;
  steps?: string[];
};

const HELP: Record<string, HelpContent> = {
  landing: {
    intro:
      "Cette page regroupe tout le contenu modifiable de la page d'accueil du site, organisé en onglets ci-dessus.",
    steps: [
      "Bannière : message optionnel affiché tout en haut du site.",
      "Actualités : active ou masque le bloc des 3 dernières actualités.",
      "Bandeau principal : grande image, titre et texte d'introduction.",
      "Liens rapides : les 6 cartes-images qui mènent vers les pages de l'école.",
      "Contact : adresse, téléphone, email et horaires affichés en bas de page.",
    ],
  },
  settings: {
    intro: "Ces réglages s'appliquent à l'ensemble du site, sur toutes les pages.",
    steps: [
      "Logo : image affichée dans la barre de navigation (PNG transparent, ratio 3:1 recommandé).",
      "Couleur d'accent : couleur des séparateurs et de la section liens rapides.",
    ],
  },
  newsSchool: {
    intro:
      "Cet article apparaît dans la liste « Actualités » du site, et parmi les 3 derniers articles sur la page d'accueil si cette section est activée.",
    steps: [
      "Titre, date et image sont ce qui s'affiche sur la carte de l'article.",
      "Le résumé court (2-3 phrases) apparaît sur la page d'accueil.",
      "Le contenu complet s'affiche sur la page de l'article.",
    ],
  },
  newsApel: {
    intro: "Cet article apparaît dans la liste des actualités de l'espace APEL du site.",
    steps: [
      "Titre, date et image sont ce qui s'affiche sur la carte de l'article.",
      "Le résumé court apparaît sur la liste ; le contenu complet sur la page de l'article.",
    ],
  },
  newsOgec: {
    intro: "Cet article apparaît dans la liste des actualités de l'espace OGEC du site.",
    steps: [
      "Titre, date et image sont ce qui s'affiche sur la carte de l'article.",
      "Le résumé court apparaît sur la liste ; le contenu complet sur la page de l'article.",
    ],
  },
  event: {
    intro: "Cet événement apparaît dans le calendrier des événements APEL du site.",
    steps: [
      "La date de début détermine l'ordre d'affichage des événements.",
      "Le lieu et la description sont visibles sur la page de l'événement.",
    ],
  },
  professor: {
    intro: "Cette fiche apparaît dans la liste de l'équipe enseignante du site.",
    steps: ["Photo, nom et rôle sont ce qui s'affiche sur la carte.", "La biographie est visible sur la fiche complète du professeur."],
  },
  apelPresentation: {
    intro: "Ce texte est la page de présentation de l'APEL, l'association des parents d'élèves.",
  },
  ogecPresentation: {
    intro: "Ce texte est la page de présentation de l'OGEC, l'organisme de gestion de l'école.",
  },
  locationContact: {
    intro: "Ces informations apparaissent sur la page « Localisation & contact » du site.",
    steps: ["Adresse, téléphone et email sont affichés et cliquables.", "L'URL Google Maps contrôle la carte intégrée sur la page."],
  },
};

const DEFAULT_HELP: HelpContent = {
  intro:
    "Cette page correspond au contenu affiché à l'emplacement correspondant sur le site. Modifiez le texte puis cliquez sur Publier pour mettre le site à jour.",
};

const PUBLISH_REMINDER =
  "Vos modifications ne sont pas visibles sur le site tant que vous n'avez pas cliqué sur Publier.";

export const HelpPanel: UserViewComponent = ({ schemaType }) => {
  const help = HELP[schemaType.name] ?? DEFAULT_HELP;

  return (
    <div style={{ padding: "24px 28px", maxWidth: 560 }}>
      <div
        style={{
          borderRadius: 10,
          background: "#EAF1F4",
          padding: "14px 16px",
          marginBottom: 16,
        }}
      >
        <p style={{ margin: 0, fontSize: 10.5, fontWeight: 700, letterSpacing: "0.04em", textTransform: "uppercase", color: "#2C4753" }}>
          À quoi sert cette page
        </p>
        <p style={{ margin: "5px 0 0", fontSize: 13, lineHeight: 1.5, color: "#2E2C31" }}>{help.intro}</p>
      </div>

      {help.steps && (
        <ul style={{ margin: "0 0 16px", padding: 0, listStyle: "none", display: "flex", flexDirection: "column", gap: 10 }}>
          {help.steps.map((step, i) => (
            <li key={i} style={{ display: "flex", gap: 10, alignItems: "flex-start" }}>
              <span
                style={{
                  flexShrink: 0,
                  width: 18,
                  height: 18,
                  marginTop: 1,
                  borderRadius: "50%",
                  background: "#2E2C31",
                  color: "#FFFFFF",
                  fontSize: 10,
                  fontWeight: 700,
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                }}
              >
                {i + 1}
              </span>
              <span style={{ fontSize: 12.5, lineHeight: 1.5, color: "#5C5763" }}>{step}</span>
            </li>
          ))}
        </ul>
      )}

      <div
        style={{
          borderRadius: 10,
          border: "1px solid #F0DCAF",
          background: "#FAF1E2",
          padding: "10px 14px",
          fontSize: 12,
          lineHeight: 1.5,
          color: "#8A6423",
        }}
      >
        <strong>Bon à savoir — </strong>
        {PUBLISH_REMINDER}
      </div>
    </div>
  );
};
