import { createClient } from "next-sanity";
import { apiVersion, dataset, projectId } from "../env";

export const client = createClient({
  projectId,
  dataset,
  apiVersion,
  useCdn: process.env.NODE_ENV === "production",
});

// Draft-aware client used when Next.js draft mode is enabled (live preview
// from Sanity's Presentation tool). Requires SANITY_API_READ_TOKEN.
export const getClient = (preview = false) =>
  preview
    ? client.withConfig({
        token: process.env.SANITY_API_READ_TOKEN,
        perspective: "drafts",
        useCdn: false,
      })
    : client;
