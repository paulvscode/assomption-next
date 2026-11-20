<?php /** @var C $c */ ?>
<?= page_header($c->t('title')) ?>
<div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
  <?php if ($c->t('intro') !== ''): ?><p class="mb-6 text-base text-ink"><?= e($c->t('intro')) ?></p><?php endif ?>
  <?= docs_list($c->rows('documents')) ?>
</div>
