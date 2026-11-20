<?php
/** @var C $c @var array $entry @var string $listUrl */
$l = locale();
$isEvent = $entry['type'] === 'event';
$cover = $c->media('coverImage');
if ($isEvent) {
    $start = $c->t('start');
    $end = $c->t('end');
    $when = format_date($start, $l, true);
    if ($end !== '') {
        $when = substr($start, 0, 10) === substr($end, 0, 10)
            ? $when . ' – ' . str_replace(':', $l === 'fr' ? 'h' : ':', substr($end, 11, 5))
            : tr('from') . ' ' . format_date($start, $l, true) . ' ' . tr('to') . ' ' . format_date($end, $l, true);
    }
} else {
    $when = format_date($c->t('date'), $l);
}
?>
<article>
  <div class="border-b border-border bg-surface py-12">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
      <a href="<?= e($listUrl) ?>" class="text-sm font-semibold text-primary/70 transition-colors hover:text-primary"><?= e(tr('back')) ?></a>
      <?php if ($when): ?><p class="mt-6 text-xs font-semibold uppercase tracking-widest text-muted"><?= e($when) ?></p><?php endif ?>
      <h1 class="mt-2 font-display text-3xl font-bold text-primary sm:text-4xl"><?= e($c->t('title')) ?></h1>
      <?php if ($isEvent && $c->t('location') !== ''): ?>
        <p class="mt-3 text-muted"><span aria-hidden="true">📍</span> <?= e(tr('location')) ?> : <?= e($c->t('location')) ?></p>
      <?php endif ?>
      <?php if ($c->t('excerpt') !== ''): ?>
        <p class="mt-4 text-lg leading-relaxed text-ink/80"><?= e($c->t('excerpt')) ?></p>
      <?php endif ?>
    </div>
  </div>

  <div class="mx-auto max-w-3xl space-y-10 px-4 py-12 sm:px-6 lg:px-8">
    <?php if ($cover): ?>
      <img src="<?= e(media_url($cover)) ?>" alt="<?= e(media_alt($cover)) ?>" class="w-full rounded-2xl object-cover shadow-sm" width="<?= (int) $cover['width'] ?>" height="<?= (int) $cover['height'] ?>">
    <?php endif ?>

    <?= prose($c->html('body')) ?>

    <?php $gallery = $c->gallery('gallery'); if ($gallery): ?>
      <section>
        <h2 class="mb-4 font-display text-xl font-semibold text-primary"><?= e(tr('photos')) ?></h2>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
          <?php foreach ($gallery as $m): ?>
            <a href="<?= e(media_url($m)) ?>" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl bg-surface">
              <img src="<?= e(media_url($m, true)) ?>" alt="<?= e(media_alt($m)) ?>" class="aspect-square w-full object-cover transition-transform duration-300 hover:scale-105" loading="lazy">
            </a>
          <?php endforeach ?>
        </div>
      </section>
    <?php endif ?>

    <?php $docs = docs_list($c->rows('documents')); if ($docs): ?>
      <section>
        <h2 class="mb-4 font-display text-xl font-semibold text-primary"><?= e(tr('attachments')) ?></h2>
        <?= $docs ?>
      </section>
    <?php endif ?>
  </div>
</article>
