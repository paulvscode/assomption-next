<?php /** @var C $c */ $values = $c->lines('values'); $images = $c->gallery('images'); ?>
<?= page_header($c->t('title')) ?>
<?php $stats = array_filter($c->rows('stats'), fn ($r) => $r->t('value') !== ''); if ($stats): ?>
  <section class="border-b border-border bg-white" aria-label="<?= e(tr('keyFigures')) ?>">
    <dl class="mx-auto grid max-w-5xl grid-cols-2 <?= [1 => 'lg:grid-cols-1', 2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3'][count($stats)] ?? 'lg:grid-cols-4' ?>">
      <?php foreach ($stats as $st): ?>
        <div class="flex flex-col-reverse justify-end border-border px-5 py-7 text-center odd:border-r lg:border-r lg:last:border-r-0 [&:nth-child(-n+2)]:border-b lg:[&:nth-child(-n+2)]:border-b-0">
          <dt class="mt-1 text-sm leading-snug text-muted"><?= e($st->t('label')) ?></dt>
          <dd class="font-display text-2xl font-bold text-primary sm:text-3xl"><?= e($st->t('value')) ?></dd>
        </div>
      <?php endforeach ?>
    </dl>
  </section>
<?php endif ?>
<div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
  <?= prose($c->html('body'), 'prose-lg') ?>
  <?php if ($values): ?>
    <div class="mt-12 rounded-2xl border border-border bg-surface p-8">
      <h2 class="mb-5 font-display text-lg font-semibold text-primary"><?= e($c->t('valuesTitle')) ?></h2>
      <?= bullets($values, 'space-y-3', 'text-base') ?>
    </div>
  <?php endif ?>
  <?php if ($images): ?>
    <div class="mt-12 grid grid-cols-2 gap-4 lg:grid-cols-4">
      <?php foreach ($images as $m): ?>
        <div class="aspect-square overflow-hidden rounded-xl bg-[#ECEAED]">
          <img src="<?= e(media_url($m, true)) ?>" alt="<?= e(media_alt($m)) ?>" class="h-full w-full object-cover" loading="lazy">
        </div>
      <?php endforeach ?>
    </div>
  <?php endif ?>
</div>
