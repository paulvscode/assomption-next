<?php /** @var string $label @var array $revisions @var string $back @var string $action */ ?>
<a class="back-link" href="<?= e($back) ?>"><?= icon('left', 'icon sm') ?>Retour à la modification</a>
<div class="page-head">
  <div>
    <h1>Versions précédentes</h1>
    <p class="lead">« <?= e($label) ?> » — chaque enregistrement garde une copie de la version d’avant. Vous pouvez revenir à l’une d’elles à tout moment.</p>
  </div>
</div>
<section class="card">
  <?php if ($revisions): ?>
    <ul class="list">
      <?php foreach ($revisions as $r): ?>
        <li class="list-row">
          <span class="list-main">
            <strong>Version du <?= e(format_date($r['created_at'], 'fr', true)) ?></strong>
            <small>Remplacée <?= e(human_date($r['created_at'])) ?><?= $r['user_name'] ? ' par ' . e($r['user_name']) : '' ?></small>
          </span>
          <form method="post" action="<?= e($action) ?>" data-confirm="Revenir à la version du <?= e(format_date($r['created_at'], 'fr', true)) ?> ? La version actuelle sera conservée dans cet historique." data-confirm-ok="Restaurer cette version">
            <?= csrf_field() ?><input type="hidden" name="revision" value="<?= (int) $r['id'] ?>">
            <button class="btn btn-small btn-secondary" type="submit"><?= icon('restore', 'icon sm') ?>Restaurer</button>
          </form>
        </li>
      <?php endforeach ?>
    </ul>
  <?php else: ?>
    <p class="muted">Aucune version précédente : ce contenu n’a pas encore été modifié depuis sa création.</p>
  <?php endif ?>
</section>
