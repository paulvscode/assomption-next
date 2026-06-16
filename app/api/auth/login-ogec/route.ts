import { NextRequest, NextResponse } from "next/server";

export async function POST(request: NextRequest) {
  const { password } = await request.json();
  const expected = process.env.OGEC_PASSWORD;

  if (!expected) return NextResponse.json({ error: "Server misconfiguration" }, { status: 500 });
  if (password !== expected) return NextResponse.json({ error: "Invalid password" }, { status: 401 });

  const response = NextResponse.json({ ok: true });
  response.cookies.set("ogec_auth", "true", {
    httpOnly: true,
    secure: process.env.NODE_ENV === "production",
    sameSite: "lax",
    path: "/",
    maxAge: 60 * 60 * 24 * 7,
  });
  return response;
}
