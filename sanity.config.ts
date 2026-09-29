"use client";

import { defineConfig } from "sanity";
import { structureTool } from "sanity/structure";
import { schemaTypes } from "./sanity/schemas";
import { structure } from "./sanity/lib/structure";
import { publicationStatusBadge } from "./sanity/lib/publicationStatusBadge";
import { apiVersion, dataset, projectId } from "./sanity/env";

export default defineConfig({
  basePath: "/studio",
  projectId,
  dataset,
  apiVersion,
  title: "École Assomption",
  schema: { types: schemaTypes },
  plugins: [structureTool({ structure })],
  document: { badges: (prev) => [...prev, publicationStatusBadge] },
});
