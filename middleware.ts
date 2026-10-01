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

const loginRoutes: { path: string; cookie: string; dashboard: string }[] = [
  { path: "/login",      cookie: "studio_auth", dashboard: "/studio/structure/accueil" },
  { path: "/login-apel", cookie: "apel_auth",   dashboard: "/studio-apel/structure/accueil" },
  { path: "/login-ogec", cookie: "ogec_auth",   dashboard: "/studio-ogec/structure/accueil" },
];

export function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl;

  // Login pages — already authenticated? Skip straight to the dashboard.
  const loginRoute = loginRoutes.find((route) => route.path === pathname);
  if (loginRoute) {
    const auth = request.cookies.get(loginRoute.cookie);
    if (auth?.value === "true") {
      return NextResponse.redirect(new URL(loginRoute.dashboard, request.url));
    }
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
