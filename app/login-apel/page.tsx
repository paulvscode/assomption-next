"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";

export default function ApelLoginPage() {
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const router = useRouter();

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setLoading(true);
    setError("");

    const res = await fetch("/api/auth/login-apel", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ password }),
    });

    if (res.ok) {
      router.push("/studio-apel");
    } else {
      setError("Mot de passe incorrect. / Incorrect password.");
      setLoading(false);
    }
  }

  return (
    <div className="flex min-h-svh items-center justify-center bg-surface px-4">
      <div className="w-full max-w-sm">
        <div className="rounded-2xl border border-border bg-white px-8 py-10 shadow-sm">
          <div className="mb-8 text-center">
            <p className="font-display text-xl font-semibold tracking-wide text-primary">
              École Assomption
            </p>
            <p className="mt-1 text-sm text-muted">APEL — Accès espace actualités</p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-5">
            <div>
              <label
                htmlFor="password"
                className="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-primary"
              >
                Mot de passe · Password
              </label>
              <input
                id="password"
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                autoFocus
                className="w-full rounded-lg border border-border bg-white px-4 py-2.5 text-sm text-primary placeholder-muted outline-none transition-colors focus:border-primary focus:ring-2 focus:ring-primary/10"
                placeholder="••••••••"
              />
            </div>

            {error && (
              <p className="rounded-lg bg-red-50 px-4 py-2.5 text-xs text-red-600">{error}</p>
            )}

            <button
              type="submit"
              disabled={loading}
              className="w-full rounded-lg bg-primary py-2.5 text-sm font-semibold text-white transition-opacity hover:opacity-80 disabled:opacity-50"
            >
              {loading ? "Connexion…" : "Se connecter · Sign in"}
            </button>
          </form>
        </div>

        <p className="mt-5 text-center text-xs text-muted">
          <a href="/fr" className="hover:text-primary transition-colors">
            ← Retour au site · Back to site
          </a>
        </p>
      </div>
    </div>
  );
}
