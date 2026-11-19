<?php /** @var C $c */ ?>
<?= page_header($c->t('title'), $c->t('headline')) ?>
<div class="mx-auto max-w-4xl space-y-10 px-4 py-14 sm:px-6 lg:px-8">
  <?= summary_box($c) ?>
  <?= prose($c->html('body')) ?>
  <?php if ($c->rows('domains')): ?>
    <div>
      <h2 class="mb-5 font-display text-xl font-semibold text-primary"><?= e($c->t('domainsTitle')) ?></h2>
      <div class="grid gap-4 sm:grid-cols-2">
        <?php foreach ($c->rows('domains') as $d): ?>
          <div class="rounded-2xl border border-border bg-white p-5 shadow-sm">
            <p class="mb-1.5 font-semibold text-primary"><?= e($d->t('name')) ?></p>
            <p class="text-sm leading-relaxed text-ink"><?= e($d->t('description')) ?></p>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  <?php endif ?>
  <?php if ($c->t('closingText') !== ''): ?><p class="text-base leading-relaxed text-ink"><?= e($c->t('closingText')) ?></p><?php endif ?>
  <?= render('site/partials/members', ['c' => $c]) ?>
</div>
