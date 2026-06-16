import { client } from "@/sanity/lib/client";
import { newsApelQuery } from "@/sanity/lib/queries";
import { getTranslations } from "next-intl/server";
import Link from "next/link";
import Image from "next/image";
import { urlFor } from "@/sanity/lib/image";

type NewsItem = {
  _id: string;
  title?: { fr?: string; en?: string };
  publishedAt?: string;
  coverImage?: object;
  excerpt?: { fr?: string; en?: string };
};

export default async function ApelNewsPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const l = locale as "fr" | "en";
  const t = await getTranslations({ locale, namespace: "nav" });
  const tc = await getTranslations({ locale, namespace: "common" });
  const news: NewsItem[] = await client.fetch(newsApelQuery);

  return (
    <div className="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
      <h1 className="mb-8 text-3xl font-bold text-gray-900">{t("apelNews")}</h1>
      {news?.length > 0 ? (
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {news.map((item) => (
            <Link
              key={item._id}
              href={`/${locale}/apel/actualites/${item._id}`}
              className="group rounded-xl overflow-hidden border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow"
            >
              {item.coverImage && (
                <div className="aspect-video overflow-hidden">
                  <Image
                    src={urlFor(item.coverImage).width(600).height(340).url()}
                    alt=""
                    width={600}
                    height={340}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                </div>
              )}
              <div className="p-4">
                <p className="text-xs text-gray-400 mb-1">{item.publishedAt}</p>
                <h2 className="font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">
                  {item.title?.[l] ?? item.title?.fr ?? ""}
                </h2>
                {item.excerpt && (
                  <p className="mt-1 text-sm text-gray-600 line-clamp-2">
                    {item.excerpt[l] ?? item.excerpt.fr ?? ""}
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
