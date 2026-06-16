"use client";

import { defineConfig } from "sanity";
import { structureTool } from "sanity/structure";
import { newsOgec } from "./sanity/schemas/documents/newsOgec";
import { apiVersion, dataset, projectId } from "./sanity/env";
import type { StructureBuilder } from "sanity/structure";

const structure = (S: StructureBuilder) =>
  S.list()
    .title("OGEC")
    .items([
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
});
