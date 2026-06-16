"use client";

import { defineConfig } from "sanity";
import { structureTool } from "sanity/structure";
import { newsApel } from "./sanity/schemas/documents/newsApel";
import { event } from "./sanity/schemas/documents/event";
import { apiVersion, dataset, projectId } from "./sanity/env";
import type { StructureBuilder } from "sanity/structure";

const structure = (S: StructureBuilder) =>
  S.list()
    .title("APEL")
    .items([
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
  plugins: [structureTool({ structure })],
});
