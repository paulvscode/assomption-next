import type { StructureBuilder } from "sanity/structure";

const singleton = (S: StructureBuilder, id: string, title: string, type: string) =>
  S.listItem()
    .title(title)
    .id(id)
    .child(S.document().schemaType(type).documentId(type));

export const structure = (S: StructureBuilder) =>
  S.list()
    .title("Contenu du site")
    .items([
      singleton(S, "settings", "⚙️ Paramètres du site", "settings"),

      S.divider(),

      S.listItem()
        .title("🏫 Notre école")
        .child(
          S.list()
            .title("Notre école")
            .items([
              singleton(S, "landing", "Page d'accueil", "landing"),
              singleton(S, "schoolPresentation", "Présentation de l'école", "schoolPresentation"),
              singleton(S, "educationalProject", "Projet éducatif", "educationalProject"),
              singleton(S, "schoolProject", "Projet d'établissement", "schoolProject"),
              singleton(S, "pastoralMission", "Mission pastorale", "pastoralMission"),
              singleton(S, "professorsPage", "Page équipe enseignante", "professorsPage"),
              S.listItem()
                .title("Membres de l'équipe")
                .child(S.documentTypeList("professor").title("Équipe enseignante")),
              singleton(S, "englishProgram", "Programme anglais", "englishProgram"),
            ])
        ),

      S.divider(),

      S.listItem()
        .title("🎓 Programmes spéciaux")
        .child(
          S.list()
            .title("Programmes spéciaux")
            .items([
              singleton(S, "ashPage", "Dispositif ASH", "ashPage"),
              singleton(S, "ulisPage", "ULIS", "ulisPage"),
              singleton(S, "englishClassPage", "La classe anglophone", "englishClassPage"),
            ])
        ),

      S.divider(),

      S.listItem()
        .title("ℹ️ Informations pratiques")
        .child(
          S.list()
            .title("Informations pratiques")
            .items([
              singleton(S, "locationContact", "Localisation & contact", "locationContact"),
              singleton(S, "cafeteria", "Restauration scolaire", "cafeteria"),
              singleton(S, "applicationForm", "Formulaire d'inscription", "applicationForm"),
              singleton(S, "fees", "Frais de scolarité", "fees"),
              singleton(S, "backToSchool", "Rentrée scolaire", "backToSchool"),
              singleton(S, "schoolBreaks", "Calendrier des vacances", "schoolBreaks"),
              singleton(S, "otherDocuments", "Autres documents", "otherDocuments"),
            ])
        ),

      S.divider(),

      S.listItem()
        .title("👨‍👩‍👧 APEL")
        .child(
          S.list()
            .title("APEL")
            .items([
              singleton(S, "apelPresentation", "Présentation", "apelPresentation"),
              S.listItem()
                .title("Actualités APEL")
                .child(S.documentTypeList("newsApel").title("Actualités APEL")),
              S.listItem()
                .title("Événements")
                .child(S.documentTypeList("event").title("Événements")),
            ])
        ),

      S.divider(),

      S.listItem()
        .title("🏛️ OGEC")
        .child(
          S.list()
            .title("OGEC")
            .items([
              singleton(S, "ogecPresentation", "Présentation", "ogecPresentation"),
              S.listItem()
                .title("Actualités OGEC")
                .child(S.documentTypeList("newsOgec").title("Actualités OGEC")),
            ])
        ),

      S.divider(),

      S.listItem()
        .title("📰 Actualités école")
        .child(S.documentTypeList("newsSchool").title("Actualités école")),
    ]);
