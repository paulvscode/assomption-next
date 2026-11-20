// Builds www/assets/site.css from tools/site.input.css with Tailwind.
// Run from the repository root:  node ovh/tools/build-css.mjs
// Only needed after changing classes in the public templates.
import { readFile, writeFile } from "node:fs/promises";
import { fileURLToPath } from "node:url";
import path from "node:path";
import postcss from "postcss";
import tailwind from "@tailwindcss/postcss";

const here = path.dirname(fileURLToPath(import.meta.url));
const input = path.join(here, "site.input.css");
const output = path.join(here, "../www/assets/site.css");

const css = await readFile(input, "utf8");
const result = await postcss([tailwind({ base: here, optimize: { minify: true } })]).process(css, { from: input, to: output });
await writeFile(output, result.css);
console.log(`site.css written (${(result.css.length / 1024).toFixed(1)} KB)`);
