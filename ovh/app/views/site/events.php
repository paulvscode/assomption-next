<?php /** @var array $upcoming @var array $past */ ?>
<?= page_header(tr('allEvents'), '', 'max-w-7xl') ?>
<div class="mx-auto max-w-7xl space-y-14 px-4 py-14 sm:px-6 lg:px-8">
  <section>
    <h2 class="mb-6 font-display text-xl font-semibold text-primary"><?= e(tr('upcoming')) ?></h2>
    <?php if ($upcoming): ?>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($upcoming as $entry): ?><?= render('site/partials/news-card', ['entry' => $entry]) ?><?php endforeach ?>
      </div>
    <?php else: ?>
      <p class="rounded-2xl border border-dashed border-border bg-surface px-6 py-10 text-center text-muted"><?= e(tr('noEvents')) ?></p>
    <?php endif ?>
  </section>
  <?php if ($past): ?>
    <section>
      <h2 class="mb-6 font-display text-xl font-semibold text-primary"><?= e(tr('pastEvents')) ?></h2>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach (array_slice($past, 0, 12) as $entry): ?><?= render('site/partials/news-card', ['entry' => $entry, 'past' => true]) ?><?php endforeach ?>
      </div>
    </section>
  <?php endif ?>
</div>
