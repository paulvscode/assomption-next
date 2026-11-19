<?php
/** @var string $title @var string $content @var array|null $user @var array $flashes */
$path = substr(request_path(), strlen('/admin')) ?: '/';
$is = fn (string $prefix) => $prefix === '/' ? $path === '/' : str_starts_with($path, $prefix);
$nav = [
    ['/', 'Accueil', 'home', true],
    ['/pages', 'Pages du site', 'page', true],
    ['/actualites', 'Actualités', 'news', (bool) allowed_sections('news')],
    ['/evenements', 'Événements', 'calendar', (bool) allowed_sections('event')],
    ['/medias', 'Images & documents', 'image', true],
];
$navAdmin = [
    ['/utilisateurs', 'Comptes', 'users'],
    ['/pages/settings', 'Réglages du site', 'settings'],
    ['/sauvegarde', 'Sauvegarde', 'download'],
];
$wide = $wide ?? false;
$v = fn ($f) => e((string) @filemtime(APP_DIR . '/../www/assets/' . $f));
?><!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="csrf" content="<?= e(csrf_token()) ?>">
<meta name="max-upload" content="<?= max_upload_mb() ?>">
<title><?= e($title) ?> · Administration du site</title>
<link rel="stylesheet" href="/assets/admin.css?v=<?= $v('admin.css') ?>">
<link rel="icon" href="/favicon.ico">
</head>
<body class="admin">
<a href="#main" class="skip">Aller au contenu</a>
<div class="shell">
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <span class="brand-mark" aria-hidden="true">A</span>
      <span><strong>Assomption</strong><small>Gestion du site</small></span>
    </div>
    <nav aria-label="Administration">
      <ul>
        <?php foreach ($nav as [$href, $label, $ic, $show]): if (!$show) continue; ?>
          <li><a href="/admin<?= $href === '/' ? '' : $href ?>"<?= $is($href) && !($href === '/pages' && $is('/pages/settings')) ? ' aria-current="page"' : '' ?>><?= icon($ic) ?><?= e($label) ?></a></li>
        <?php endforeach ?>
      </ul>
      <?php if (is_admin()): ?>
        <p class="nav-heading">Administration</p>
        <ul>
          <?php foreach ($navAdmin as [$href, $label, $ic]): ?>
            <li><a href="/admin<?= $href ?>"<?= $is($href) ? ' aria-current="page"' : '' ?>><?= icon($ic) ?><?= e($label) ?></a></li>
          <?php endforeach ?>
        </ul>
      <?php endif ?>
      <ul class="nav-bottom">
        <li><a href="/admin/aide"<?= $is('/aide') ? ' aria-current="page"' : '' ?>><?= icon('help') ?>Aide</a></li>
        <li><a href="/fr" target="_blank" rel="noopener"><?= icon('external') ?>Voir le site</a></li>
      </ul>
    </nav>
    <?php if ($user): ?>
      <div class="whoami">
        <a href="/admin/compte" class="who"><?= icon('user') ?><span><strong><?= e($user['name']) ?></strong><small><?= e(ROLES[$user['role']]['label']) ?></small></span></a>
        <form method="post" action="/admin/deconnexion"><?= csrf_field() ?><button class="btn btn-ghost btn-small" type="submit"><?= icon('logout', 'icon sm') ?>Se déconnecter</button></form>
      </div>
    <?php endif ?>
  </aside>

  <div class="main-col">
    <header class="topbar">
      <button type="button" class="btn btn-ghost menu-btn" data-menu-toggle aria-controls="sidebar" aria-expanded="false"><?= icon('menu') ?>Menu</button>
      <span class="topbar-title">Gestion du site</span>
      <a class="btn btn-ghost btn-small" href="/fr" target="_blank" rel="noopener"><?= icon('external', 'icon sm') ?>Site</a>
    </header>

    <main id="main" class="content<?= $wide ? ' wide' : '' ?>" tabindex="-1">
      <?php if ($flashes): ?>
        <div class="flashes" aria-live="polite">
          <?php foreach ($flashes as $f): ?>
            <div class="flash flash-<?= e($f['type']) ?>" role="<?= $f['type'] === 'error' ? 'alert' : 'status' ?>">
              <span class="flash-icon"><?= icon($f['type'] === 'error' ? 'x' : 'check') ?></span>
              <p><?= e($f['message']) ?></p>
              <?php if (!empty($f['action'])): $a = $f['action']; ?>
                <?php if (!empty($a['post'])): ?>
                  <form method="post" action="<?= e($a['url']) ?>"><?= csrf_field() ?><button class="btn btn-small btn-secondary" type="submit"><?= e($a['label']) ?></button></form>
                <?php else: ?>
                  <a class="btn btn-small btn-secondary" href="<?= e($a['url']) ?>"<?= !empty($a['external']) ? ' target="_blank" rel="noopener"' : '' ?>><?= e($a['label']) ?><?= !empty($a['external']) ? icon('external', 'icon sm') : '' ?></a>
                <?php endif ?>
              <?php endif ?>
              <button type="button" class="flash-close" data-dismiss aria-label="Fermer ce message"><?= icon('x', 'icon sm') ?></button>
            </div>
          <?php endforeach ?>
        </div>
      <?php endif ?>
      <?= $content ?>
    </main>
  </div>
</div>

<dialog class="modal" id="media-modal" aria-labelledby="media-modal-title">
  <div class="modal-head">
    <h2 id="media-modal-title">Choisir un fichier</h2>
    <button type="button" class="btn btn-ghost" data-modal-close aria-label="Fermer"><?= icon('x') ?></button>
  </div>
  <div class="modal-body">
    <label class="dropzone" data-dropzone>
      <input type="file" data-upload-input hidden>
      <?= icon('upload', 'icon lg') ?>
      <strong data-drop-title>Envoyer un fichier depuis l’ordinateur</strong>
      <span class="muted" data-drop-hint>Cliquez ici, ou glissez-déposez le fichier dans ce cadre.</span>
      <span class="upload-progress" data-upload-progress hidden><span></span></span>
    </label>
    <p class="field-error" data-upload-error hidden role="alert"></p>
    <div class="picker-head">
      <h3>Ou choisir un fichier déjà envoyé</h3>
      <input type="search" placeholder="Rechercher…" data-picker-search aria-label="Rechercher un fichier">
    </div>
    <div class="picker-grid" data-picker-grid><p class="muted">Chargement…</p></div>
  </div>
</dialog>

<dialog class="modal modal-small" id="confirm-modal" aria-labelledby="confirm-title">
  <div class="modal-body">
    <h2 id="confirm-title" data-confirm-title>Êtes-vous sûr(e) ?</h2>
    <p data-confirm-text></p>
    <div class="actions">
      <button type="button" class="btn btn-secondary" data-confirm-cancel>Annuler</button>
      <button type="button" class="btn btn-danger" data-confirm-ok>Confirmer</button>
    </div>
  </div>
</dialog>

<dialog class="modal modal-small" id="link-modal" aria-labelledby="link-title">
  <form class="modal-body" method="dialog" data-link-form>
    <h2 id="link-title">Ajouter un lien</h2>
    <div class="field">
      <label for="link-url">Adresse du lien</label>
      <p class="help">Une adresse web (https://…), une adresse e-mail ou un numéro de téléphone.</p>
      <input type="text" id="link-url" data-link-url placeholder="https://www.exemple.fr">
    </div>
    <div class="actions">
      <button type="button" class="btn btn-secondary" data-link-cancel>Annuler</button>
      <button type="submit" class="btn btn-primary">Ajouter le lien</button>
    </div>
  </form>
</dialog>

<script src="/assets/admin.js?v=<?= $v('admin.js') ?>" defer></script>
</body>
</html>
