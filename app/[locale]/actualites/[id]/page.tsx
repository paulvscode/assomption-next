import { client } from "@/sanity/lib/client";
import { newsSchoolByIdQuery } from "@/sanity/lib/queries";
import PageContent from "@/components/ui/PageContent";
import { notFound } from "next/navigation";

export default async function SchoolNewsDetailPage({
  params,
}: {
  params: Promise<{ locale: string; id: string }>;
}) {
  const { locale, id } = await params;
  const post = await client.fetch(newsSchoolByIdQuery, { id });
  if (!post) notFound();
  return <PageContent title={post.title} body={post.body} locale={locale} />;
}
