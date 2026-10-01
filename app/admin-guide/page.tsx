import Link from "next/link";

const sections = [
  { id: "acces",       label: "Accès & connexion" },
  { id: "dashboard",   label: "Écran d'accueil" },
  { id: "parametres",  label: "Paramètres du site" },
  { id: "accueil",     label: "Page d'accueil" },
  { id: "actualites",  label: "Actualités école" },
  { id: "pages",       label: "Pages de l'école" },
  { id: "apel",        label: "APEL" },
  { id: "ogec",        label: "OGEC" },
  { id: "publier",     label: "Publier & gérer vos contenus" },
];

function Section({ id, title, children }: { id: string; title: string; children: React.ReactNode }) {
  return (
    <section id={id} className="scroll-mt-8 border-b border-border pb-12 pt-10 first:pt-0">
      <h2 className="mb-6 font-display text-2xl font-bold text-primary">{title}</h2>
      <div className="space-y-4 text-sm leading-relaxed text-[#2E2C31]/80">{children}</div>
    </section>
  );
}

function Step({ n, children }: { n: number; children: React.ReactNode }) {
  return (
    <div className="flex gap-4">
      <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary text-xs font-bold text-white">
        {n}
      </span>
      <p>{children}</p>
    </div>
  );
}

function Field({ name, desc }: { name: string; desc: string }) {
  return (
    <div className="flex gap-3 rounded-lg border border-border bg-surface px-4 py-3">
      <span className="shrink-0 font-mono text-xs font-semibold text-primary">{name}</span>
      <span className="text-xs text-[#2E2C31]/70">{desc}</span>
    </div>
  );
}

function Tip({ children }: { children: React.ReactNode }) {
  return (
    <div className="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
      <span className="font-semibold">Bon à savoir — </span>{children}
    </div>
  );
}

function Path({ href }: { href: string }) {
  return (
    <code className="rounded bg-surface px-2 py-0.5 font-mono text-xs text-primary border border-border">
      {href}
    </code>
  );
}

export default function AdminGuidePage() {
  return (
    <div className="min-h-screen bg-white">

      {/* Header */}
      <div className="border-b border-border bg-primary px-6 py-6">
        <div className="mx-auto max-w-6xl flex items-center justify-between">
          <div>
            <h1 className="font-display text-2xl font-bold text-white">Guide administrateur</h1>
            <p className="mt-1 text-sm text-white/60">École de l&apos;Assomption — Documentation interne</p>
          </div>
          <Link
            href="/studio"
            className="rounded-lg border border-white/20 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-white/10"
          >
            Ouvrir le studio →
          </Link>
        </div>
      </div>

      <div className="mx-auto max-w-6xl px-6 py-10 lg:flex lg:gap-12">

        {/* Sidebar */}
        <aside className="hidden lg:block lg:w-56 shrink-0">
          <div className="sticky top-8 space-y-1">
            <p className="mb-3 text-[10px] font-semibold uppercase tracking-widest text-muted">
              Sommaire
            </p>
            {sections.map((s) => (
              <a
                key={s.id}
                href={`#${s.id}`}
                className="block rounded-lg px-3 py-2 text-sm text-[#2E2C31]/70 transition-colors hover:bg-surface hover:text-primary"
              >
                {s.label}
              </a>
            ))}
          </div>
        </aside>

        {/* Content */}
        <main className="min-w-0 flex-1">

          {/* ── Accès & connexion ───────────────────────────────────────── */}
          <Section id="acces" title="1. Accès & connexion">
            <p>
              Le site dispose de <strong>trois espaces d&apos;administration</strong> distincts, chacun accessible via une URL et un mot de passe différents.
            </p>

            <div className="space-y-3">
              <div className="rounded-xl border border-border p-4">
                <p className="mb-1 font-semibold text-primary">Administration principale</p>
                <p className="mb-2 text-xs text-muted">Accès complet à tout le contenu du site.</p>
                <Path href="/login" />
                <span className="mx-2 text-muted">→</span>
                <Path href="/studio" />
              </div>

              <div className="rounded-xl border border-border p-4">
                <p className="mb-1 font-semibold text-primary">Espace APEL</p>
                <p className="mb-2 text-xs text-muted">Accès limité aux actualités APEL et aux événements.</p>
                <Path href="/login-apel" />
                <span className="mx-2 text-muted">→</span>
                <Path href="/studio-apel" />
              </div>

              <div className="rounded-xl border border-border p-4">
                <p className="mb-1 font-semibold text-primary">Espace OGEC</p>
                <p className="mb-2 text-xs text-muted">Accès limité aux actualités OGEC.</p>
                <Path href="/login-ogec" />
                <span className="mx-2 text-muted">→</span>
                <Path href="/studio-ogec" />
              </div>
            </div>

            <Tip>
              Les mots de passe sont définis dans la configuration du serveur. Contactez le développeur pour les modifier.
            </Tip>
          </Section>

          {/* ── Écran d'accueil ─────────────────────────────────────────── */}
          <Section id="dashboard" title="2. Écran d'accueil">
            <p>
              Une fois connecté, vous arrivez directement sur l&apos;écran <strong>Accueil</strong> du studio — premier élément de la barre latérale. Il ne s&apos;agit pas d&apos;une liste de contenus, mais d&apos;un ensemble de cartes correspondant aux actions les plus courantes : publier une actualité, mettre à jour l&apos;équipe, modifier les paramètres du site.
            </p>
            <p>
              Pour modifier une page précise (page d&apos;accueil, présentation, infos pratiques, APEL, OGEC...), cliquez sur la carte <strong>Modifier le contenu d&apos;une page</strong>. Elle ouvre un écran qui liste toutes les pages du site, regroupées par thème — cliquez sur une page pour l&apos;ouvrir directement en édition.
            </p>
            <p>
              Les espaces APEL et OGEC ont leur propre écran d&apos;accueil, avec uniquement les actions qui les concernent.
            </p>
          </Section>

          {/* ── Paramètres du site ──────────────────────────────────────── */}
          <Section id="parametres" title="3. Paramètres du site">
            <p>
              Dans le studio principal, cliquez sur <strong>Paramètres du site</strong> (dans la barre latérale, ou depuis la carte correspondante sur l&apos;écran d&apos;accueil).
            </p>
            <div className="space-y-2">
              <Field name="Logo" desc="Image affichée dans la barre de navigation. Format recommandé : PNG transparent, ratio 3:1 (ex. 300×100 px)." />
              <Field name="Couleur d'accent" desc="Couleur des séparateurs et de la section liens rapides. Utilisez le sélecteur de couleur ou entrez un code hexadécimal (ex. #8BB1C5)." />
            </div>
            <Tip>Après avoir modifié le logo ou la couleur, cliquez sur Publier pour que les changements soient visibles sur le site.</Tip>
          </Section>

          {/* ── Page d'accueil ──────────────────────────────────────────── */}
          <Section id="accueil" title="4. Page d'accueil">
            <p>
              Dans le studio, ouvrez <strong>Notre école → Page d&apos;accueil</strong> (ou <strong>Modifier le contenu d&apos;une page → Page d&apos;accueil</strong> depuis l&apos;écran d&apos;accueil). Les champs sont regroupés en <strong>cinq onglets</strong>, affichés en haut du formulaire, plutôt qu&apos;en une seule longue liste : Bannière, Actualités, Bandeau principal, Liens rapides, Contact.
            </p>

            <h3 className="font-display font-semibold text-primary">Bannière d&apos;annonce</h3>
            <p>Permet d&apos;afficher un message visible immédiatement sous la barre de navigation.</p>
            <div className="space-y-2">
              <Field name="Bannière active" desc="Interrupteur ON/OFF. Désactivée par défaut. Activez-la pour afficher le bandeau sur le site." />
              <Field name="Texte de la bannière" desc="Message à afficher. Utilisez l'interrupteur FR/EN au-dessus du champ pour basculer entre les deux langues. Ex. : Journée portes ouvertes le samedi 12 avril." />
            </div>

            <h3 className="font-display font-semibold text-primary">Actualités</h3>
            <div className="space-y-2">
              <Field name="Section actualités active" desc="Interrupteur ON/OFF. Affiche ou masque le bloc des 3 dernières actualités sur la page d'accueil." />
            </div>

            <h3 className="font-display font-semibold text-primary">Bandeau principal (hero)</h3>
            <div className="space-y-2">
              <Field name="Image hero" desc="Grande photo affichée en arrière-plan. Format paysage recommandé (1920×1080 px minimum)." />
              <Field name="Accroche" desc="Petite ligne au-dessus du titre. Ex. : Le Havre · École catholique." />
              <Field name="Titre principal (H1)" desc="Grand titre de la page. Ex. : École de l'Assomption." />
              <Field name="Sous-titre (H2)" desc="Phrase en italique sous le titre. Ex. : Une école ouverte à l'autre." />
              <Field name="Paragraphe d'introduction" desc="Texte de présentation visible sous le sous-titre." />
              <Field name="Bouton principal" desc="Texte du bouton qui mène vers la page de présentation." />
              <Field name="Bouton contact" desc="Texte du bouton qui mène vers la page de contact." />
            </div>

            <h3 className="font-display font-semibold text-primary">Liens rapides</h3>
            <p>6 cartes avec image et titre, menant vers des pages de l&apos;école.</p>
            <div className="space-y-2">
              <Field name="Titre" desc="Titre de la section. Ex. : Liens rapides." />
              <Field name="Lien rapide — [nom]" desc="Image de chaque carte. Format paysage recommandé (640×360 px)." />
            </div>

            <h3 className="font-display font-semibold text-primary">Contact</h3>
            <div className="space-y-2">
              <Field name="Titre" desc="Titre de la section contact." />
              <Field name="Adresse" desc="Adresse postale de l'école (affichée sur 2 lignes)." />
              <Field name="Téléphone" desc="Numéro de téléphone affiché et cliquable." />
              <Field name="Email" desc="Adresse email affichée et cliquable." />
              <Field name="Horaires d'ouverture" desc="Jours et heures d'ouverture." />
            </div>

            <h3 className="font-display font-semibold text-primary">Aperçu en direct</h3>
            <p>
              Dans le studio principal, l&apos;onglet <strong>Aperçu en direct</strong> (en haut de l&apos;écran, à côté de Structure) affiche le formulaire et le site côte à côte : vos modifications apparaissent dans l&apos;aperçu au fur et à mesure, avant même de publier.
            </p>
            <Tip>
              Pour l&apos;instant, l&apos;aperçu en direct ne couvre que la page d&apos;accueil. Les autres pages restent modifiables normalement, mais sans aperçu en temps réel.
            </Tip>
          </Section>

          {/* ── Actualités école ────────────────────────────────────────── */}
          <Section id="actualites" title="5. Actualités de l'école">
            <p>
              Dans la barre latérale du studio, cliquez sur <strong>Actualités école</strong>, ou sur la carte <strong>Publier une actualité</strong> depuis l&apos;écran d&apos;accueil. Vous verrez la liste de tous les articles existants.
            </p>

            <h3 className="font-display font-semibold text-primary">Créer un nouvel article</h3>
            <div className="space-y-3">
              <Step n={1}>Cliquez sur le bouton <strong>+ Créer</strong> en haut à droite.</Step>
              <Step n={2}>Remplissez les champs (voir ci-dessous). L&apos;onglet <strong>Aide</strong>, à côté de <strong>Contenu</strong>, rappelle à quoi sert chaque champ directement dans le document.</Step>
              <Step n={3}>Cliquez sur <strong>Publier</strong> en bas à droite pour que l&apos;article soit visible sur le site.</Step>
            </div>

            <div className="space-y-2">
              <Field name="Titre" desc="Titre de l'article." />
              <Field name="Date de publication" desc="Date affichée sur la carte et l'article. Utilisée pour trier les articles du plus récent au plus ancien." />
              <Field name="Image" desc="Photo principale de l'article (format paysage recommandé)." />
              <Field name="Résumé court" desc="Texte court affiché sur la carte de la page d'accueil (2-3 phrases maximum)." />
              <Field name="Contenu" desc="Corps de l'article complet, avec mise en forme (titres, listes, liens...)." />
            </div>

            <Tip>
              Les 3 articles les plus récents (par date de publication) apparaissent automatiquement sur la page d&apos;accueil si la section actualités est activée.
            </Tip>
          </Section>

          {/* ── Pages de l'école ────────────────────────────────────────── */}
          <Section id="pages" title="6. Pages de l'école">
            <p>
              Toutes les pages de contenu sont accessibles depuis la section <strong>Notre école</strong> dans la barre latérale.
            </p>

            <div className="space-y-2">
              <Field name="Présentation de l'école" desc="Texte de présentation riche (titres, paragraphes), liste des valeurs, et 4 photos." />
              <Field name="Projet éducatif" desc="Contenu éditorial de la page projet éducatif." />
              <Field name="Projet d'établissement" desc="Contenu éditorial du projet d'établissement." />
              <Field name="Mission pastorale" desc="Contenu de la page pastorale." />
              <Field name="Programme anglais" desc="Contenu de la page programme anglais." />
              <Field name="Équipe enseignante" desc="Page de présentation + liste des membres (photo, nom, rôle, biographie)." />
            </div>

            <p>Les pages <strong>Informations pratiques</strong> (inscription, frais, restauration, vacances...) fonctionnent de la même manière, accessibles depuis la section correspondante.</p>

            <p>
              Chaque page dispose d&apos;un contenu en français et en anglais. Au lieu d&apos;afficher les deux à la fois, un petit interrupteur <strong>FR / EN</strong> au-dessus de chaque champ permet de basculer d&apos;une langue à l&apos;autre — un point creux à côté d&apos;EN signale qu&apos;il n&apos;a pas encore été rempli.
            </p>

            <Tip>
              Si le champ anglais est laissé vide, le site affichera le texte français par défaut.
            </Tip>
          </Section>

          {/* ── APEL ────────────────────────────────────────────────────── */}
          <Section id="apel" title="7. APEL">
            <p>
              L&apos;espace APEL est accessible de deux façons : depuis le studio principal (section APEL) ou depuis l&apos;espace dédié <Path href="/studio-apel" /> avec le mot de passe APEL.
            </p>

            <div className="space-y-2">
              <Field name="Présentation APEL" desc="Texte de présentation de l'association des parents d'élèves." />
              <Field name="Actualités APEL" desc="Articles de l'APEL : titre, date, image, résumé, contenu." />
              <Field name="Événements" desc="Calendrier des événements APEL : titre, date de début/fin, lieu, description." />
            </div>

            <Tip>
              Les membres de l&apos;APEL utilisent l&apos;adresse <Path href="/login-apel" /> avec leur propre mot de passe. Ils ne voient que les actualités et événements APEL, avec leur propre écran d&apos;accueil.
            </Tip>
          </Section>

          {/* ── OGEC ────────────────────────────────────────────────────── */}
          <Section id="ogec" title="8. OGEC">
            <p>
              Même fonctionnement que l&apos;APEL. Accessible depuis le studio principal (section OGEC) ou depuis <Path href="/studio-ogec" /> avec le mot de passe OGEC.
            </p>

            <div className="space-y-2">
              <Field name="Présentation OGEC" desc="Texte de présentation de l'organisme de gestion." />
              <Field name="Actualités OGEC" desc="Articles de l'OGEC : titre, date, image, résumé, contenu." />
            </div>
          </Section>

          {/* ── Publier ─────────────────────────────────────────────────── */}
          <Section id="publier" title="9. Publier & gérer vos contenus">
            <p>
              Dans Sanity Studio, les modifications ne sont <strong>pas automatiquement visibles</strong> sur le site. Il faut les publier manuellement.
            </p>

            <div className="space-y-3">
              <Step n={1}>Faites vos modifications dans les champs du document.</Step>
              <Step n={2}>Cliquez sur le bouton vert <strong>Publier</strong> en bas à droite de l&apos;écran.</Step>
              <Step n={3}>Le site se met à jour dans les secondes qui suivent.</Step>
            </div>

            <p>
              Un bandeau près du titre du document indique clairement l&apos;état de la page : <strong>« En ligne »</strong> si elle correspond à ce qui est visible sur le site, <strong>« Modifications non publiées »</strong> s&apos;il reste des changements à publier, ou <strong>« Jamais publié »</strong> pour un contenu créé mais encore invisible sur le site.
            </p>

            <h3 className="font-display font-semibold text-primary">Retirer un contenu du site</h3>
            <p>
              Il n&apos;est plus possible de supprimer définitivement un document depuis le studio. Pour retirer une actualité ou une page du site, utilisez <strong>Annuler la publication</strong> (dans le menu ••• en haut à droite du document) : le contenu disparaît du site mais reste enregistré, et peut être republié à tout moment en cliquant de nouveau sur <strong>Publier</strong>.
            </p>

            <Tip>
              Aucune action dans le studio n&apos;efface définitivement un contenu. En cas de doute, annulez la publication plutôt que de laisser un contenu que vous n&apos;êtes plus sûr de vouloir.
            </Tip>
          </Section>

          {/* Footer */}
          <div className="pt-10 text-center text-xs text-muted">
            Guide administrateur — École de l&apos;Assomption — Usage interne uniquement
          </div>

        </main>
      </div>
    </div>
  );
}
