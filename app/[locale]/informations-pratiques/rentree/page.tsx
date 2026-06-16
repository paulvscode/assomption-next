type Locale = "fr" | "en";

const content: Record<
  Locale,
  {
    title: string;
    intro: string[];
    table1Title: string;
    table1: { time: string; classes: string }[];
    table2Title: string;
    table2: { day: string; tpsPs: string; psMs: string }[];
    noteText: string;
    listsTitle: string;
    lists: string[];
    comingSoon: string;
  }
> = {
  fr: {
    title: "Rentrée scolaire",
    intro: [
      "Notre école fonctionnera sur 4 jours : lundi, mardi, jeudi, vendredi.",
      "Horaires de classe : 8h30 – 11h45, 13h30 – 16h30 (hors protocole sanitaire).",
      "La rentrée aura lieu le lundi 2 septembre (rentrée échelonnée pour les classes de TPS/PS et PS/MS).",
    ],
    table1Title: "Horaires de rentrée",
    table1: [
      { time: "8h30", classes: "MS/GS, CE1, CE2/CM1, CM1/CM2, CM2, ULIS" },
      { time: "8h45", classes: "TPS/PS, PS/MS" },
      { time: "9h00", classes: "GS, CP, CP/CE1, CE2" },
    ],
    table2Title: "Rentrée échelonnée TPS/PS et PS/MS",
    table2: [
      { day: "Lundi 2 septembre", tpsPs: "1er groupe à 8h45", psMs: "1er groupe à 8h45" },
      { day: "Mardi 3 septembre", tpsPs: "2e groupe à 8h45", psMs: "2e groupe à 8h45" },
      { day: "Jeudi 5 septembre", tpsPs: "Classe entière à 8h30", psMs: "Classe entière à 8h30" },
    ],
    noteText:
      "Vous trouverez d'autres précisions en consultant la Note de rentrée 2024.",
    listsTitle:
      "Listes des fournitures (cliquez pour ouvrir et télécharger) :",
    lists: [
      "Fournitures TPS-PS (Linda Zeghoubi)",
      "Fournitures PS Classe de PS-MS (Sophie Patin)",
      "Fournitures MS Classe de PS-MS (Sophie Patin)",
      "Fournitures MS Classe de MS-GS (Lara Delamare)",
      "Fournitures GS Classe de MS-GS (Lara Delamare)",
      "Fournitures GS/CP (Graziella Ledoux)",
      "Fournitures CP (Guillaume Saint Martin)",
      "Fournitures CP Classe de CP-CE1",
      "Fournitures CE1 Classe de CE1 (Diana Mahrouchi)",
      "Fournitures CE1/CE2 (Sarah Leray)",
      "Fournitures CE2 (Audrey Prigent)",
      "Fournitures CM1 (Vanessa Héry)",
      "Fournitures CM1-CM2 (Florence Gosselin)",
      "Fournitures CM2 (Océane Dubourg)",
    ],
    comingSoon: "À venir",
  },
  en: {
    title: "Back to school",
    intro: [
      "Our school operates on 4 days: Monday, Tuesday, Thursday, Friday.",
      "School hours: 8:30 am – 11:45 am, 1:30 pm – 4:30 pm (outside health protocol).",
      "The new school year begins on Monday 2 September (staggered entry for TPS/PS and PS/MS classes).",
    ],
    table1Title: "Entry timetable",
    table1: [
      { time: "8:30 am", classes: "MS/GS, CE1, CE2/CM1, CM1/CM2, CM2, ULIS" },
      { time: "8:45 am", classes: "TPS/PS, PS/MS" },
      { time: "9:00 am", classes: "GS, CP, CP/CE1, CE2" },
    ],
    table2Title: "Staggered entry — TPS/PS and PS/MS",
    table2: [
      { day: "Monday 2 September", tpsPs: "1st group at 8:45 am", psMs: "1st group at 8:45 am" },
      { day: "Tuesday 3 September", tpsPs: "2nd group at 8:45 am", psMs: "2nd group at 8:45 am" },
      { day: "Thursday 5 September", tpsPs: "Full class at 8:30 am", psMs: "Full class at 8:30 am" },
    ],
    noteText: "Further details are available in the 2024 Back-to-School Note.",
    listsTitle: "Supply lists (click to open and download):",
    lists: [
      "Supplies TPS-PS (Linda Zeghoubi)",
      "Supplies PS — PS-MS class (Sophie Patin)",
      "Supplies MS — PS-MS class (Sophie Patin)",
      "Supplies MS — MS-GS class (Lara Delamare)",
      "Supplies GS — MS-GS class (Lara Delamare)",
      "Supplies GS/CP (Graziella Ledoux)",
      "Supplies CP (Guillaume Saint Martin)",
      "Supplies CP — CP-CE1 class",
      "Supplies CE1 — CE1 class (Diana Mahrouchi)",
      "Supplies CE1/CE2 (Sarah Leray)",
      "Supplies CE2 (Audrey Prigent)",
      "Supplies CM1 (Vanessa Héry)",
      "Supplies CM1-CM2 (Florence Gosselin)",
      "Supplies CM2 (Océane Dubourg)",
    ],
    comingSoon: "Coming soon",
  },
};

export default async function BackToSchoolPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const c = content[(locale as Locale)] ?? content.fr;

  return (
    <div>
      <div className="border-b border-border bg-surface py-12">
        <div className="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
          <h1 className="font-display text-3xl font-bold text-primary sm:text-4xl">{c.title}</h1>
        </div>
      </div>

      <div className="mx-auto max-w-5xl px-4 py-14 sm:px-6 lg:px-8 space-y-12">
        {/* Intro text */}
        <div className="space-y-2">
          {c.intro.map((line, i) => (
            <p key={i} className="text-base text-[#2E2C31]">
              {line}
            </p>
          ))}
        </div>

        {/* Table 1 */}
        <div>
          <h2 className="mb-4 font-display text-xl font-semibold text-primary">{c.table1Title}</h2>
          <div className="overflow-x-auto rounded-2xl border border-border">
            <table className="w-full text-sm">
              <thead>
                <tr className="bg-surface">
                  <th className="px-4 py-3 text-left font-semibold text-primary">
                    {locale === "fr" ? "Heure" : "Time"}
                  </th>
                  <th className="px-4 py-3 text-left font-semibold text-primary">
                    {locale === "fr" ? "Classes" : "Classes"}
                  </th>
                </tr>
              </thead>
              <tbody className="divide-y divide-border">
                {c.table1.map((row, i) => (
                  <tr key={i} className="bg-white">
                    <td className="px-4 py-3 font-semibold text-primary">{row.time}</td>
                    <td className="px-4 py-3 text-[#2E2C31]">{row.classes}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Table 2 */}
        <div>
          <h2 className="mb-4 font-display text-xl font-semibold text-primary">{c.table2Title}</h2>
          <div className="overflow-x-auto overflow-hidden rounded-2xl border border-border">
            <table className="w-full text-sm">
              <thead>
                <tr className="bg-surface">
                  <th className="px-4 py-3 text-left font-semibold text-primary">
                    {locale === "fr" ? "Jour" : "Day"}
                  </th>
                  <th className="px-4 py-3 text-left font-semibold text-primary">TPS/PS</th>
                  <th className="px-4 py-3 text-left font-semibold text-primary">PS/MS</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-border">
                {c.table2.map((row, i) => (
                  <tr key={i} className="bg-white">
                    <td className="px-4 py-3 font-semibold text-primary">{row.day}</td>
                    <td className="px-4 py-3 text-[#2E2C31]">{row.tpsPs}</td>
                    <td className="px-4 py-3 text-[#2E2C31]">{row.psMs}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Note text */}
        <p className="text-sm italic text-muted">{c.noteText}</p>

        {/* Supply lists */}
        <div>
          <h2 className="mb-4 font-display text-xl font-semibold text-primary">{c.listsTitle}</h2>
          <ul className="grid grid-cols-1 gap-2 sm:grid-cols-2">
            {c.lists.map((item, i) => (
              <li
                key={i}
                className="flex items-center gap-3 rounded-xl border border-border bg-surface px-4 py-3 text-sm"
              >
                <span className="text-muted">↓</span>
                <span className="text-[#2E2C31]">{item}</span>
                <span className="ml-auto text-xs text-muted">{c.comingSoon}</span>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  );
}
