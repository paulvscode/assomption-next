<?php /** @var C $c */ $form = $c->media('formFile'); ?>
<?= page_header($c->t('title')) ?>
<div class="mx-auto max-w-4xl space-y-10 px-4 py-14 sm:px-6 lg:px-8">
  <div>
    <p class="mb-4 text-base text-ink"><?= e($c->t('downloadLabel')) ?></p>
    <?php if ($form): ?>
      <a href="<?= e(media_url($form)) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-primary px-7 py-3 text-sm font-semibold text-white transition-opacity hover:opacity-90">↓ <?= e(tr('download')) ?> · <?= e(human_size((int) $form['size'])) ?></a>
    <?php else: ?>
      <div class="inline-flex items-center gap-2 rounded-xl border border-dashed border-border bg-surface px-5 py-3 text-sm text-muted">↓ <?= e(tr('docComingSoon')) ?></div>
    <?php endif ?>
  </div>
  <?php if ($c->lines('list1')): ?>
    <div>
      <p class="mb-4 font-semibold text-primary"><?= e($c->t('list1Title')) ?></p>
      <?= bullets($c->lines('list1')) ?>
    </div>
  <?php endif ?>
  <?php $faq = array_filter($c->rows('faq'), fn ($r) => $r->t('question') !== ''); if ($faq): ?>
    <section>
      <h2 class="mb-4 font-display text-xl font-semibold text-primary"><?= e($c->t('faqTitle')) ?></h2>
      <div class="divide-y divide-border rounded-2xl border border-border bg-white">
        <?php foreach ($faq as $q): ?>
          <details class="group px-5 py-4">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-primary">
              <?= e($q->t('question')) ?>
              <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-surface text-lg leading-none transition-transform group-open:rotate-45" aria-hidden="true">+</span>
            </summary>
            <p class="mt-3 text-base leading-relaxed text-ink"><?= $q->nl('answer') ?></p>
          </details>
        <?php endforeach ?>
      </div>
      <?php $ph = $settings->t('phone'); if ($ph !== ''): ?>
        <p class="mt-4 text-sm text-muted"><?= e(tr('faqCta')) ?> <a class="font-semibold text-primary underline" href="tel:<?= e(preg_replace('/[^0-9+]/', '', $ph)) ?>"><?= e($ph) ?></a><?php if ($settings->t('email') !== ''): ?> · <a class="font-semibold text-primary underline" href="mailto:<?= e($settings->t('email')) ?>"><?= e($settings->t('email')) ?></a><?php endif ?></p>
      <?php endif ?>
    </section>
  <?php endif ?>
  <?php $docs = docs_list($c->rows('otherDocs'), 'space-y-2.5', 'bg-white'); if ($docs): ?>
    <div class="rounded-2xl border border-border bg-surface p-6">
      <p class="mb-4 font-semibold text-primary"><?= e($c->t('list2Title')) ?></p>
      <?= $docs ?>
    </div>
  <?php endif ?>
</div>
