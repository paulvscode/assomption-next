<?php
/** @var string $type @var string $slug @var array $entries @var array $sections @var ?string $filter @var string $search @var int $trashCount */
$isEvent = $type === 'event';
$sectionName = fn ($s) => ['ecole' => 'École', 'apel' => 'APEL', 'ogec' => 'OGEC'][$s];
$today = date('Y-m-d\T00:00');
?>
<div class="page-head">
  <div>
    <h1><?= $isEvent ? 'Événements' : 'Actualités' ?></h1>
    <p class="lead"><?= $isEvent ? 'Les événements de l’APEL, du plus récent au plus ancien.' : 'Les actualités, de la plus récente à la plus ancienne.' ?></p>
  </div>
  <a class="btn btn-primary btn-large" href="/admin/<?= e($slug) ?>/nouveau<?= $filter ? '?section=' . e($filter) : '' ?>"><?= icon('plus') ?><?= $isEvent ? 'Nouvel événement' : 'Nouvelle actualité' ?></a>
</div>

<form class="toolbar card" method="get">
  <?php if (!$isEvent && count($sections) > 1): ?>
    <div class="segmented" role="group" aria-label="Filtrer par rubrique">
      <a href="?<?= $search !== '' ? 'q=' . e(urlencode($search)) : '' ?>" aria-pressed="<?= $filter ? 'false' : 'true' ?>">Toutes</a>
      <?php foreach ($sections as $s): ?>
        <a href="?section=<?= e($s) ?><?= $search !== '' ? '&q=' . e(urlencode($search)) : '' ?>" aria-pressed="<?= $filter === $s ? 'true' : 'false' ?>"><?= e($sectionName($s)) ?></a>
      <?php endforeach ?>
    </div>
  <?php endif ?>
  <?php if ($filter): ?><input type="hidden" name="section" value="<?= e($filter) ?>"><?php endif ?>
  <div class="search">
    <input type="search" name="q" value="<?= e($search) ?>" placeholder="Rechercher un titre, un mot…" aria-label="Rechercher">
    <button class="btn btn-secondary" type="submit">Rechercher</button>
  </div>
</form>

<?php if ($entries): ?>
  <ul class="entry-list">
    <?php foreach ($entries as $en): $d = $en['data']; $c = new C($d, 'fr'); $img = $c->img('coverImage', true); $past = $isEvent && ((($d['end'] ?? '') ?: $en['date']) < $today); ?>
      <li class="entry-row card<?= $en['status'] !== 'published' ? ' is-draft' : '' ?>">
        <a href="/admin/<?= e($slug) ?>/<?= (int) $en['id'] ?>" class="entry-link">
          <span class="thumb"><?= $img ? '<img src="' . e($img) . '" alt="">' : icon($isEvent ? 'calendar' : 'news', 'icon lg') ?></span>
          <span class="list-main">
            <strong><?= e($c->t('title', 'Sans titre')) ?></strong>
            <small>
              <?= e($isEvent ? format_date($c->t('start'), 'fr', true) : format_date($c->t('date'), 'fr')) ?>
              <?php if (!$isEvent && count($sections) > 1): ?> · <?= e($sectionName($en['section'])) ?><?php endif ?>
              · modifié <?= e(human_date($en['updated_at'])) ?><?= $en['who'] ? ' par ' . e($en['who']) : '' ?>
            </small>
          </span>
          <span class="badges">
            <?php if ($en['status'] === 'published'): ?>
              <span class="badge badge-live"><?= $past ? 'Passé' : 'En ligne' ?></span>
            <?php else: ?>
              <span class="badge badge-draft">Brouillon</span>
            <?php endif ?>
            <?php if (trim((string) ($d['title']['en'] ?? '')) === ''): ?><span class="badge badge-muted" title="Pas de titre anglais : le titre français est utilisé sur le site anglais">EN à traduire</span><?php endif ?>
          </span>
        </a>
        <a class="btn btn-small btn-ghost" href="<?= e(entry_url($en, 'fr')) ?>" target="_blank" rel="noopener" title="Voir sur le site"><?= icon('eye', 'icon sm') ?><span class="hide-sm">Voir</span></a>
      </li>
    <?php endforeach ?>
  </ul>
<?php else: ?>
  <div class="empty-state card">
    <span class="task-icon"><?= icon($isEvent ? 'calendar' : 'news') ?></span>
    <h2><?= $search !== '' ? 'Aucun résultat pour « ' . e($search) . ' »' : ($isEvent ? 'Aucun événement pour le moment' : 'Aucune actualité pour le moment') ?></h2>
    <?php if ($search === ''): ?><p>Cliquez sur « <?= $isEvent ? 'Nouvel événement' : 'Nouvelle actualité' ?> » pour commencer.</p><?php endif ?>
  </div>
<?php endif ?>

<?php if ($trashCount): ?>
  <p class="muted center"><a href="/admin/corbeille"><?= icon('trash', 'icon sm') ?> Corbeille (<?= $trashCount ?>)</a></p>
<?php endif ?>
