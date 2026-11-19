<?php /** @var C $c */ $members = $c->rows('members'); if (!$members) return; ?>
<div class="rounded-2xl border border-border bg-surface p-6">
  <h2 class="mb-5 font-display text-lg font-semibold text-primary"><?= e($c->t('compositionTitle')) ?></h2>
  <ul class="space-y-2">
    <?php foreach ($members as $m): ?>
      <li class="flex flex-wrap gap-x-2 text-sm text-ink"><span class="font-semibold text-primary"><?= e($m->t('role')) ?> :</span><?= e($m->t('name')) ?></li>
    <?php endforeach ?>
  </ul>
</div>
