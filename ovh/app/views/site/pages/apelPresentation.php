<?php /** @var C $c */ ?>
<?= page_header($c->t('title')) ?>
<div class="mx-auto max-w-4xl space-y-10 px-4 py-14 sm:px-6 lg:px-8">
  <?= summary_box($c) ?>
  <?= prose($c->html('body')) ?>
  <?= render('site/partials/members', ['c' => $c]) ?>
  <?php if ($c->lines('contactItems')): ?>
    <div>
      <h2 class="mb-4 font-display text-lg font-semibold text-primary"><?= e($c->t('contactTitle')) ?></h2>
      <?= bullets($c->lines('contactItems')) ?>
    </div>
  <?php endif ?>
  <?php if ($c->t('signupUrl') !== ''): ?>
    <div class="rounded-2xl border border-primary/20 bg-white p-6">
      <p class="mb-4 text-sm leading-relaxed text-ink"><?= e($c->t('signupText')) ?></p>
      <a href="<?= e($c->t('signupUrl')) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-primary px-6 py-2.5 text-sm font-semibold text-white transition-opacity hover:opacity-90"><?= e($c->t('signupLabel')) ?> →</a>
    </div>
  <?php endif ?>
  <?php if ($c->t('closing') !== ''): ?><p class="text-sm italic text-muted"><?= e($c->t('closing')) ?></p><?php endif ?>
</div>
