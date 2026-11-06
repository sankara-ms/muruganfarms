// One row per page view, plus the two counts the page shows. The counter is
// append-only on purpose: an insert cannot race with another visitor's insert,
// while a read-modify-write total would quietly lose visits.
const ALLOWED_ORIGINS = new Set([
  "https://farmingmurugansanthi-22b74f79eo4.qoder.website",
  "https://sankara-ms.github.io",
]);
const MAX_BODY_BYTES = 2048;
// The farm sells to families in Tamil Nadu, so "today" is their today, not UTC.
const DAY_ZONE = "Asia/Kolkata";

type Supabase = {
  from: (table: string) => {
    insert: (row: Record<string, unknown>) => Promise<{ error: unknown }>;
    select: (columns: string, options?: { count?: string }) => {
      limit: (count: number) => Promise<{ count: number | null; error: unknown }>;
      eq: (column: string, value: string) => {
        limit: (count: number) => Promise<{ count: number | null; error: unknown }>;
      };
    };
  };
};

function corsHeaders(origin: string | null): Record<string, string> {
  if (!origin || !ALLOWED_ORIGINS.has(origin)) return {};
  return {
    "Access-Control-Allow-Origin": origin,
    "Access-Control-Allow-Methods": "POST",
    "Access-Control-Allow-Headers": "Content-Type",
    Vary: "Origin",
  };
}

function json(body: unknown, status: number, origin: string | null): Response {
  return Response.json(body, {
    status,
    headers: { "Cache-Control": "no-store", ...corsHeaders(origin) },
  });
}

function today(originDate: Date): string {
  return new Intl.DateTimeFormat("en-CA", {
    timeZone: DAY_ZONE,
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
  }).format(originDate);
}

async function countWhere(supabase: Supabase, day: string | null): Promise<number> {
  const query = supabase.from("visits").select("id", { count: "exact" });
  const result = day === null ? await query.limit(1) : await query.eq("day", day).limit(1);
  if (result.error || !Number.isSafeInteger(result.count) || (result.count as number) < 0) {
    throw new Error("count_unavailable");
  }
  return result.count as number;
}

export async function handleVisits(ctx: {
  request: Request;
  supabase: Supabase;
}): Promise<Response> {
  const { request, supabase } = ctx;
  const origin = request.headers.get("origin");

  if (request.method !== "POST") {
    return json({ ok: false, error: "method_not_allowed" }, 405, origin);
  }

  const length = Number(request.headers.get("content-length") || 0);
  if (Number.isFinite(length) && length > MAX_BODY_BYTES) {
    return json({ ok: false, error: "payload_too_large" }, 413, origin);
  }

  const viewedAt = new Date();
  const day = today(viewedAt);

  try {
    const inserted = await supabase.from("visits").insert({
      id: crypto.randomUUID(),
      day,
      viewed_at: viewedAt.toISOString(),
    });
    if (inserted.error) {
      return json({ ok: false, error: "visit_not_recorded" }, 503, origin);
    }

    const [total, visitsToday] = await Promise.all([
      countWhere(supabase, null),
      countWhere(supabase, day),
    ]);
    return json({ ok: true, total, today: visitsToday }, 200, origin);
  } catch {
    // A failed read after a recorded insert still counts the visit; the page
    // only stops showing a number it cannot vouch for.
    return json({ ok: false, error: "count_unavailable" }, 503, origin);
  }
}
