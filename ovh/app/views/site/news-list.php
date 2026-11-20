<?php /** @var array $entries @var string $title */ ?>
<?= page_header($title, '', 'max-w-7xl') ?>
<div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
  <?php if ($entries): ?>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($entries as $entry): ?><?= render('site/partials/news-card', ['entry' => $entry]) ?><?php endforeach ?>
    </div>
  <?php else: ?>
    <p class="rounded-2xl border border-dashed border-border bg-surface px-6 py-10 text-center text-muted"><?= e(tr('noNews')) ?></p>
  <?php endif ?>
</div>
