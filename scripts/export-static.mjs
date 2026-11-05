// Fetches the rendered EN and TA pages from the local Laravel app and writes a
// static build to dist/. Asset folders are synced from laravel/public.
import { cp, mkdir, readdir, rm, writeFile } from "node:fs/promises";
import path from "node:path";

const root = path.resolve(import.meta.dirname, "..");
const origin = "http://127.0.0.1:8000";
const publicDir = path.join(root, "laravel", "public");
const target = path.join(root, "dist");

async function fetchPage(pathname, cookie) {
  const res = await fetch(origin + pathname, { headers: cookie ? { Cookie: cookie } : {}, redirect: "follow" });
  const html = await res.text();
  return { html, res };
}

function readCookies(res, cookie) {
  const setCookie = res.headers.get("set-cookie");
  const jar = setCookie ? setCookie.split(/, (?=[^;,]*=)/).map((c) => c.split(";")[0]) : [];
  return [...new Set([...(cookie ? cookie.split("; ") : []), ...jar])].join("; ");
}

function processHtml(html, locale) {
  let out = html
    .replace("<html lang=", '<html data-static-locale lang=')
    .replaceAll(origin, "")
    .replaceAll("http:\\/\\/127.0.0.1:8000", "")
    .replaceAll('href="/locale/en"', 'href="/"')
    .replaceAll('href="/locale/ta"', 'href="/ta/index.html"')
    .replaceAll("/api/enquiries", "/functions/v1/app")
    .replaceAll("\\/api\\/enquiries", "\\/functions\\/v1\\/app")
    .replaceAll("\\/", "/");
  if (!out.includes("<html data-static-locale")) {
    throw new Error("static locale marker missing in " + locale + " export");
  }
  if (locale === "en" && !out.includes('enquiryUrl: "/functions/v1/app"')) {
    throw new Error("enquiryUrl rewrite missing in EN export");
  }
  return out;
}

async function syncAssets() {
  for (const dir of ["css", "js", "vendor", "sites", "assets"]) {
    await rm(path.join(target, dir), { recursive: true, force: true });
    await cp(path.join(publicDir, dir), path.join(target, dir), { recursive: true });
  }
}

await mkdir(path.join(target, "ta"), { recursive: true });

const en = processHtml((await fetchPage("/")).html, "en");
await writeFile(path.join(target, "index.html"), en);

// The locale switch 302s back to /; following that redirect would start a fresh
// session and lose the choice, so capture the 302's session cookie instead.
const switchRes = await fetch(origin + "/locale/ta", { redirect: "manual" });
const taCookie = readCookies(switchRes, "");
await switchRes.body?.cancel();
const ta = processHtml((await fetchPage("/", taCookie)).html, "ta");
await writeFile(path.join(target, "ta", "index.html"), ta);

await syncAssets();
console.log("dist/ exported:", en.length, "bytes EN /", ta.length, "bytes TA");
