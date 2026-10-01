import type { StructureBuilder } from "sanity/structure";
import { Dashboard, DashboardIcons, type DashboardTask, type PageGroup } from "../components/Dashboard";
import { HelpPanel } from "../components/HelpPanel";

const singleton = (S: StructureBuilder, id: string, title: string, type: string) =>
  S.listItem()
    .title(title)
    .id(id)
    .child(
      S.document()
        .schemaType(type)
        .documentId(type)
        .views([
          S.view.form().title("Contenu"),
          S.view.component(HelpPanel).id("aide").title("Aide"),
        ])
    );

const dashboardTasks: DashboardTask[] = [
  {
    title: "Publier une actualité",
    description: "École, APEL ou OGEC",
    intent: "create",
    type: "newsSchool",
    icon: DashboardIcons.news,
  },
  {
    title: "Mettre à jour l'équipe",
    description: "Ajouter ou modifier un professeur",
    intent: "create",
    type: "professor",
    icon: DashboardIcons.users,
  },
  {
    title: "Paramètres du site",
    description: "Logo, couleur d'accent",
    intent: "edit",
    type: "settings",
    icon: DashboardIcons.settings,
  },
];

const page = (title: string, type: string, description: string, icon = DashboardIcons.info): DashboardTask => ({
  title,
  description,
  intent: "edit",
  type,
  icon,
});

const pageGroups: PageGroup[] = [
  {
    title: "Notre école",
    pages: [
      page("Page d'accueil", "landing", "Bannière, hero, liens rapides, contact", DashboardIcons.image),
      page("Présentation de l'école", "schoolPresentation", "Texte, valeurs, photos"),
      page("Projet éducatif", "educationalProject", "Contenu éditorial"),
      page("Projet d'établissement", "schoolProject", "Contenu éditorial"),
      page("Mission pastorale", "pastoralMission", "Contenu éditorial"),
      page("Page équipe enseignante", "professorsPage", "Présentation de l'équipe", DashboardIcons.users),
      page("Programme anglais", "englishProgram", "Contenu éditorial"),
    ],
  },
  {
    title: "Programmes spéciaux",
    pages: [
      page("Dispositif ASH", "ashPage", "Contenu éditorial"),
      page("ULIS", "ulisPage", "Contenu éditorial"),
      page("La classe anglophone", "englishClassPage", "Contenu éditorial"),
    ],
  },
  {
    title: "Informations pratiques",
    pages: [
      page("Localisation & contact", "locationContact", "Adresse, téléphone, carte"),
      page("Restauration scolaire", "cafeteria", "Contenu éditorial"),
      page("Formulaire d'inscription", "applicationForm", "Contenu éditorial"),
      page("Frais de scolarité", "fees", "Contenu éditorial"),
      page("Rentrée scolaire", "backToSchool", "Contenu éditorial", DashboardIcons.calendar),
      page("Calendrier des vacances", "schoolBreaks", "Dates des vacances scolaires", DashboardIcons.calendar),
      page("Autres documents", "otherDocuments", "Documents téléchargeables"),
    ],
  },
  {
    title: "APEL",
    pages: [
      page("Présentation APEL", "apelPresentation", "Texte de présentation", DashboardIcons.heart),
    ],
  },
  {
    title: "OGEC",
    pages: [
      page("Présentation OGEC", "ogecPresentation", "Texte de présentation", DashboardIcons.shield),
    ],
  },
];

export const structure = (S: StructureBuilder) =>
  S.list()
    .title("Contenu du site")
    .items([
      S.listItem()
        .title("🏠 Accueil")
        .id("accueil")
        .child(
          S.component(() => <Dashboard tasks={dashboardTasks} pageGroups={pageGroups} />)
            .title("Accueil")
            .id("accueil-dashboard")
        ),

      S.divider(),

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
