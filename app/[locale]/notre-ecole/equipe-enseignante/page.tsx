import Image from "next/image";
import { client } from "@/sanity/lib/client";
import { singletonQuery } from "@/sanity/lib/queries";
import { urlFor } from "@/sanity/lib/image";

type Locale = "fr" | "en";

const content: Record<
  Locale,
  {
    title: string;
    subtitle: string;
    groups: { title: string; members: string[] }[];
    supportTitle: string;
    supportMembers: string[];
    principalLabel: string;
    principal: string;
    photoLabel: string;
  }
> = {
  fr: {
    title: "À l'École de l'Assomption, chaque membre de l'équipe pédagogique contribue au projet éducatif.",
    subtitle: "Année scolaire 2024 / 2025",
    groups: [
      {
        title: "Enseignantes de maternelle",
        members: [
          "Linda Zeghoubi — TPS/PS, aidée de Mélanie",
          "Sophie Patin — PS/MS, aidée d'Emmanuelle",
          "Lara Delamare — MS/GS, aidée de Béatrice",
          "Graziella Ledoux — GS, aidée de Mélanie",
        ],
      },
      {
        title: "Enseignants de primaire",
        members: [
          "Guillaume Saint Martin — CP",
          "Diana Mahrouchi — CE1",
          "Sarah Leray — CE1/CE2",
          "Audrey Prigent — CE2",
          "Vanessa Héry — CM1",
          "Florence Gosselin — CM1/CM2",
          "Océane Dubourg — CM2",
        ],
      },
      {
        title: "Enseignants pour les enfants à Besoins Éducatifs Particuliers",
        members: [
          "Fabien Varly-Le Bot — Poste d'adaptation (ASH)",
          "Marie Charlotte Dutertre — ULIS",
        ],
      },
      {
        title: "Enseignantes pour l'enseignement de l'anglais",
        members: [
          "Olivia Lea-Robinson — Classe anglophone d'Immersion Linguistique et Culturelle",
          "Laëtitia Laude — Anglais",
        ],
      },
    ],
    supportTitle: "L'équipe éducative comporte également",
    supportMembers: [
      "1 secrétaire-comptable : Sophie Kervella",
      "4 aides maternelles : Emmanuelle Lemieux, Mélanie Cabioch, Béatrice Fiquet et Mélanie Saint Martin",
      "1 assistante pédagogique / surveillante : Julie Desfontaine",
      "4 AESH mutualisées auprès de 8 enfants : Claire Hugonnet, Nathalie Leroux, Delphine Gréboval et Angéline Gouteux",
      "3 personnes à la cantine : Laura Degraeve, Angélina et Sylvie Robert",
    ],
    principalLabel: "Chef d'établissement",
    principal: "Le chef d'établissement de l'École de l'Assomption est Mme Isabelle QUEVAL.",
    photoLabel: "Photo de l'équipe",
  },
  en: {
    title: "At the École de l'Assomption, every member of the teaching team contributes to the educational project.",
    subtitle: "School year 2024 / 2025",
    groups: [
      {
        title: "Nursery teachers",
        members: [
          "Linda Zeghoubi — TPS/PS, assisted by Mélanie",
          "Sophie Patin — PS/MS, assisted by Emmanuelle",
          "Lara Delamare — MS/GS, assisted by Béatrice",
          "Graziella Ledoux — GS, assisted by Mélanie",
        ],
      },
      {
        title: "Primary teachers",
        members: [
          "Guillaume Saint Martin — CP",
          "Diana Mahrouchi — CE1",
          "Sarah Leray — CE1/CE2",
          "Audrey Prigent — CE2",
          "Vanessa Héry — CM1",
          "Florence Gosselin — CM1/CM2",
          "Océane Dubourg — CM2",
        ],
      },
      {
        title: "Teachers for children with Special Educational Needs",
        members: [
          "Fabien Varly-Le Bot — Adaptation post (ASH)",
          "Marie Charlotte Dutertre — ULIS",
        ],
      },
      {
        title: "English teaching staff",
        members: [
          "Olivia Lea-Robinson — English-language Immersion Class",
          "Laëtitia Laude — English",
        ],
      },
    ],
    supportTitle: "The educational team also includes",
    supportMembers: [
      "1 secretary-accountant: Sophie Kervella",
      "4 nursery assistants: Emmanuelle Lemieux, Mélanie Cabioch, Béatrice Fiquet and Mélanie Saint Martin",
      "1 teaching assistant / supervisor: Julie Desfontaine",
      "4 AESH shared among 8 children: Claire Hugonnet, Nathalie Leroux, Delphine Gréboval and Angéline Gouteux",
      "3 cafeteria staff: Laura Degraeve, Angélina and Sylvie Robert",
    ],
    principalLabel: "Head teacher",
    principal: "The head teacher of the École de l'Assomption is Mme Isabelle QUEVAL.",
    photoLabel: "Team photo",
  },
};

export default async function ProfessorsPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const c = content[(locale as Locale)] ?? content.fr;

  const page = await client.fetch(singletonQuery("professorsPage"));
  const teamPhotoUrl = page?.teamPhoto
    ? urlFor(page.teamPhoto).width(1200).height(675).url()
    : null;

  return (
    <div>
      <div className="border-b border-border bg-surface py-12">
        <div className="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
          <h1 className="font-display text-2xl font-bold text-primary sm:text-3xl">
            {c.title}
          </h1>
          <p className="mt-3 font-display text-lg italic text-muted">{c.subtitle}</p>
        </div>
      </div>

      <div className="mx-auto max-w-5xl px-4 py-14 sm:px-6 lg:px-8">
        {/* Teacher groups */}
        <div className="grid gap-6 sm:grid-cols-2">
          {c.groups.map((group, i) => (
            <div key={i} className="rounded-2xl border border-border bg-white p-6 shadow-sm">
              <h2 className="mb-4 font-display text-base font-semibold text-primary">
                {group.title}
              </h2>
              <ul className="space-y-2">
                {group.members.map((m, j) => (
                  <li key={j} className="flex items-start gap-2.5 text-sm text-[#2E2C31]">
                    <span className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary/50" />
                    {m}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        {/* Support staff */}
        <div className="mt-8 rounded-2xl border border-border bg-surface p-6">
          <h2 className="mb-4 font-display text-base font-semibold text-primary">
            {c.supportTitle}
          </h2>
          <ul className="space-y-2">
            {c.supportMembers.map((m, i) => (
              <li key={i} className="flex items-start gap-2.5 text-sm text-[#2E2C31]">
                <span className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary/50" />
                {m}
              </li>
            ))}
          </ul>
        </div>

        {/* Principal */}
        <div className="mt-8 rounded-2xl border-2 border-primary/20 bg-white p-6 text-center">
          <p className="text-xs font-semibold uppercase tracking-widest text-muted">
            {c.principalLabel}
          </p>
          <p className="mt-2 font-display text-lg font-semibold text-primary">{c.principal}</p>
        </div>

        {/* Team photo */}
        <div className="relative mt-10 aspect-video overflow-hidden rounded-2xl bg-[#ECEAED]">
          {teamPhotoUrl && (
            <Image
              src={teamPhotoUrl}
              alt={c.photoLabel}
              fill
              className="object-cover"
            />
          )}
        </div>
      </div>
    </div>
  );
}
