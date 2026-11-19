<?php /** @var C $c */ $note = $c->media('noteFile'); ?>
<?= page_header($c->t('title'), '', 'max-w-5xl') ?>
<div class="mx-auto max-w-5xl space-y-12 px-4 py-14 sm:px-6 lg:px-8">
  <?= prose($c->html('intro')) ?>
  <?php foreach ([1, 2] as $n): $rows = $c->table("table$n"); if (!$rows) continue; ?>
    <div>
      <h2 class="mb-4 font-display text-xl font-semibold text-primary"><?= e($c->t("table{$n}Title")) ?></h2>
      <?= data_table($rows) ?>
    </div>
  <?php endforeach ?>
  <?php if ($c->t('noteText') !== '' || $note): ?>
    <div class="space-y-3">
      <?php if ($c->t('noteText') !== ''): ?><p class="text-sm italic text-muted"><?= e($c->t('noteText')) ?></p><?php endif ?>
      <?php if ($note): ?><ul><?= doc_row(tr('download') . ' — ' . $note['original_name'], $note) ?></ul><?php endif ?>
    </div>
  <?php endif ?>
  <?php $docs = docs_list($c->rows('supplyLists'), 'grid grid-cols-1 gap-2 sm:grid-cols-2'); if ($docs): ?>
    <div>
      <h2 class="mb-4 font-display text-xl font-semibold text-primary"><?= e($c->t('listsTitle')) ?></h2>
      <?= $docs ?>
    </div>
  <?php endif ?>
</div>
