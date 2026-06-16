import { defineField } from "sanity";

export const localizedString = (name: string, title: string) =>
  defineField({
    name,
    title,
    type: "object",
    options: { columns: 2 },
    fields: [
      { name: "fr", title: `${title} (FR)`, type: "string" },
      { name: "en", title: `${title} (EN)`, type: "string" },
    ],
  });

export const localizedText = (name: string, title: string) =>
  defineField({
    name,
    title,
    type: "object",
    fields: [
      { name: "fr", title: `${title} (FR)`, type: "text", rows: 4 },
      { name: "en", title: `${title} (EN)`, type: "text", rows: 4 },
    ],
  });

export const localizedRichText = (name: string, title: string) =>
  defineField({
    name,
    title,
    type: "object",
    fields: [
      {
        name: "fr",
        title: `${title} (FR)`,
        type: "array",
        of: [
          {
            type: "block",
            styles: [
              { title: "Paragraphe", value: "normal" },
              { title: "Titre H2", value: "h2" },
              { title: "Titre H3", value: "h3" },
              { title: "Citation", value: "blockquote" },
            ],
            marks: {
              decorators: [
                { title: "Gras", value: "strong" },
                { title: "Italique", value: "em" },
                { title: "Souligné", value: "underline" },
              ],
              annotations: [
                {
                  name: "link",
                  type: "object",
                  title: "Lien",
                  fields: [{ name: "href", type: "url", title: "URL" }],
                },
              ],
            },
          },
          { type: "image", options: { hotspot: true } },
        ],
      },
      {
        name: "en",
        title: `${title} (EN)`,
        type: "array",
        of: [
          {
            type: "block",
            styles: [
              { title: "Paragraph", value: "normal" },
              { title: "Heading H2", value: "h2" },
              { title: "Heading H3", value: "h3" },
              { title: "Blockquote", value: "blockquote" },
            ],
            marks: {
              decorators: [
                { title: "Bold", value: "strong" },
                { title: "Italic", value: "em" },
                { title: "Underline", value: "underline" },
              ],
              annotations: [
                {
                  name: "link",
                  type: "object",
                  title: "Link",
                  fields: [{ name: "href", type: "url", title: "URL" }],
                },
              ],
            },
          },
          { type: "image", options: { hotspot: true } },
        ],
      },
    ],
  });
