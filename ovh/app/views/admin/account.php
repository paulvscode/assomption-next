<?php /** @var array $u @var array $errors */ $forced = (int) $u['must_change_password'] === 1; ?>
<div class="page-head"><div><h1>Mon compte</h1><p class="lead"><?= e($u['name']) ?> · <?= e($u['email']) ?> · <?= e(ROLES[$u['role']]['label']) ?></p></div></div>

<?php if ($forced): ?>
  <div class="flash flash-info" role="status"><span class="flash-icon"><?= icon('lock') ?></span><p><strong>Bienvenue !</strong> Vous vous êtes connecté(e) avec un mot de passe temporaire. Choisissez maintenant votre propre mot de passe pour continuer.</p></div>
<?php endif ?>
<?php if ($errors): ?>
  <div class="flash flash-error" role="alert"><span class="flash-icon"><?= icon('x') ?></span><ul><?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<form method="post" class="card stack narrow">
  <?= csrf_field() ?>
  <h2>Changer mon mot de passe</h2>
  <div class="field"><label for="current"><?= $forced ? 'Mot de passe temporaire reçu' : 'Mot de passe actuel' ?></label><input type="password" id="current" name="current" required autocomplete="current-password"></div>
  <div class="field">
    <label for="password">Nouveau mot de passe</label>
    <p class="help">Au moins 10 caractères. Astuce : une petite phrase est facile à retenir et difficile à deviner, par exemple « Mon chat adore la plage 2025 ».</p>
    <div class="password-wrap"><input type="password" id="password" name="password" required minlength="10" autocomplete="new-password" data-strength><button type="button" class="btn btn-ghost btn-small" data-show-password="password" aria-pressed="false"><?= icon('eye', 'icon sm') ?>Afficher</button></div>
    <div class="strength" data-strength-meter hidden><span></span><small></small></div>
  </div>
  <div class="field"><label for="confirm">Retapez le nouveau mot de passe</label><input type="password" id="confirm" name="confirm" required autocomplete="new-password"></div>
  <div class="actions-left"><button class="btn btn-primary" type="submit"><?= icon('save') ?>Enregistrer mon mot de passe</button></div>
</form>
