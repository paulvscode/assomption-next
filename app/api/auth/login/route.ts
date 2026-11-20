import { NextRequest, NextResponse } from "next/server";
import { AREA_COOKIE, SESSION_SECONDS, createSessionToken, passwordMatches } from "@/lib/adminSession";

export async function POST(request: NextRequest) {
  const { password } = await request.json().catch(() => ({ password: undefined }));
  const expected = process.env.STUDIO_PASSWORD;

  if (!expected) return NextResponse.json({ error: "Server misconfiguration" }, { status: 500 });
  if (!(await passwordMatches(password, expected))) {
    // Slow down password guessing.
    await new Promise((r) => setTimeout(r, 800));
    return NextResponse.json({ error: "Invalid password" }, { status: 401 });
  }

  const token = await createSessionToken("studio");
  const response = NextResponse.json({ ok: true });
  response.cookies.set(AREA_COOKIE.studio, token!, {
    httpOnly: true,
    secure: process.env.NODE_ENV === "production",
    sameSite: "lax",
    path: "/",
    maxAge: SESSION_SECONDS,
  });
  return response;
}
