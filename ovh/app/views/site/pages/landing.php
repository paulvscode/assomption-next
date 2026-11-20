<?php
/** @var C $c @var C $settings */
$l = locale();
$hero = $c->img('heroImage');
$news = $c->bool('newsEnabled') ? published_entries('news', 'ecole', 3) : [];
$events = [];
if ($c->bool('eventsEnabled')) {
    $today = date('Y-m-d\T00:00');
    $events = array_slice(array_values(array_filter(published_entries('event', 'apel'), fn ($e) => (($e['data']['end'] ?? '') ?: $e['date']) >= $today)), 0, 3);
}
$next = $c->bool('holidayEnabled') ? next_calendar_date() : null;
$bannerStyles = [
    'info' => 'bg-amber-400 text-amber-950',
    'urgent' => 'bg-red-700 text-white',
    'calm' => 'bg-sky-700 text-white',
];
$divider = function (string $id) {
    return '<div aria-hidden="true" class="text-accent"><svg width="100%" height="56" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="' . $id . '" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse"><rect width="28" height="28" fill="white"/><polygon points="14,4 24,14 14,24 4,14" fill="currentColor"/></pattern></defs><rect width="100%" height="56" fill="url(#' . $id . ')"/></svg></div>';
};
$phone = $settings->t('phone');
$parentLinks = array_filter($c->rows('parentLinks'), fn ($r) => $r->t('title') !== '' && $r->t('page') !== '');
$label = $settings->t('labelText');
$welcome = $c->bool('welcomeEnabled') ? $c->html('welcomeText') : '';
?>
<div>
  <?php if ($c->bool('bannerEnabled') && $c->t('bannerText') !== ''): ?>
    <div class="w-full px-4 py-3 text-center text-sm font-semibold shadow-sm <?= $bannerStyles[$c->t('bannerStyle', 'info')] ?? $bannerStyles['info'] ?>" role="status">
      <?= e($c->t('bannerText')) ?>
    </div>
  <?php endif ?>

  <section class="relative flex min-h-[64dvh] items-center justify-center overflow-hidden bg-primary py-16 text-white">
    <?php if ($hero): ?>
      <img src="<?= e($hero) ?>" alt="" class="animate-hero-zoom absolute inset-0 h-full w-full object-cover opacity-40" fetchpriority="high">
    <?php endif ?>
    <div class="relative z-10 mx-auto max-w-4xl px-6 text-center text-white [text-shadow:2px_2px_8px_rgba(0,0,0,0.7)]">
      <p class="mb-4 text-xs font-semibold uppercase tracking-[0.35em] text-white/85"><?= e($c->t('tagline')) ?></p>
      <h1 class="font-display text-4xl font-bold leading-tight text-white sm:text-6xl"><?= e($c->t('h1', $settings->t('schoolName'))) ?></h1>
      <?php if ($c->t('h2') !== ''): ?><p class="mt-3 font-display text-xl italic text-white sm:text-2xl"><?= e($c->t('h2')) ?></p><?php endif ?>
      <?php if ($c->t('statement') !== ''): ?><p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-white sm:text-lg"><?= e($c->t('statement')) ?></p><?php endif ?>
      <?php if ($label !== ''): ?>
        <p class="mt-6 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-sm font-semibold backdrop-blur-sm [text-shadow:none]"><?= site_icon('award', 'h-4 w-4') ?><?= e($label) ?></p>
      <?php endif ?>
      <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
        <a href="<?= e(site_url('notre-ecole/presentation')) ?>" class="rounded-full bg-white px-8 py-3.5 text-sm font-semibold text-primary shadow transition-opacity [text-shadow:none] hover:opacity-90"><?= e($c->t('cta', tr('readMore'))) ?></a>
        <a href="<?= e(site_url('informations-pratiques/localisation-contact')) ?>" class="rounded-full border border-white/70 px-8 py-3.5 text-sm font-semibold text-white transition-all [text-shadow:none] hover:bg-white/10"><?= e($c->t('ctaContact', tr('contactUs'))) ?></a>
      </div>
    </div>
  </section>

  <?php if ($parentLinks || $next): ?>
    <section class="relative z-10 -mt-10 px-4 sm:px-6 lg:px-8" aria-labelledby="parents-title">
      <div class="mx-auto max-w-6xl rounded-2xl border border-border bg-white p-5 shadow-lg sm:p-7">
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
          <h2 id="parents-title" class="font-display text-lg font-bold text-primary"><?= e($c->t('parentLinksTitle', tr('quickAccess'))) ?></h2>
        </div>
        <?php if ($parentLinks): ?>
          <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            <?php foreach ($parentLinks as $pl): ?>
              <li>
                <a href="<?= e(site_url($pl->t('page'))) ?>" class="group flex h-full flex-col items-center gap-2.5 rounded-xl border border-border bg-surface px-3 py-4 text-center transition-all hover:-translate-y-0.5 hover:border-accent hover:bg-white hover:shadow-md">
                  <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-primary shadow-sm ring-1 ring-border transition-colors group-hover:bg-accent group-hover:text-white group-hover:ring-accent"><?= site_icon($pl->t('icon', 'info')) ?></span>
                  <span class="text-sm leading-snug font-semibold text-primary"><?= e($pl->t('title')) ?></span>
                </a>
              </li>
            <?php endforeach ?>
          </ul>
        <?php endif ?>
        <?php if ($next): $nc = new C($next, $l); ?>
          <a href="<?= e(site_url('informations-pratiques/vacances')) ?>" class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-1 rounded-xl bg-primary-dark px-5 py-3.5 text-white transition-opacity hover:opacity-95">
            <span class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-white/75"><?= site_icon('sun', 'h-5 w-5') ?><?= e(tr('nextDate')) ?></span>
            <span class="font-semibold"><?= e($nc->t('name')) ?> — <?= e(period_text((string) $next['start'], (string) ($next['end'] ?? ''), $l)) ?></span>
            <span class="rounded-full bg-white/15 px-2.5 py-0.5 text-xs font-bold">
              <?= e($next['ongoing'] ? tr('ongoing') : ($next['days'] === 0 ? tr('today') : ($next['days'] === 1 ? tr('tomorrow') : sprintf(tr('inDays'), $next['days'])))) ?>
            </span>
            <span class="ml-auto text-sm font-semibold text-white/80"><?= e(tr('fullCalendar')) ?></span>
          </a>
        <?php endif ?>
      </div>
    </section>
  <?php endif ?>

  <?php if ($news || $events):
      $btn = 'mt-6 inline-flex items-center gap-2 rounded-full border border-primary/25 px-5 py-2.5 text-sm font-semibold text-primary transition-colors hover:border-primary hover:bg-surface';
      $h2 = 'mb-6 flex items-center gap-3 font-display text-2xl font-bold text-primary after:h-px after:flex-1 after:bg-border';
  ?>
    <section class="py-16">
      <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:px-8 <?= $news && $events ? 'lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]' : '' ?>">
        <?php if ($news): ?>
          <div class="flex flex-col">
            <h2 class="<?= $h2 ?>"><?= e(tr('news')) ?></h2>
            <div class="grid flex-1 content-start gap-6 sm:grid-cols-2 <?= $events ? '' : 'lg:grid-cols-3' ?>">
              <?php foreach (array_slice($news, 0, $events ? 2 : 3) as $n): ?><?= render('site/partials/news-card', ['entry' => $n]) ?><?php endforeach ?>
            </div>
            <div><a href="<?= e(site_url('actualites')) ?>" class="<?= $btn ?>"><?= e(tr('allNews')) ?> <span aria-hidden="true">→</span></a></div>
          </div>
        <?php endif ?>
        <?php if ($events): ?>
          <div class="flex flex-col">
            <h2 class="<?= $h2 ?>"><?= e(tr('upcomingEvents')) ?></h2>
            <ul class="flex-1 space-y-3">
              <?php foreach ($events as $ev): $ec = new C($ev['data'], $l); $ts = strtotime($ec->t('start')); ?>
                <li>
                  <a href="<?= e(entry_url($ev)) ?>" class="group flex items-center gap-4 rounded-2xl border border-border bg-white p-4 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md">
                    <span class="flex h-16 w-16 shrink-0 flex-col items-center justify-center rounded-xl bg-primary text-white">
                      <span class="font-display text-2xl leading-none font-bold"><?= date('j', $ts) ?></span>
                      <span class="mt-0.5 text-[11px] font-semibold uppercase"><?= e(short_month($ts, $l)) ?></span>
                    </span>
                    <span class="min-w-0">
                      <span class="block font-display font-bold text-primary group-hover:underline"><?= e($ec->t('title')) ?></span>
                      <span class="mt-0.5 block text-sm text-muted"><?= e(format_date($ec->t('start'), $l, true)) ?><?= $ec->t('location') !== '' ? ' · ' . e($ec->t('location')) : '' ?></span>
                    </span>
                  </a>
                </li>
              <?php endforeach ?>
            </ul>
            <div><a href="<?= e(site_url('apel/evenements')) ?>" class="<?= $btn ?>"><?= e(tr('allEvents')) ?> <span aria-hidden="true">→</span></a></div>
          </div>
        <?php endif ?>
      </div>
    </section>
  <?php endif ?>

  <?php if ($welcome !== ''): $photo = $c->media('welcomePhoto'); ?>
    <section class="bg-surface py-16">
      <div class="mx-auto flex max-w-5xl flex-col items-center gap-10 px-4 sm:px-6 md:flex-row lg:px-8">
        <?php if ($photo): ?>
          <img src="<?= e(media_url($photo, true)) ?>" alt="<?= e(media_alt($photo, $c->t('welcomeName'))) ?>" class="h-48 w-48 shrink-0 rounded-full object-cover shadow-md ring-4 ring-white" loading="lazy">
        <?php endif ?>
        <div>
          <h2 class="mb-4 font-display text-2xl font-bold text-primary"><?= e($c->t('welcomeTitle')) ?></h2>
          <?= prose($welcome, 'prose-lg') ?>
          <?php if ($c->t('welcomeName') !== ''): ?>
            <p class="mt-5 font-display font-semibold text-primary"><?= e($c->t('welcomeName')) ?><?php if ($c->t('welcomeRole') !== ''): ?><span class="font-normal text-muted"> — <?= e($c->t('welcomeRole')) ?></span><?php endif ?></p>
          <?php endif ?>
        </div>
      </div>
    </section>
  <?php endif ?>

  <?= $divider('diamonds-top') ?>

  <?php $links = $c->rows('quickLinks'); if ($links): ?>
  <section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="mb-10 text-center font-display text-3xl font-bold text-primary"><?= e($c->t('linksTitle')) ?></h2>
      <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($links as $i => $link): $img = $link->img('image', true); ?>
          <a href="<?= e(site_url($link->t('page'))) ?>" class="group flex flex-col overflow-hidden rounded-2xl border border-border bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md">
            <div class="relative overflow-hidden <?= $img ? 'aspect-video' : 'aspect-[16/5]' ?>">
              <?php if ($img): ?>
                <?= img_tag($img, '', 'absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105') ?>
              <?php else: ?>
                <div class="absolute inset-0 bg-primary" aria-hidden="true">
                  <svg class="absolute inset-0 h-full w-full text-accent opacity-25" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="ql-<?= $i ?>" width="28" height="28" patternUnits="userSpaceOnUse"><polygon points="14,4 24,14 14,24 4,14" fill="currentColor"/></pattern></defs><rect width="100%" height="100%" fill="url(#ql-<?= $i ?>)"/></svg>
                </div>
              <?php endif ?>
            </div>
            <div class="flex items-center justify-between px-5 py-4">
              <span class="font-display text-[1.0625rem] font-semibold text-primary"><?= e($link->t('title')) ?></span>
              <span class="text-accent transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">→</span>
            </div>
          </a>
        <?php endforeach ?>
      </div>
    </div>
  </section>
  <?= $divider('diamonds-bottom') ?>
  <?php endif ?>

  <section class="bg-primary-dark py-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <h2 class="mb-10 text-center font-display text-3xl font-bold text-white"><?= e($c->t('contactTitle', tr('contactUs'))) ?></h2>
      <div class="grid gap-8 text-center sm:grid-cols-3">
        <div>
          <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-white/70"><?= e(tr('address')) ?></p>
          <p class="font-semibold text-white"><?= $settings->nl('address') ?></p>
        </div>
        <div class="space-y-4">
          <?php if ($phone !== ''): ?>
          <div>
            <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-white/70"><?= e(tr('phone')) ?></p>
            <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phone)) ?>" class="font-semibold text-white hover:underline"><?= e($phone) ?></a>
          </div>
          <?php endif ?>
          <?php if ($settings->t('email') !== ''): ?>
          <div>
            <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-white/70"><?= e(tr('email')) ?></p>
            <a href="mailto:<?= e($settings->t('email')) ?>" class="break-all text-sm font-semibold text-white hover:underline"><?= e($settings->t('email')) ?></a>
          </div>
          <?php endif ?>
        </div>
        <div>
          <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-white/70"><?= e(tr('openingHours')) ?></p>
          <p class="font-semibold text-white"><?= $settings->nl('hours') ?></p>
        </div>
      </div>
    </div>
  </section>
</div>
