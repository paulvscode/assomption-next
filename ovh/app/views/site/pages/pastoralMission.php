<?php /** @var C $c */ $bottom = $c->media('bottomImage'); ?>
<?= page_header($c->t('title'), '', 'max-w-5xl') ?>
<div class="mx-auto max-w-5xl px-4 py-14 sm:px-6 lg:px-8">
  <?= summary_box($c) ?>
  <?php if ($c->t('statement') !== ''): ?>
    <p class="mb-12 font-display text-xl font-semibold italic leading-relaxed text-primary sm:text-2xl"><?= e($c->t('statement')) ?></p>
  <?php endif ?>
  <div class="space-y-12">
    <?php foreach ($c->rows('blocks') as $b): $img = $b->media('image'); ?>
      <div class="flex flex-col gap-8 lg:flex-row">
        <div class="flex-1"><?= prose($b->html('text')) ?></div>
        <?php if ($img): ?>
          <div class="h-52 w-full shrink-0 overflow-hidden rounded-2xl bg-[#ECEAED] lg:h-auto lg:w-64">
            <img src="<?= e(media_url($img, true)) ?>" alt="<?= e(media_alt($img)) ?>" class="h-full w-full object-cover" loading="lazy">
          </div>
        <?php endif ?>
      </div>
    <?php endforeach ?>
  </div>
  <?php if ($c->html('highlight')): ?>
    <div class="mt-16 rounded-2xl border border-border bg-surface p-8"><?= prose($c->html('highlight')) ?></div>
  <?php endif ?>
  <?php if ($bottom): ?>
    <img src="<?= e(media_url($bottom)) ?>" alt="<?= e(media_alt($bottom)) ?>" class="mt-10 aspect-video w-full rounded-2xl object-cover" loading="lazy">
  <?php endif ?>
</div>
