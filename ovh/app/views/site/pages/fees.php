<?php /** @var C $c */ ?>
<?= page_header($c->t('title')) ?>
<div class="mx-auto max-w-4xl space-y-8 px-4 py-14 sm:px-6 lg:px-8">
  <?= prose($c->html('intro')) ?>
  <?= docs_list($c->rows('documents')) ?>
</div>
