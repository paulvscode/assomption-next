import { client } from "@/sanity/lib/client";
import { newsOgecByIdQuery } from "@/sanity/lib/queries";
import PageContent from "@/components/ui/PageContent";
import { notFound } from "next/navigation";

export default async function OgecNewsDetailPage({
  params,
}: {
  params: Promise<{ locale: string; id: string }>;
}) {
  const { locale, id } = await params;
  const post = await client.fetch(newsOgecByIdQuery, { id });
  if (!post) notFound();
  return <PageContent title={post.title} body={post.body} locale={locale} />;
}
