"use client";

import { defineConfig } from "sanity";
import { structureTool } from "sanity/structure";
import { frFRLocale } from "@sanity/locale-fr-fr";
import { newsApel } from "./sanity/schemas/documents/newsApel";
import { event } from "./sanity/schemas/documents/event";
import { apiVersion, dataset, projectId } from "./sanity/env";
import { Dashboard, DashboardIcons, type DashboardTask } from "./sanity/components/Dashboard";
import { publicationStatusBadge } from "./sanity/lib/publicationStatusBadge";
import { defaultDocumentNode, removeDeleteAction } from "./sanity/lib/documentConfig";
import type { StructureBuilder } from "sanity/structure";

const dashboardTasks: DashboardTask[] = [
  {
    title: "Publier une actualité",
    description: "Nouvel article APEL",
    intent: "create",
    type: "newsApel",
    icon: DashboardIcons.news,
  },
  {
    title: "Ajouter un événement",
    description: "Date, lieu, description",
    intent: "create",
    type: "event",
    icon: DashboardIcons.calendar,
  },
];

const structure = (S: StructureBuilder) =>
  S.list()
    .title("APEL")
    .items([
      S.listItem()
        .title("🏠 Accueil")
        .id("accueil")
        .child(
          S.component(() => <Dashboard tasks={dashboardTasks} />)
            .title("Accueil")
            .id("accueil-dashboard")
        ),

      S.divider(),

      S.listItem()
        .title("Actualités APEL")
        .child(S.documentTypeList("newsApel").title("Actualités APEL")),
      S.listItem()
        .title("Événements")
        .child(S.documentTypeList("event").title("Événements")),
    ]);

export default defineConfig({
  basePath: "/studio-apel",
  projectId,
  dataset,
  apiVersion,
  title: "APEL — Actualités & Événements",
  schema: { types: [newsApel, event] },
  plugins: [structureTool({ structure, defaultDocumentNode }), frFRLocale()],
  document: {
    badges: (prev) => [...prev, publicationStatusBadge],
    actions: removeDeleteAction,
  },
});
