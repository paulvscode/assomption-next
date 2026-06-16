import { client } from "@/sanity/lib/client";
import { eventByIdQuery } from "@/sanity/lib/queries";
import PageContent from "@/components/ui/PageContent";
import { notFound } from "next/navigation";

export default async function EventDetailPage({
  params,
}: {
  params: Promise<{ locale: string; id: string }>;
}) {
  const { locale, id } = await params;
  const ev = await client.fetch(eventByIdQuery, { id });
  if (!ev) notFound();
  return <PageContent title={ev.title} body={ev.body} locale={locale} />;
}
