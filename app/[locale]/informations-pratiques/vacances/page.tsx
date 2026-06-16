type Locale = "fr" | "en";

const content: Record<
  Locale,
  {
    title: string;
    periodLabel: string;
    datesLabel: string;
    rows: { period: string; dates: string }[];
  }
> = {
  fr: {
    title: "Calendrier des vacances",
    periodLabel: "Période",
    datesLabel: "Dates",
    rows: [
      { period: "Prérentrée des enseignants", dates: "Vendredi 30 août 2024" },
      { period: "Rentrée scolaire des élèves", dates: "Lundi 2 septembre 2024" },
      { period: "TOUSSAINT", dates: "Du vendredi 18 octobre 2024 au lundi 4 novembre 2024" },
      { period: "NOËL", dates: "Du vendredi 20 décembre 2024 au lundi 6 janvier 2025" },
      { period: "HIVER", dates: "Du vendredi 7 février 2025 au lundi 24 février 2025" },
      { period: "PRINTEMPS", dates: "Du vendredi 4 avril 2025 au lundi 22 avril 2025" },
      { period: "ASCENSION", dates: "Du mardi 27 mai 2025 au lundi 2 juin 2025" },
      { period: "PENTECÔTE", dates: "Du vendredi 6 juin 2025 au mardi 10 juin 2025" },
      { period: "ÉTÉ", dates: "Vendredi 4 juillet 2025" },
    ],
  },
  en: {
    title: "School breaks calendar",
    periodLabel: "Period",
    datesLabel: "Dates",
    rows: [
      { period: "Teachers' pre-term day", dates: "Friday 30 August 2024" },
      { period: "Pupils' first day", dates: "Monday 2 September 2024" },
      { period: "ALL SAINTS", dates: "Friday 18 October 2024 to Monday 4 November 2024" },
      { period: "CHRISTMAS", dates: "Friday 20 December 2024 to Monday 6 January 2025" },
      { period: "WINTER", dates: "Friday 7 February 2025 to Monday 24 February 2025" },
      { period: "SPRING", dates: "Friday 4 April 2025 to Monday 22 April 2025" },
      { period: "ASCENSION", dates: "Tuesday 27 May 2025 to Monday 2 June 2025" },
      { period: "WHITSUN", dates: "Friday 6 June 2025 to Tuesday 10 June 2025" },
      { period: "SUMMER", dates: "Friday 4 July 2025" },
    ],
  },
};

export default async function SchoolBreaksPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const c = content[(locale as Locale)] ?? content.fr;

  return (
    <div>
      <div className="border-b border-border bg-surface py-12">
        <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
          <h1 className="font-display text-3xl font-bold text-primary sm:text-4xl">{c.title}</h1>
        </div>
      </div>

      <div className="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
        <div className="overflow-x-auto rounded-2xl border border-border">
          <table className="w-full text-sm">
            <thead>
              <tr className="bg-surface">
                <th className="px-5 py-3 text-left font-semibold text-primary">
                  {c.periodLabel}
                </th>
                <th className="px-5 py-3 text-left font-semibold text-primary">
                  {c.datesLabel}
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border">
              {c.rows.map((row, i) => (
                <tr key={i} className={i % 2 === 0 ? "bg-white" : "bg-surface/50"}>
                  <td className="px-5 py-3 font-semibold text-primary">{row.period}</td>
                  <td className="px-5 py-3 text-[#2E2C31]">{row.dates}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
