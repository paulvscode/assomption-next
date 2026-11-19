<?php /** @var array $groups */ ?>
<div class="page-head">
  <div>
    <h1>Pages du site</h1>
    <p class="lead">Les pages sont rangées comme dans le menu du site. Cliquez sur une page pour la modifier.</p>
  </div>
</div>
<?php foreach (MENU_GROUPS as $g => $groupLabel): if (empty($groups[$g])) continue; ?>
  <section class="card">
    <h2><?= e($groupLabel) ?></h2>
    <ul class="list">
      <?php foreach ($groups[$g] as $key => $def): $meta = $def['meta']; ?>
        <li class="page-row">
          <a href="/admin/pages/<?= e($key) ?>" class="list-link">
            <span class="task-icon small"><?= icon($def['icon'] ?? 'page') ?></span>
            <span class="list-main">
              <strong><?= e($def['label']) ?></strong>
              <small><?= $meta && $meta['updated_by_name'] ? 'Modifiée ' . e(human_date($meta['updated_at'])) . ' par ' . e($meta['updated_by_name']) : 'Contenu d’origine, jamais modifié' ?></small>
            </span>
            <span class="btn btn-small btn-secondary">Modifier</span>
          </a>
          <?php if ($def['path'] !== null): ?>
            <a class="btn btn-small btn-ghost" href="<?= e(site_url($def['path'], 'fr')) ?>" target="_blank" rel="noopener" title="Voir sur le site"><?= icon('eye', 'icon sm') ?><span class="hide-sm">Voir</span></a>
          <?php endif ?>
        </li>
      <?php endforeach ?>
    </ul>
  </section>
<?php endforeach ?>
