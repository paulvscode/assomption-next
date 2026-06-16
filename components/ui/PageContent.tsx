import PortableTextRenderer from "./PortableTextRenderer";
import type { PortableTextBlock } from "@portabletext/types";

type LocalizedField<T> = { fr: T; en: T } | null | undefined;

type Props = {
  title?: LocalizedField<string>;
  body?: LocalizedField<PortableTextBlock[]>;
  locale: string;
  children?: React.ReactNode;
};

export default function PageContent({ title, body, locale, children }: Props) {
  const resolvedTitle = title?.[locale as "fr" | "en"] ?? title?.fr;
  const resolvedBody = body?.[locale as "fr" | "en"] ?? body?.fr;

  return (
    <div className="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
      {resolvedTitle && (
        <h1 className="mb-8 text-3xl font-bold text-gray-900 sm:text-4xl">{resolvedTitle}</h1>
      )}
      {resolvedBody && resolvedBody.length > 0 && (
        <div className="prose max-w-none">
          <PortableTextRenderer value={resolvedBody} />
        </div>
      )}
      {children}
    </div>
  );
}
