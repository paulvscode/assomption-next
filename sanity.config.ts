"use client";

import { defineConfig } from "sanity";
import { structureTool } from "sanity/structure";
import { frFRLocale } from "@sanity/locale-fr-fr";
import { schemaTypes } from "./sanity/schemas";
import { structure } from "./sanity/lib/structure";
import { publicationStatusBadge } from "./sanity/lib/publicationStatusBadge";
import { defaultDocumentNode, removeDeleteAction } from "./sanity/lib/documentConfig";
import { presentation } from "./sanity/lib/presentation";
import { apiVersion, dataset, projectId } from "./sanity/env";

export default defineConfig({
  basePath: "/studio",
  projectId,
  dataset,
  apiVersion,
  title: "École Assomption",
  schema: { types: schemaTypes },
  plugins: [structureTool({ structure, defaultDocumentNode }), presentation, frFRLocale()],
  document: {
    badges: (prev) => [...prev, publicationStatusBadge],
    actions: removeDeleteAction,
  },
});
