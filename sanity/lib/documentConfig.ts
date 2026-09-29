import type { DefaultDocumentNodeResolver } from "sanity/structure";
import type { DocumentActionComponent } from "sanity";
import { HelpPanel } from "../components/HelpPanel";

/**
 * Adds the "Aide" tab to every document opened from a document-type list
 * (news, team members, events…). Singletons build their own document node
 * in sanity/lib/structure.tsx and wire the same views there.
 */
export const defaultDocumentNode: DefaultDocumentNodeResolver = (S) =>
  S.document().views([
    S.view.form().title("Contenu"),
    S.view.component(HelpPanel).id("aide").title("Aide"),
  ]);

/**
 * Removes the irreversible "Delete" action so a non-technical editor's
 * safest option is "Unpublish" (reversible — republish brings it back).
 */
export const removeDeleteAction = (prev: DocumentActionComponent[]) =>
  prev.filter((action) => action.action !== "delete");
