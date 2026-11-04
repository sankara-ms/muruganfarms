// Copies the static export to docs/ for GitHub Pages, where the site is served
// from /muruganfarms/ instead of the domain root. The Qoder gateway only accepts
// same-origin function calls, so this build posts to the email relay directly.
import { cp, readFile, readdir, rm, writeFile } from "node:fs/promises";
import path from "node:path";

const root = path.resolve(import.meta.dirname, "..");
const source = path.join(root, "dist");
const target = path.join(root, "docs");
const base = "/muruganfarms/";
const endpoint = "https://shipmyform.com/to/sankarnaece@gmail.com";

await rm(target, { recursive: true, force: true });
await cp(source, target, { recursive: true });

async function walk(dir) {
  for (const entry of await readdir(dir, { withFileTypes: true })) {
    const full = path.join(dir, entry.name);
    if (entry.isDirectory()) await walk(full);
    else if (entry.name.endsWith(".html")) {
      const text = await readFile(full, "utf8");
      const out = text
        .replace(/(href|src|srcset)="\/(?!\/)/g, `$1="${base}`)
        .replace(/enquiryUrl:\s*"\/functions\/v1\/app"/g, `enquiryUrl: "${endpoint}"`);
      await writeFile(full, out);
    }
  }
}

await walk(target);

// The relay rejects custom request headers during CORS preflight, and Laravel's
// API routes do not use CSRF tokens.
const mainJs = path.join(target, "js", "main.js");
const js = await readFile(mainJs, "utf8");
await writeFile(mainJs, js.replace(/\n\s*"X-CSRF-TOKEN": SITE\.csrf \|\| "",/, ""));

await writeFile(path.join(target, ".nojekyll"), "");
console.log(`Pages build ready in ${target}`);
