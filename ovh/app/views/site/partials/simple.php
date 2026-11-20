<?php /** @var C $c */ $img = $c->media('coverImage'); ?>
<?= page_header($c->t('title')) ?>
<div class="mx-auto max-w-4xl space-y-10 px-4 py-14 sm:px-6 lg:px-8">
  <?= summary_box($c) ?>
  <?= prose($c->html('body'), 'prose-lg') ?>
  <?php if ($img): ?>
    <img src="<?= e(media_url($img)) ?>" alt="<?= e(media_alt($img)) ?>" class="w-full rounded-2xl object-cover" loading="lazy">
  <?php endif ?>
</div>
