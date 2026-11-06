import { createClient } from "npm:@supabase/supabase-js@2.57.4";
import { serveSite } from "./adapter.mjs";
import { handler as handleEnquiry } from "./handler.ts";
import { handleVisits } from "./visits.ts";

// The counter is the only route that needs the database. Wrapping just that
// route keeps the enquiry relay independent of the database runtime.
const withDatabase = serveSite(handleVisits, {
  createClient,
  env: (name: string) => Deno.env.get(name),
});

Deno.serve((request: Request) => {
  const path = new URL(request.url).pathname;
  if (path.endsWith("/visits")) return withDatabase(request);
  return handleEnquiry(request);
});
