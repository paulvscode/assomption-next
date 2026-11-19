<?php /** @var C $c */ $doc = $c->media('document'); ?>
<div class="border-b border-border bg-surface py-12">
  <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
    <h1 class="font-display text-3xl font-bold text-primary sm:text-4xl"><?= e($c->t('title')) ?></h1>
    <?php if ($c->t('intro') !== ''): ?><p class="mt-4 max-w-3xl text-base text-muted"><?= e($c->t('intro')) ?></p><?php endif ?>
  </div>
</div>
<div class="mx-auto max-w-5xl space-y-10 px-4 py-14 sm:px-6 lg:px-8">
  <?php foreach ($c->rows('cards') as $i => $card): $img = $card->media('image'); ?>
    <div class="flex flex-col gap-8 lg:flex-row<?= $i % 2 ? ' lg:flex-row-reverse' : '' ?>">
      <div class="flex-1 rounded-2xl border border-border bg-white p-8 shadow-sm">
        <?php if ($card->t('badge') !== ''): ?>
          <span class="mb-3 inline-block rounded-full border border-border bg-surface px-3 py-1 text-xs font-semibold uppercase tracking-widest text-muted"><?= e($card->t('badge')) ?></span>
        <?php endif ?>
        <h2 class="mb-4 font-display text-xl font-bold text-primary"><?= e($card->t('title')) ?></h2>
        <?php if ($card->t('description') !== ''): ?><p class="mb-6 text-sm leading-relaxed text-ink"><?= e($card->t('description')) ?></p><?php endif ?>
        <?= prose($card->html('body'), 'prose-sm') ?>
      </div>
      <?php if ($img): ?>
        <div class="h-64 w-full shrink-0 overflow-hidden rounded-2xl bg-[#ECEAED] lg:h-auto lg:w-72">
          <img src="<?= e(media_url($img, true)) ?>" alt="<?= e(media_alt($img)) ?>" class="h-full w-full object-cover" loading="lazy">
        </div>
      <?php endif ?>
    </div>
  <?php endforeach ?>
  <?php if ($doc): ?><ul><?= doc_row($c->t('title'), $doc) ?></ul><?php endif ?>
</div>
