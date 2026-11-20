<?php
/** @var string $content @var string $title @var C $settings */
$l = locale();
$other = $l === 'fr' ? 'en' : 'fr';
$school = $settings->t('schoolName', "École de l'Assomption");
$fullTitle = $title !== '' ? "$title — $school" : $school;
$logo = $settings->img('logo');
$canonicalPath = $altPath !== null ? site_url($altPath, $l) : null;
$ogImage = $ogImage ?? '';
$editLabel = $editLabel ?? tr('editPage');
$accent = $settings->t('accentColor', '#8BB1C5');
$phone = $settings->t('phone');
$tel = preg_replace('/[^0-9+]/', '', $phone);
$address = str_replace("\n", ', ', $settings->t('address'));
$directions = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($address);
$enrolUrl = site_url('informations-pratiques/inscription');
$chevron = fn ($cls) => '<svg class="' . $cls . '" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 6l4 4 4-4"/></svg>';
$current = request_path();
?><!doctype html>
<html lang="<?= e($l) ?>" class="h-full">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($fullTitle) ?></title>
<?php if ($description !== ''): ?><meta name="description" content="<?= e($description) ?>"><?php endif ?>
<?php if (!empty($robots)): ?><meta name="robots" content="<?= e($robots) ?>"><?php endif ?>
<?php if ($canonicalPath !== null): ?>
<link rel="canonical" href="<?= e(base_url($canonicalPath)) ?>">
<link rel="alternate" hreflang="fr" href="<?= e(base_url(site_url($altPath, 'fr'))) ?>">
<link rel="alternate" hreflang="en" href="<?= e(base_url(site_url($altPath, 'en'))) ?>">
<?php endif ?>
<meta property="og:title" content="<?= e($fullTitle) ?>">
<meta property="og:type" content="website">
<?php if ($ogImage !== ''): ?><meta property="og:image" content="<?= e(base_url($ogImage)) ?>"><?php endif ?>
<link rel="preload" href="/assets/fonts/montserrat-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/fonts/nunito-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="/assets/site.css?v=<?= e((string) @filemtime(__DIR__ . '/../../../www/assets/site.css')) ?>">
<link rel="icon" href="/favicon.ico">
</head>
<body class="flex min-h-full flex-col pb-16 lg:pb-0" style="--color-accent: <?= e($accent) ?>">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:shadow"><?= e(tr('skip')) ?></a>

<header class="sticky top-0 z-40 w-full border-b border-border bg-white">
  <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:h-22 sm:px-6 lg:px-8">
    <a href="<?= e(site_url()) ?>" class="flex shrink-0 items-center gap-3 transition-opacity hover:opacity-75" aria-label="<?= e($school) ?>">
      <?php if ($logo): ?>
        <img src="<?= e($logo) ?>" alt="" class="h-12 w-auto object-contain sm:h-16" width="160" height="64">
      <?php endif ?>
      <span class="font-display text-base leading-tight font-bold text-primary sm:text-lg <?= $logo ? 'hidden md:block' : '' ?>"><?= e($school) ?></span>
    </a>

    <div class="flex flex-1 items-center justify-end gap-2 lg:gap-3">
      <nav aria-label="Menu principal" data-nav>
        <ul class="hidden items-center lg:flex">
          <?php foreach (site_menu() as $i => $item): ?>
            <li class="group relative" data-dropdown>
              <button type="button" class="flex items-center gap-1 px-3 py-2 text-[13px] font-semibold uppercase tracking-[0.06em] text-primary transition-opacity hover:opacity-60" aria-expanded="false" aria-controls="dd-<?= $i ?>">
                <?= e(tr($item['label'])) ?><?= $chevron('h-3.5 w-3.5 transition-transform duration-200 group-hover:rotate-180') ?>
              </button>
              <div id="dd-<?= $i ?>" class="dropdown pointer-events-none absolute left-0 top-full -translate-y-1 pt-2 opacity-0 transition-all duration-200 group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:translate-y-0 group-focus-within:opacity-100">
                <div class="min-w-64 rounded-xl border border-border bg-white py-2 shadow-lg">
                  <?php foreach ($item['groups'] as $gi => $g): ?>
                    <?php if ($g['heading']): ?>
                      <p class="mt-1 border-t border-border px-4 pt-3 pb-1 text-[11px] font-bold uppercase tracking-widest text-muted"><?= e(tr($g['heading'])) ?></p>
                    <?php endif ?>
                    <ul>
                      <?php foreach ($g['children'] as $child): $href = site_url($child['path']); ?>
                        <li><a href="<?= e($href) ?>" class="block px-4 py-2 text-[13.5px] text-primary transition-colors hover:bg-surface<?= $current === $href ? ' font-bold' : '' ?>"<?= $current === $href ? ' aria-current="page"' : '' ?>><?= e(tr($child['label'])) ?></a></li>
                      <?php endforeach ?>
                    </ul>
                  <?php endforeach ?>
                </div>
              </div>
            </li>
          <?php endforeach ?>
        </ul>

        <button type="button" class="flex h-10 w-10 items-center justify-center rounded-lg text-primary transition-colors hover:bg-surface lg:hidden" aria-label="<?= e(tr('menu')) ?>" aria-expanded="false" aria-controls="mobile-menu" data-mobile-toggle>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" class="h-6 w-6" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
        <div id="mobile-menu" hidden class="fixed inset-x-0 top-16 bottom-16 z-50 overflow-y-auto border-t border-border bg-white shadow-xl sm:top-22 lg:hidden">
          <a href="<?= e($enrolUrl) ?>" class="m-4 flex items-center justify-center gap-2 rounded-full bg-primary px-5 py-3 text-sm font-semibold text-white"><?= site_icon('enrol', 'h-5 w-5') ?><?= e(tr('enrol')) ?></a>
          <div class="divide-y divide-border border-t border-border">
            <?php foreach (site_menu() as $item): ?>
              <details>
                <summary class="flex w-full cursor-pointer list-none items-center justify-between px-5 py-4 text-[13px] font-semibold uppercase tracking-[0.06em] text-primary">
                  <?= e(tr($item['label'])) ?><?= $chevron('h-4 w-4') ?>
                </summary>
                <div class="bg-surface pb-2">
                  <?php foreach ($item['groups'] as $g): ?>
                    <?php if ($g['heading']): ?><p class="px-8 pt-3 pb-1 text-[11px] font-bold uppercase tracking-widest text-muted"><?= e(tr($g['heading'])) ?></p><?php endif ?>
                    <ul>
                      <?php foreach ($g['children'] as $child): ?>
                        <li><a href="<?= e(site_url($child['path'])) ?>" class="block px-8 py-3 text-[14px] text-primary transition-opacity hover:opacity-60"><?= e(tr($child['label'])) ?></a></li>
                      <?php endforeach ?>
                    </ul>
                  <?php endforeach ?>
                </div>
              </details>
            <?php endforeach ?>
          </div>
        </div>
      </nav>

      <?php if ($phone !== ''): ?>
        <a href="tel:<?= e($tel) ?>" class="hidden items-center gap-1.5 px-2 text-sm font-semibold whitespace-nowrap text-primary hover:underline xl:flex"><?= site_icon('phone', 'h-4 w-4') ?><?= e($phone) ?></a>
      <?php endif ?>
      <a href="<?= e($enrolUrl) ?>" class="hidden rounded-full bg-primary px-5 py-2.5 text-sm font-semibold whitespace-nowrap text-white transition-opacity hover:opacity-90 lg:inline-flex"><?= e(tr('enrol')) ?></a>
      <a href="<?= e($altPath !== null ? site_url($altPath, $other) : site_url('', $other)) ?>" hreflang="<?= $other ?>" lang="<?= $other ?>" class="rounded-full border border-border px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-primary transition-colors hover:bg-surface" title="<?= e(tr('switchTo')) ?>">
        <?= strtoupper($other) ?>
      </a>
    </div>
  </div>
</header>

<?php if (!empty($notice)): ?>
  <?php if (($noticeStyle ?? null) === 'soft'): ?>
    <div class="w-full border-b border-amber-200 bg-amber-50 px-4 py-2.5 text-center text-sm text-amber-900" role="status" lang="en"><?= e($notice) ?></div>
  <?php else: ?>
    <div class="w-full bg-primary-dark px-4 py-2.5 text-center text-sm font-semibold text-white" role="status"><?= e($notice) ?></div>
  <?php endif ?>
<?php endif ?>

<main id="main" class="flex-1"><?= $content ?></main>

<footer class="bg-primary text-white">
  <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
      <div>
        <p class="font-display text-lg font-semibold tracking-wide"><?= e($school) ?></p>
        <?php if ($settings->t('footerText') !== ''): ?><p class="mt-3 text-sm leading-relaxed text-white/70"><?= e($settings->t('footerText')) ?></p><?php endif ?>
        <address class="mt-4 space-y-1.5 text-sm not-italic text-white/80">
          <p><?= $settings->nl('address') ?></p>
          <?php if ($phone !== ''): ?><p><a href="tel:<?= e($tel) ?>" class="hover:underline"><?= e($phone) ?></a></p><?php endif ?>
          <?php if ($settings->t('email') !== ''): ?><p><a href="mailto:<?= e($settings->t('email')) ?>" class="break-all hover:underline"><?= e($settings->t('email')) ?></a></p><?php endif ?>
        </address>
        <?php $links = array_filter($settings->rows('links'), fn ($r) => $r->t('url') !== ''); if ($links): ?>
          <p class="mt-6 mb-2 text-[11px] font-semibold uppercase tracking-widest text-white/60"><?= e(tr('usefulLinks')) ?></p>
          <ul class="flex flex-wrap gap-2">
            <?php foreach ($links as $lk): ?>
              <li><a href="<?= e($lk->t('url')) ?>" target="_blank" rel="noopener noreferrer" class="inline-block rounded-full border border-white/25 px-3 py-1 text-xs font-semibold text-white/90 transition-colors hover:bg-white/10"><?= e($lk->t('label')) ?> ↗</a></li>
            <?php endforeach ?>
          </ul>
        <?php endif ?>
      </div>
      <?php foreach (site_menu() as $item): ?>
        <div>
          <p class="mb-4 text-[11px] font-semibold uppercase tracking-widest text-white/60"><?= e(tr($item['label'])) ?></p>
          <ul class="space-y-2.5">
            <?php foreach ($item['groups'] as $g): foreach ($g['children'] as $child): ?>
              <li><a href="<?= e(site_url($child['path'])) ?>" class="text-sm text-white/75 transition-colors hover:text-white"><?= e(($g['heading'] && in_array($g['heading'], ['apel', 'ogec'], true) ? strtoupper($g['heading']) . ' · ' : '') . tr($child['label'])) ?></a></li>
            <?php endforeach; endforeach ?>
          </ul>
        </div>
      <?php endforeach ?>
    </div>
  </div>
  <div class="border-t border-white/10">
    <div class="mx-auto flex max-w-7xl flex-col items-center gap-2 px-4 py-5 text-xs text-white/60 sm:flex-row sm:justify-between sm:px-6 lg:px-8">
      <p>© <?= date('Y') ?> <?= e($school) ?> — <?= e(tr('rights')) ?></p>
      <div class="flex gap-5">
        <a href="<?= e(site_url('mentions-legales')) ?>" class="transition-colors hover:text-white"><?= e(tr('legalNotice')) ?></a>
        <a href="<?= e(site_url('confidentialite')) ?>" class="transition-colors hover:text-white"><?= e(tr('privacyPolicy')) ?></a>
        <a href="/admin" class="transition-colors hover:text-white" rel="nofollow">Administration</a>
      </div>
    </div>
  </div>
</footer>

<nav class="fixed inset-x-0 bottom-0 z-40 grid h-16 grid-cols-3 border-t border-border bg-white/95 backdrop-blur lg:hidden" aria-label="<?= e(tr('contactShort')) ?>">
  <?php if ($phone !== ''): ?>
    <a href="tel:<?= e($tel) ?>" class="flex flex-col items-center justify-center gap-0.5 text-xs font-semibold text-primary"><?= site_icon('phone', 'h-5 w-5') ?><?= e(tr('call')) ?></a>
  <?php endif ?>
  <a href="<?= e($directions) ?>" target="_blank" rel="noopener" class="flex flex-col items-center justify-center gap-0.5 text-xs font-semibold text-primary"><?= site_icon('pin', 'h-5 w-5') ?><?= e(tr('directions')) ?></a>
  <a href="<?= e($enrolUrl) ?>" class="flex flex-col items-center justify-center gap-0.5 bg-primary text-xs font-semibold text-white"><?= site_icon('enrol', 'h-5 w-5') ?><?= e(tr('enrol')) ?></a>
</nav>

<?php if (!empty($editUrl)): ?>
  <a href="<?= e($editUrl) ?>" class="fixed right-4 bottom-20 z-50 inline-flex items-center gap-2 rounded-full bg-primary-dark px-5 py-3 text-sm font-semibold text-white shadow-lg ring-4 ring-white transition-transform hover:-translate-y-0.5 lg:right-5 lg:bottom-5">
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
    <?= e($editLabel) ?>
  </a>
<?php endif ?>
<script src="/assets/site.js" defer></script>
</body>
</html>
