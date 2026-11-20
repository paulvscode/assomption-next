<?php /** @var array $errors @var array $old @var bool $keyMissing */ ?><!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Installation · Administration du site</title>
<link rel="stylesheet" href="/assets/admin.css">
</head>
<body class="admin auth-page">
<main class="auth-card">
  <div class="brand brand-center">
    <span class="brand-mark" aria-hidden="true">A</span>
    <span><strong>École de l’Assomption</strong><small>Installation</small></span>
  </div>
  <h1>Créer le premier compte administrateur</h1>
  <?php if ($keyMissing): ?>
    <div class="flash flash-error" role="alert"><p>L’installation est verrouillée : renseignez d’abord une clé <code>setup_key</code> dans le fichier <code>app/config.php</code> sur le serveur, puis rechargez cette page.</p></div>
  <?php else: ?>
    <p class="muted">Cette page n’apparaît qu’une seule fois. Une fois votre compte créé, vous pourrez ajouter les autres personnes depuis « Comptes ».</p>
    <?php if ($errors): ?>
      <div class="flash flash-error" role="alert"><ul><?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach ?></ul></div>
    <?php endif ?>
    <form method="post" action="/admin/setup" class="stack">
      <?= csrf_field() ?>
      <div class="field"><label for="setup_key">Clé d’installation</label><p class="help">La valeur de <code>setup_key</code> dans app/config.php.</p><input type="password" id="setup_key" name="setup_key" required autocomplete="off"></div>
      <div class="field"><label for="name">Votre nom</label><input type="text" id="name" name="name" value="<?= e($old['name']) ?>" required></div>
      <div class="field"><label for="email">Votre adresse e-mail</label><input type="email" id="email" name="email" value="<?= e($old['email']) ?>" required autocomplete="username"></div>
      <div class="field"><label for="password">Choisissez un mot de passe</label><p class="help">Au moins 10 caractères. Une phrase courte est facile à retenir et difficile à deviner, par exemple « Le phare du Havre 1928 ».</p><input type="password" id="password" name="password" required minlength="10" autocomplete="new-password"></div>
      <button type="submit" class="btn btn-primary btn-block btn-large">Créer mon compte</button>
    </form>
  <?php endif ?>
</main>
</body>
</html>
