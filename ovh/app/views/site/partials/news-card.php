<?php
/** @var array $entry */
$c = new C($entry['data'], locale());
$img = $c->img('coverImage', true);
$isEvent = $entry['type'] === 'event';
$date = $isEvent ? format_date($c->t('start'), locale(), true) : format_date($c->t('date'), locale());
?>
<a href="<?= e(entry_url($entry)) ?>" class="group flex flex-col overflow-hidden rounded-2xl border border-border bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md<?= !empty($past) ? ' opacity-75' : '' ?>">
  <div class="relative aspect-video bg-surface">
    <?= img_tag($img, '', 'absolute inset-0 h-full w-full object-cover') ?>
  </div>
  <div class="flex flex-1 flex-col p-5">
    <?php if ($date): ?>
      <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-muted"><?= e($date) ?></p>
    <?php endif ?>
    <h3 class="line-clamp-2 font-display text-base font-bold text-primary"><?= e($c->t('title')) ?></h3>
    <?php if ($isEvent && $c->t('location') !== ''): ?>
      <p class="mt-1 text-sm text-muted"><span aria-hidden="true">📍</span> <?= e($c->t('location')) ?></p>
    <?php endif ?>
    <?php if ($c->t('excerpt') !== ''): ?>
      <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-ink/80"><?= e($c->t('excerpt')) ?></p>
    <?php endif ?>
  </div>
</a>
