<?php /** @var array $items @var ?string $kind @var string $search */ ?>
<div class="page-head">
  <div>
    <h1>Images & documents</h1>
    <p class="lead">Tous les fichiers envoyés sur le site. Vous pouvez aussi ajouter une image ou un document directement depuis la page où il doit apparaître.</p>
  </div>
</div>

<form method="post" action="/admin/medias<?= $kind ? '?type=' . e($kind) : '' ?>" enctype="multipart/form-data" class="card upload-card" data-upload-form>
  <?= csrf_field() ?>
  <label class="dropzone" data-dropzone>
    <input type="file" name="files[]" multiple hidden data-auto-submit accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.ods,.jpg,.jpeg,.png,.webp,.gif">
    <?= icon('upload', 'icon lg') ?>
    <strong>Ajouter des fichiers</strong>
    <span class="muted">Cliquez ici ou glissez-déposez vos fichiers. PDF, Word, Excel, images — <?= max_upload_mb() ?> Mo maximum par fichier.</span>
  </label>
</form>

<form class="toolbar card" method="get">
  <div class="segmented" role="group" aria-label="Type de fichier">
    <a href="/admin/medias" aria-pressed="<?= $kind ? 'false' : 'true' ?>">Tout</a>
    <a href="/admin/medias?type=image" aria-pressed="<?= $kind === 'image' ? 'true' : 'false' ?>">Images</a>
    <a href="/admin/medias?type=file" aria-pressed="<?= $kind === 'file' ? 'true' : 'false' ?>">Documents</a>
  </div>
  <?php if ($kind): ?><input type="hidden" name="type" value="<?= e($kind) ?>"><?php endif ?>
  <div class="search">
    <input type="search" name="q" value="<?= e($search) ?>" placeholder="Rechercher un nom de fichier…" aria-label="Rechercher">
    <button class="btn btn-secondary" type="submit">Rechercher</button>
  </div>
</form>

<?php if ($items): ?>
  <ul class="media-grid">
    <?php foreach ($items as $m): ?>
      <li class="media-card card">
        <a class="media-thumb" href="<?= e(media_url($m)) ?>" target="_blank" rel="noopener" title="Ouvrir">
          <?= $m['kind'] === 'image' ? '<img src="' . e(media_url($m, true)) . '" alt="" loading="lazy">' : '<span class="file-badge big">' . e(file_icon($m['mime'])) . '</span>' ?>
        </a>
        <div class="media-info">
          <strong title="<?= e($m['original_name']) ?>"><?= e($m['original_name']) ?></strong>
          <small><?= e(human_size((int) $m['size'])) ?> · <?= e(human_date($m['created_at'])) ?><?= $m['who'] ? ' · ' . e($m['who']) : '' ?></small>
          <?php if ($m['kind'] === 'image'): ?>
            <input type="text" value="<?= e($m['alt']) ?>" data-alt-for="<?= e($m['id']) ?>" placeholder="Description (pour les malvoyants)" aria-label="Description de l’image">
          <?php endif ?>
        </div>
        <div class="media-tools">
          <button type="button" class="btn btn-small btn-ghost" data-copy="<?= e(base_url(media_url($m))) ?>" title="Copier le lien pour le coller dans un texte ou un e-mail"><?= icon('link', 'icon sm') ?>Copier le lien</button>
          <form method="post" action="/admin/medias/<?= e($m['id']) ?>/supprimer" data-confirm="Supprimer définitivement « <?= e($m['original_name']) ?> » ? Cette action ne peut pas être annulée." data-confirm-ok="Supprimer">
            <?= csrf_field() ?><button type="submit" class="btn btn-small btn-ghost danger" title="Supprimer"><?= icon('trash', 'icon sm') ?><span class="sr-only">Supprimer</span></button>
          </form>
        </div>
      </li>
    <?php endforeach ?>
  </ul>
<?php else: ?>
  <div class="empty-state card"><span class="task-icon"><?= icon('image') ?></span><h2><?= $search !== '' ? 'Aucun fichier trouvé' : 'Aucun fichier pour le moment' ?></h2></div>
<?php endif ?>
