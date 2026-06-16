type Locale = "fr" | "en";

const content: Record<
  Locale,
  {
    title: string;
    phoneLabel: string;
    emailLabel: string;
    addressLabel: string;
    hoursLabel: string;
  }
> = {
  fr: {
    title: "Localisation & contact",
    phoneLabel: "Téléphone",
    emailLabel: "Email",
    addressLabel: "Adresse",
    hoursLabel: "Horaires d'ouverture",
  },
  en: {
    title: "Location & contact",
    phoneLabel: "Phone",
    emailLabel: "Email",
    addressLabel: "Address",
    hoursLabel: "Opening hours",
  },
};

export default async function LocationContactPage({
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

      <div className="mx-auto max-w-5xl px-4 py-14 sm:px-6 lg:px-8">
        {/* Map */}
        <div className="overflow-hidden rounded-2xl border border-border shadow-sm">
          <iframe
            src="https://maps.google.com/maps?q=32+rue+Lord+Kitchener+76600+Le+Havre&output=embed"
            width="100%"
            height="400"
            style={{ border: 0, display: "block" }}
            allowFullScreen
            loading="lazy"
            referrerPolicy="no-referrer-when-downgrade"
          />
        </div>

        {/* Contact info */}
        <div className="mt-10 grid gap-6 sm:grid-cols-3">
          <div className="rounded-2xl border border-border bg-white p-6 shadow-sm">
            <p className="mb-1 text-xs font-semibold uppercase tracking-widest text-muted">
              {c.phoneLabel}
            </p>
            <a
              href="tel:+33235436068"
              className="font-semibold text-primary hover:underline"
            >
              (+33) 02 35 43 60 68
            </a>
            <p className="mt-4 mb-1 text-xs font-semibold uppercase tracking-widest text-muted">
              {c.emailLabel}
            </p>
            <a
              href="mailto:dir.ec.assomption.lehavre@srec-hn.com"
              className="break-all text-sm font-semibold text-primary hover:underline"
            >
              dir.ec.assomption.lehavre@srec-hn.com
            </a>
          </div>

          <div className="rounded-2xl border border-border bg-white p-6 shadow-sm">
            <p className="mb-1 text-xs font-semibold uppercase tracking-widest text-muted">
              {c.addressLabel}
            </p>
            <p className="font-semibold text-primary">
              32, rue Lord Kitchener
              <br />
              76600 - Le Havre
            </p>
          </div>

          <div className="rounded-2xl border border-border bg-white p-6 shadow-sm">
            <p className="mb-1 text-xs font-semibold uppercase tracking-widest text-muted">
              {c.hoursLabel}
            </p>
            {locale === "fr" ? (
              <p className="font-semibold text-primary">
                Lundi, mardi, jeudi, vendredi
                <br />
                7h45 – 18h00
              </p>
            ) : (
              <p className="font-semibold text-primary">
                Monday, Tuesday, Thursday, Friday
                <br />
                7:45 am – 6:00 pm
              </p>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
