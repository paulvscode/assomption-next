import Link from "next/link";
import Image from "next/image";
import { getLocale, getTranslations } from "next-intl/server";
import Navigation from "./Navigation";
import LanguageSwitcher from "../ui/LanguageSwitcher";
import { client } from "@/sanity/lib/client";
import { singletonQuery } from "@/sanity/lib/queries";
import { urlFor } from "@/sanity/lib/image";

export default async function Header() {
  const locale = await getLocale();
  const t = await getTranslations("common");

  const settings = await client.fetch(singletonQuery("settings")).catch(() => null);
  const logoUrl = settings?.logo
    ? urlFor(settings.logo).height(180).url()
    : null;

  return (
    <header className="sticky top-0 z-40 w-full bg-white border-b border-border">
      <div className="mx-auto flex h-16 sm:h-26.5 max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8">

        {/* Logo */}
          <Link href={`/${locale}`} className="shrink-0 transition-opacity hover:opacity-75">
            {logoUrl ? (
              <Image
                src={logoUrl}
                alt={t("schoolName")}
                height={90}
                width={288}
                className="h-15 sm:h-22.5 w-auto object-contain"
                priority
              />
            ) : (
              <div className="flex h-15 sm:h-22.5 w-50 sm:w-[288px] items-center justify-center rounded-md bg-border text-xs font-semibold text-muted">
                Logo
              </div>
            )}
          </Link>

        {/* Nav + language switcher */}
        <div className="flex flex-1 items-center justify-end gap-4">
          <Navigation />
          <LanguageSwitcher />
        </div>

      </div>
    </header>
  );
}
