"use client";

import { defineConfig } from "sanity";
import { structureTool } from "sanity/structure";
import { newsOgec } from "./sanity/schemas/documents/newsOgec";
import { apiVersion, dataset, projectId } from "./sanity/env";
import { Dashboard, DashboardIcons, type DashboardTask } from "./sanity/components/Dashboard";
import { publicationStatusBadge } from "./sanity/lib/publicationStatusBadge";
import type { StructureBuilder } from "sanity/structure";

const dashboardTasks: DashboardTask[] = [
  {
    title: "Publier une actualité",
    description: "Nouvel article OGEC",
    intent: "create",
    type: "newsOgec",
    icon: DashboardIcons.news,
  },
];

const structure = (S: StructureBuilder) =>
  S.list()
    .title("OGEC")
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
        .title("Actualités OGEC")
        .child(S.documentTypeList("newsOgec").title("Actualités OGEC")),
    ]);

export default defineConfig({
  basePath: "/studio-ogec",
  projectId,
  dataset,
  apiVersion,
  title: "OGEC — Actualités",
  schema: { types: [newsOgec] },
  plugins: [structureTool({ structure })],
  document: { badges: (prev) => [...prev, publicationStatusBadge] },
});
