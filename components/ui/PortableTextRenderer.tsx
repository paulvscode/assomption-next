import { PortableText } from "@portabletext/react";
import type { PortableTextBlock } from "@portabletext/types";
import Image from "next/image";
import { urlFor } from "@/sanity/lib/image";

type Props = { value: PortableTextBlock[]; className?: string };

const components = {
  types: {
    image: ({ value }: { value: { asset: { _ref: string }; alt?: string } }) => (
      <div className="my-8 overflow-hidden rounded-xl">
        <Image
          src={urlFor(value).width(800).url()}
          alt={value.alt ?? ""}
          width={800}
          height={450}
          className="w-full object-cover"
        />
      </div>
    ),
  },
  block: {
    normal: ({ children }: { children?: React.ReactNode }) => (
      <p className="text-base leading-relaxed text-[#2E2C31] sm:text-lg">{children}</p>
    ),
    h2: ({ children }: { children?: React.ReactNode }) => (
      <h2 className="font-display text-2xl font-bold text-primary">{children}</h2>
    ),
    h3: ({ children }: { children?: React.ReactNode }) => (
      <h3 className="font-display text-xl font-semibold text-primary">{children}</h3>
    ),
    blockquote: ({ children }: { children?: React.ReactNode }) => (
      <blockquote className="border-l-4 border-primary/30 pl-4 italic text-muted">
        {children}
      </blockquote>
    ),
  },
  marks: {
    strong: ({ children }: { children?: React.ReactNode }) => (
      <strong className="font-semibold">{children}</strong>
    ),
    em: ({ children }: { children?: React.ReactNode }) => <em>{children}</em>,
    underline: ({ children }: { children?: React.ReactNode }) => (
      <span className="underline">{children}</span>
    ),
    link: ({ children, value }: { children?: React.ReactNode; value?: { href: string } }) => (
      <a
        href={value?.href}
        target="_blank"
        rel="noopener noreferrer"
        className="text-primary underline hover:opacity-75"
      >
        {children}
      </a>
    ),
  },
};

export default function PortableTextRenderer({ value, className }: Props) {
  return (
    <div className={className ?? "space-y-6"}>
      <PortableText value={value} components={components} />
    </div>
  );
}
