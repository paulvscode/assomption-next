<?php /** @var ?string $error @var string $email @var array $flashes */ ?><!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Connexion · Administration du site</title>
<link rel="stylesheet" href="/assets/admin.css?v=<?= e((string) @filemtime(APP_DIR . '/../www/assets/admin.css')) ?>">
</head>
<body class="admin auth-page">
<main class="auth-card">
  <div class="brand brand-center">
    <span class="brand-mark" aria-hidden="true">A</span>
    <span><strong>École de l’Assomption</strong><small>Gestion du site</small></span>
  </div>
  <h1>Connexion</h1>
  <?php foreach ($flashes as $f): ?>
    <div class="flash flash-<?= e($f['type']) ?>" role="status"><p><?= e($f['message']) ?></p></div>
  <?php endforeach ?>
  <?php if ($error): ?>
    <div class="flash flash-error" role="alert"><span class="flash-icon"><?= icon('x') ?></span><p><?= e($error) ?></p></div>
  <?php endif ?>
  <form method="post" action="/admin/connexion" class="stack">
    <?= csrf_field() ?>
    <div class="field">
      <label for="email">Votre adresse e-mail</label>
      <input type="email" id="email" name="email" value="<?= e($email) ?>" required autocomplete="username" <?= $email === '' ? 'autofocus' : '' ?>>
    </div>
    <div class="field">
      <label for="password">Votre mot de passe</label>
      <div class="password-wrap">
        <input type="password" id="password" name="password" required autocomplete="current-password" <?= $email !== '' ? 'autofocus' : '' ?>>
        <button type="button" class="btn btn-ghost btn-small" data-show-password="password" aria-pressed="false"><?= icon('eye', 'icon sm') ?>Afficher</button>
      </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-large"><?= icon('lock') ?>Se connecter</button>
  </form>
  <details class="forgot">
    <summary>Mot de passe oublié ?</summary>
    <p>Pas d’inquiétude : demandez à l’administrateur du site (la direction de l’école) de vous créer un nouveau mot de passe temporaire. Vous le changerez à la connexion suivante.</p>
  </details>
  <p class="auth-foot"><a href="/fr">← Retour au site</a></p>
</main>
<script src="/assets/admin.js" defer></script>
</body>
</html>
