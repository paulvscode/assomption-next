<?php /** @var C $c */ $menu = $c->media('menuFile'); ?>
<?= page_header($c->t('title')) ?>
<div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
  <?= prose($c->html('body'), 'prose-lg') ?>
  <div class="mt-6 flex flex-wrap gap-3">
    <?php if ($c->t('linkUrl') !== ''): ?>
      <a href="<?= e($c->t('linkUrl')) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-primary px-7 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90"><?= e($c->t('linkLabel', tr('viewMenus'))) ?> →</a>
    <?php endif ?>
    <?php if ($menu): ?>
      <a href="<?= e(media_url($menu)) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-primary/30 px-7 py-3 text-sm font-semibold text-primary transition-colors hover:bg-surface">↓ <?= e(tr('downloadMenu')) ?></a>
    <?php endif ?>
  </div>
</div>
