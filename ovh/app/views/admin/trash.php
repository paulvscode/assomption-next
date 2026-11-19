<?php /** @var array $entries */ ?>
<div class="page-head">
  <div>
    <h1>Corbeille</h1>
    <p class="lead">Les actualités et événements supprimés. Ils ne sont plus visibles sur le site, mais vous pouvez les récupérer.</p>
  </div>
</div>
<section class="card">
  <?php if ($entries): ?>
    <ul class="list">
      <?php foreach ($entries as $en): $slug = $en['type'] === 'event' ? 'evenements' : 'actualites'; ?>
        <li class="list-row">
          <span class="tag"><?= $en['type'] === 'event' ? 'Événement' : 'Actualité' ?></span>
          <span class="list-main">
            <strong><?= e($en['data']['title']['fr'] ?? 'Sans titre') ?></strong>
            <small>Supprimé <?= e(human_date($en['deleted_at'])) ?><?= $en['who'] ? ' par ' . e($en['who']) : '' ?></small>
          </span>
          <form method="post" action="/admin/<?= $slug ?>/<?= (int) $en['id'] ?>/restaurer"><?= csrf_field() ?><button class="btn btn-small btn-secondary" type="submit"><?= icon('restore', 'icon sm') ?>Récupérer</button></form>
        </li>
      <?php endforeach ?>
    </ul>
  <?php else: ?>
    <p class="muted">La corbeille est vide.</p>
  <?php endif ?>
</section>
