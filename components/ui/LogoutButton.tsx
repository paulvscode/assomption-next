"use client";

import { useRouter } from "next/navigation";

interface Props {
  logoutPath?: string;
  redirectTo?: string;
}

export default function LogoutButton({
  logoutPath = "/api/auth/logout",
  redirectTo = "/login",
}: Props) {
  const router = useRouter();

  async function handleLogout() {
    await fetch(logoutPath, { method: "POST" });
    router.push(redirectTo);
  }

  return (
    <button
      onClick={handleLogout}
      className="rounded-lg border border-primary/30 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-primary transition-colors hover:bg-primary hover:text-white"
    >
      Déconnexion · Logout
    </button>
  );
}
