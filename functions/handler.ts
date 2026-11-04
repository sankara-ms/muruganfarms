// Receives the farm enquiry form, validates it and forwards it to the owner's
// inbox through ShipMyForm's instant endpoint (email-only setup, no account).
// Storing the message is the relay's job; this handler is stateless.
const OWNER_EMAIL = "sankarnaece@gmail.com";
const RELAY_URL = `https://shipmyform.com/to/${OWNER_EMAIL}`;
const SITE_ORIGIN = "https://farmingmurugansanthi-22b74f79eo4.qoder.website";
// The GitHub Pages demo is a different origin, so its form posts here cross-origin.
const ALLOWED_ORIGINS = new Set([
  SITE_ORIGIN,
  "https://sankara-ms.github.io",
]);
const MAX_BODY_BYTES = 16 * 1024;

type Enquiry = {
  name: string;
  phone: string;
  product: string;
  message: string;
};

function corsHeaders(origin: string | null): Record<string, string> {
  if (!origin || !ALLOWED_ORIGINS.has(origin)) return {};
  return {
    "Access-Control-Allow-Origin": origin,
    "Access-Control-Allow-Methods": "POST, OPTIONS",
    "Access-Control-Allow-Headers": "Content-Type",
    "Access-Control-Max-Age": "86400",
    Vary: "Origin",
  };
}

function json(body: unknown, status = 200, origin: string | null = null): Response {
  return Response.json(body, {
    status,
    headers: { "Cache-Control": "no-store", ...corsHeaders(origin) },
  });
}

function clean(value: unknown, maxLength: number): string {
  if (typeof value !== "string") return "";
  return value.replace(/[\u0000-\u001f\u007f]/g, " ").replace(/\s+/g, " ").trim().slice(0, maxLength);
}

export function parseEnquiry(input: unknown): Enquiry | null {
  if (!input || typeof input !== "object") return null;
  const source = input as Record<string, unknown>;

  const name = clean(source.name, 80);
  const phone = clean(source.phone, 24);
  const product = clean(source.product, 80);
  const message = clean(source.message, 2000);
  const digits = phone.replace(/[^0-9]/g, "");

  if (name.length < 2) return null;
  if (digits.length < 10 || digits.length > 15) return null;
  if (message.length < 10) return null;

  return { name, phone, product, message };
}

async function relay(enquiry: Enquiry): Promise<boolean> {
  const response = await fetch(RELAY_URL, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      Origin: SITE_ORIGIN,
      Referer: `${SITE_ORIGIN}/`,
    },
    body: JSON.stringify({
      name: enquiry.name,
      phone: enquiry.phone,
      product: enquiry.product || "General enquiry",
      message: enquiry.message,
      _subject: `New farm enquiry from ${enquiry.name}`,
      _gotcha: "",
    }),
    signal: AbortSignal.timeout(10_000),
  });

  if (!response.ok) return false;

  const result = await response.json().catch(() => null);
  return Boolean(result && result.ok === true);
}

export async function handler(request: Request): Promise<Response> {
  const origin = request.headers.get("origin");

  if (request.method === "OPTIONS") {
    return new Response(null, { status: 204, headers: corsHeaders(origin) });
  }

  if (request.method !== "POST") {
    return json({ ok: false, error: "method_not_allowed" }, 405, origin);
  }

  const type = request.headers.get("content-type") || "";
  if (!type.toLowerCase().includes("application/json")) {
    return json({ ok: false, error: "unsupported_media_type" }, 415, origin);
  }

  const raw = await request.text();
  if (raw.length > MAX_BODY_BYTES) {
    return json({ ok: false, error: "payload_too_large" }, 413, origin);
  }

  let enquiry: Enquiry | null = null;
  try {
    enquiry = parseEnquiry(JSON.parse(raw));
  } catch {
    return json({ ok: false, error: "invalid_json" }, 400, origin);
  }

  if (!enquiry) {
    return json({ ok: false, error: "invalid_enquiry" }, 400, origin);
  }

  try {
    const accepted = await relay(enquiry);
    if (!accepted) {
      return json({ ok: false, error: "notification_failed" }, 502, origin);
    }
    return json({ ok: true }, 200, origin);
  } catch {
    return json({ ok: false, error: "notification_failed" }, 502, origin);
  }
}
