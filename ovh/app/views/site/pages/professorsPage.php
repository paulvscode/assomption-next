<?php /** @var C $c */ $photo = $c->media('teamPhoto'); ?>
<?= page_header($c->t('title'), $c->t('subtitle'), 'max-w-5xl', 'text-2xl sm:text-3xl') ?>
<div class="mx-auto max-w-5xl px-4 py-14 sm:px-6 lg:px-8">
  <div class="grid gap-6 sm:grid-cols-2">
    <?php foreach ($c->rows('groups') as $g): ?>
      <div class="rounded-2xl border border-border bg-white p-6 shadow-sm">
        <h2 class="mb-4 font-display text-base font-semibold text-primary"><?= e($g->t('title')) ?></h2>
        <?= bullets($g->lines('members'), 'space-y-2', 'text-sm') ?>
      </div>
    <?php endforeach ?>
  </div>
  <?php if ($c->lines('supportMembers')): ?>
    <div class="mt-8 rounded-2xl border border-border bg-surface p-6">
      <h2 class="mb-4 font-display text-base font-semibold text-primary"><?= e($c->t('supportTitle')) ?></h2>
      <?= bullets($c->lines('supportMembers'), 'space-y-2', 'text-sm') ?>
    </div>
  <?php endif ?>
  <?php if ($c->t('principal') !== ''): ?>
    <div class="mt-8 rounded-2xl border-2 border-primary/20 bg-white p-6 text-center">
      <p class="text-xs font-semibold uppercase tracking-widest text-muted"><?= e($c->t('principalLabel')) ?></p>
      <p class="mt-2 font-display text-lg font-semibold text-primary"><?= e($c->t('principal')) ?></p>
    </div>
  <?php endif ?>
  <?php if ($photo): ?>
    <img src="<?= e(media_url($photo)) ?>" alt="<?= e(media_alt($photo, tr('team'))) ?>" class="mt-10 aspect-video w-full rounded-2xl object-cover" loading="lazy">
  <?php endif ?>
</div>
