<?php
/** @var C $c @var C $settings */
$address = $c->t('mapAddress') ?: str_replace("\n", ', ', $settings->t('address'));
$phone = $settings->t('phone');
$osm = 'https://www.openstreetmap.org/search?query=' . rawurlencode($address);
?>
<?= page_header($c->t('title'), '', 'max-w-5xl') ?>
<div class="mx-auto max-w-5xl px-4 py-14 sm:px-6 lg:px-8">
  <?php if ($c->bool('showMap')): ?>
    <div class="overflow-hidden rounded-2xl border border-border bg-surface shadow-sm" data-map data-query="<?= e($address) ?>">
      <div class="flex h-[400px] flex-col items-center justify-center gap-4 p-6 text-center" data-map-placeholder>
        <p class="font-semibold text-primary"><?= e($address) ?></p>
        <button type="button" class="rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90" data-map-load><?= e(tr('showMap')) ?></button>
        <p class="text-xs text-muted"><?= e(tr('mapNotice')) ?> · <a class="underline" href="<?= e($osm) ?>" target="_blank" rel="noopener"><?= e(tr('openMap')) ?></a></p>
      </div>
    </div>
  <?php endif ?>

  <div class="mt-10 grid gap-6 sm:grid-cols-3">
    <div class="rounded-2xl border border-border bg-white p-6 shadow-sm">
      <?php if ($phone !== ''): ?>
        <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-muted"><?= e(tr('phone')) ?></p>
        <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phone)) ?>" class="font-semibold text-primary hover:underline"><?= e($phone) ?></a>
      <?php endif ?>
      <?php if ($settings->t('email') !== ''): ?>
        <p class="mb-1 mt-4 text-xs font-semibold uppercase tracking-widest text-muted"><?= e(tr('email')) ?></p>
        <a href="mailto:<?= e($settings->t('email')) ?>" class="break-all text-sm font-semibold text-primary hover:underline"><?= e($settings->t('email')) ?></a>
      <?php endif ?>
    </div>
    <div class="rounded-2xl border border-border bg-white p-6 shadow-sm">
      <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-muted"><?= e(tr('address')) ?></p>
      <p class="font-semibold text-primary"><?= $settings->nl('address') ?></p>
    </div>
    <div class="rounded-2xl border border-border bg-white p-6 shadow-sm">
      <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-muted"><?= e(tr('openingHours')) ?></p>
      <p class="font-semibold text-primary"><?= $settings->nl('hours') ?></p>
    </div>
  </div>
  <?php if ($c->html('additionalInfo')): ?><div class="mt-10"><?= prose($c->html('additionalInfo')) ?></div><?php endif ?>
</div>
