// The two hosts this repository publishes to. Crawlers only accept absolute
// share URLs, so each build stamps its own origin into the og:/twitter: tags.
export const qoderOrigin = "https://farmingmurugansanthi-22b74f79eo4.qoder.website";
export const pagesOrigin = "https://sankara-ms.github.io/muruganfarms";

// Accepts either a root-relative or an already-absolute value so a build can be
// re-stamped from another build's output.
export function stampShareOrigin(html, origin) {
  return html.replace(
    /((?:og:url|og:image|twitter:image)" content=")(?:https?:\/\/[^/"]*)?\/([^"]*)"/g,
    (_match, head, rest) => `${head}${origin}/${rest}"`
  );
}
