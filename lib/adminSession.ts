// Signed admin session cookies (Web Crypto: works in middleware and route handlers).
// Previously the cookie value was the literal "true", so anyone could forge it.
// The token is "<expiry>.<hmac>", keyed by the area's password: changing the
// password logs everyone out of that area.

export type AdminArea = "studio" | "apel" | "ogec";

export const AREA_COOKIE: Record<AdminArea, string> = {
  studio: "studio_auth",
  apel: "apel_auth",
  ogec: "ogec_auth",
};

const AREA_SECRET_ENV: Record<AdminArea, string> = {
  studio: "STUDIO_PASSWORD",
  apel: "APEL_PASSWORD",
  ogec: "OGEC_PASSWORD",
};

export const SESSION_SECONDS = 60 * 60 * 24 * 7;

function secretFor(area: AdminArea): string | undefined {
  return process.env[AREA_SECRET_ENV[area]];
}

async function hmac(secret: string, message: string): Promise<string> {
  const enc = new TextEncoder();
  const key = await crypto.subtle.importKey("raw", enc.encode(secret), { name: "HMAC", hash: "SHA-256" }, false, ["sign"]);
  const sig = await crypto.subtle.sign("HMAC", key, enc.encode(message));
  return Array.from(new Uint8Array(sig), (b) => b.toString(16).padStart(2, "0")).join("");
}

function safeEqual(a: string, b: string): boolean {
  if (a.length !== b.length) return false;
  let diff = 0;
  for (let i = 0; i < a.length; i++) diff |= a.charCodeAt(i) ^ b.charCodeAt(i);
  return diff === 0;
}

export async function createSessionToken(area: AdminArea): Promise<string | null> {
  const secret = secretFor(area);
  if (!secret) return null;
  const expiry = Math.floor(Date.now() / 1000) + SESSION_SECONDS;
  return `${expiry}.${await hmac(secret, `${area}:${expiry}`)}`;
}

export async function verifySessionToken(area: AdminArea, token: string | undefined): Promise<boolean> {
  const secret = secretFor(area);
  if (!secret || !token) return false;
  const [expiryStr, sig] = token.split(".");
  const expiry = Number(expiryStr);
  if (!sig || !Number.isFinite(expiry) || expiry < Date.now() / 1000) return false;
  return safeEqual(sig, await hmac(secret, `${area}:${expiry}`));
}

/** Constant-time password comparison (hash both sides so lengths match). */
export async function passwordMatches(given: unknown, expected: string): Promise<boolean> {
  if (typeof given !== "string") return false;
  const digest = async (s: string) =>
    Array.from(new Uint8Array(await crypto.subtle.digest("SHA-256", new TextEncoder().encode(s))), (b) => b.toString(16)).join("");
  return safeEqual(await digest(given), await digest(expected));
}
