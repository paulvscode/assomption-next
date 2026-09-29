import type { DocumentBadgeComponent } from "sanity";

/**
 * Replaces Studio's default draft dot with a plain-language status,
 * so a non-technical editor can tell at a glance whether a page is
 * live on the site or still waiting to be published.
 */
export const publicationStatusBadge: DocumentBadgeComponent = ({ draft, published }) => {
  if (draft && published) {
    return { label: "Modifications non publiées", color: "warning" };
  }
  if (draft && !published) {
    return { label: "Jamais publié", color: "warning" };
  }
  if (!draft && published) {
    return { label: "En ligne", color: "success" };
  }
  return null;
};
