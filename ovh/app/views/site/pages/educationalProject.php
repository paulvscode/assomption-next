<?php /** @var C $c */ ?>
<?= page_header($c->t('title'), '', 'max-w-5xl') ?>
<div class="mx-auto max-w-5xl px-4 py-14 sm:px-6 lg:px-8">
  <?php if ($c->html('intro')): ?><div class="mb-10"><?= prose($c->html('intro')) ?></div><?php endif ?>
  <div class="grid gap-6 sm:grid-cols-2">
    <?php foreach ($c->rows('cards') as $card): ?>
      <div class="rounded-2xl border border-border bg-white p-7 shadow-sm">
        <h2 class="mb-5 font-display text-lg font-semibold text-primary"><?= e($card->t('title')) ?></h2>
        <?= bullets($card->lines('items')) ?>
      </div>
    <?php endforeach ?>
  </div>
</div>
