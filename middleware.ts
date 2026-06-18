import createMiddleware from "next-intl/middleware";
import { NextRequest, NextResponse } from "next/server";
import { routing } from "./i18n/routing";

const intlMiddleware = createMiddleware(routing);

const studioRoutes: { prefix: string; cookie: string; login: string }[] = [
  { prefix: "/studio-apel",  cookie: "apel_auth",   login: "/login-apel" },
  { prefix: "/studio-ogec",  cookie: "ogec_auth",   login: "/login-ogec" },
  { prefix: "/studio",       cookie: "studio_auth", login: "/login" },
  { prefix: "/admin-guide",  cookie: "studio_auth", login: "/login" },
];

const publicPaths = ["/login", "/login-apel", "/login-ogec"];

export function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl;

  // Login pages — serve as-is, no locale routing
  if (publicPaths.includes(pathname)) {
    return NextResponse.next();
  }

  // Studio routes — check the corresponding cookie
  for (const route of studioRoutes) {
    if (pathname.startsWith(route.prefix)) {
      const auth = request.cookies.get(route.cookie);
      if (auth?.value !== "true") {
        return NextResponse.redirect(new URL(route.login, request.url));
      }
      return NextResponse.next();
    }
  }

  // All other routes go through next-intl locale routing
  return intlMiddleware(request);
}

export const config = {
  matcher: ["/((?!api|_next|_vercel|.*\\..*).*)" ],
};
