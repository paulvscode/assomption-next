import { client } from "@/sanity/lib/client";
import { eventsQuery } from "@/sanity/lib/queries";
import { getTranslations } from "next-intl/server";
import Link from "next/link";
import Image from "next/image";
import { urlFor } from "@/sanity/lib/image";

type EventItem = {
  _id: string;
  title?: { fr?: string; en?: string };
  startDate?: string;
  endDate?: string;
  location?: { fr?: string; en?: string };
  coverImage?: object;
  excerpt?: { fr?: string; en?: string };
};

export default async function EventsPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const l = locale as "fr" | "en";
  const t = await getTranslations({ locale, namespace: "events" });
  const tc = await getTranslations({ locale, namespace: "common" });
  const events: EventItem[] = await client.fetch(eventsQuery);

  return (
    <div className="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
      <h1 className="mb-8 text-3xl font-bold text-gray-900">{t("allEvents")}</h1>
      {events?.length > 0 ? (
        <div className="space-y-6">
          {events.map((ev) => (
            <Link
              key={ev._id}
              href={`/${locale}/apel/evenements/${ev._id}`}
              className="group flex gap-5 rounded-xl border border-gray-100 bg-white p-5 shadow-sm hover:shadow-md transition-shadow"
            >
              {ev.coverImage && (
                <div className="flex-shrink-0 w-32 h-24 overflow-hidden rounded-lg">
                  <Image
                    src={urlFor(ev.coverImage).width(200).height(150).url()}
                    alt=""
                    width={200}
                    height={150}
                    className="w-full h-full object-cover"
                  />
                </div>
              )}
              <div>
                <h2 className="font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">
                  {ev.title?.[l] ?? ev.title?.fr ?? ""}
                </h2>
                {ev.startDate && (
                  <p className="text-sm text-blue-700 mt-0.5">
                    {new Date(ev.startDate).toLocaleDateString(locale === "fr" ? "fr-FR" : "en-GB")}
                  </p>
                )}
                {ev.location && (
                  <p className="text-sm text-gray-500">
                    📍 {ev.location[l] ?? ev.location.fr ?? ""}
                  </p>
                )}
                {ev.excerpt && (
                  <p className="mt-1 text-sm text-gray-600 line-clamp-2">
                    {ev.excerpt[l] ?? ev.excerpt.fr ?? ""}
                  </p>
                )}
              </div>
            </Link>
          ))}
        </div>
      ) : (
        <p className="text-gray-500">{tc("noContent")}</p>
      )}
    </div>
  );
}
