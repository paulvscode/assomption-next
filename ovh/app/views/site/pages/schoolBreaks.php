<?php
/** @var C $c */
$file = $c->media('calendarFile');
$l = locale();
$today = date('Y-m-d');
$periods = $c->rows('periods');
usort($periods, fn ($a, $b) => strcmp($a->t('start'), $b->t('start')));
?>
<?= page_header($c->t('title')) ?>
<div class="mx-auto max-w-4xl space-y-8 px-4 py-14 sm:px-6 lg:px-8">
  <?= prose($c->html('intro')) ?>
  <?php if ($periods): ?>
    <div class="overflow-x-auto rounded-2xl border border-border">
      <table class="w-full text-sm">
        <thead><tr class="bg-surface">
          <th scope="col" class="px-5 py-3 text-left font-semibold text-primary"><?= e(tr('period')) ?></th>
          <th scope="col" class="px-5 py-3 text-left font-semibold text-primary"><?= e(tr('dates')) ?></th>
        </tr></thead>
        <tbody class="divide-y divide-border">
          <?php foreach ($periods as $i => $p): $end = $p->t('end') ?: $p->t('start'); $past = $end < $today; $now = !$past && $p->t('start') <= $today; ?>
            <tr class="<?= $now ? 'bg-amber-50' : ($i % 2 ? 'bg-surface/50' : 'bg-white') ?><?= $past ? ' text-muted' : '' ?>">
              <th scope="row" class="px-5 py-3 text-left font-semibold <?= $past ? 'text-muted' : 'text-primary' ?>"><?= e($p->t('name')) ?><?php if ($now): ?> <span class="ml-1 rounded-full bg-amber-200 px-2 py-0.5 text-xs text-amber-900"><?= e(tr('ongoing')) ?></span><?php endif ?></th>
              <td class="px-5 py-3 <?= $past ? '' : 'text-ink' ?>"><?= e(period_text($p->t('start'), $p->t('end'), $l)) ?></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  <?php endif ?>
  <?php if ($file): ?><ul><?= doc_row($c->t('title'), $file) ?></ul><?php endif ?>
</div>
