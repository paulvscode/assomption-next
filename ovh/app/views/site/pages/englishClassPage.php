<?php /** @var C $c */ ?>
<?= page_header($c->t('title'), '', 'max-w-4xl', 'text-2xl sm:text-3xl') ?>
<div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
  <?= summary_box($c) ?>
  <?php if ($c->t('subtitle') !== ''): ?><p class="mb-8 text-lg font-semibold text-primary"><?= e($c->t('subtitle')) ?></p><?php endif ?>
  <div class="space-y-5">
    <?php foreach ($c->rows('cases') as $case): ?>
      <div class="rounded-2xl border border-border bg-white p-6 shadow-sm">
        <p class="text-base leading-relaxed text-ink"><?= e($case->t('description')) ?></p>
        <?php if ($case->t('condition') !== ''): ?>
          <p class="mt-3 text-sm text-muted"><span class="font-semibold text-primary"><?= e(tr('condition')) ?></span> <?= e($case->t('condition')) ?></p>
        <?php endif ?>
      </div>
    <?php endforeach ?>
  </div>
  <div class="mt-10"><?= prose($c->html('text')) ?></div>
  <?php if ($c->lines('activities')): ?>
    <div class="mt-8 rounded-2xl border border-border bg-surface p-6">
      <p class="mb-4 font-semibold text-primary"><?= e($c->t('activitiesTitle')) ?></p>
      <?= bullets($c->lines('activities'), 'grid gap-2 sm:grid-cols-2', 'text-sm') ?>
    </div>
  <?php endif ?>
  <?php if ($c->t('finalWord') !== ''): ?><p class="mt-6 text-sm italic text-muted"><?= e($c->t('finalWord')) ?></p><?php endif ?>
</div>
