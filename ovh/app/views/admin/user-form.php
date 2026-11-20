<?php /** @var array $u @var bool $isNew @var array $errors @var ?string $tempPassword @var array $me */ ?>
<a class="back-link" href="/admin/utilisateurs"><?= icon('left', 'icon sm') ?>Tous les comptes</a>
<div class="page-head"><div><h1><?= $isNew ? 'Ajouter une personne' : e($u['name']) ?></h1></div></div>

<?php if ($tempPassword): ?>
  <section class="card card-success" role="status">
    <h2>Mot de passe temporaire de <?= e($u['name']) ?></h2>
    <p>Communiquez ces informations à <?= e($u['name']) ?> (de vive voix ou par SMS de préférence). Ce mot de passe ne sera plus affiché ensuite ; à la première connexion, il faudra en choisir un nouveau.</p>
    <dl class="credentials">
      <dt>Adresse de connexion</dt><dd><code><?= e(base_url('/admin')) ?></code></dd>
      <dt>Identifiant</dt><dd><code><?= e($u['email']) ?></code></dd>
      <dt>Mot de passe temporaire</dt><dd><code class="big-code"><?= e($tempPassword) ?></code> <button type="button" class="btn btn-small btn-secondary" data-copy="<?= e($tempPassword) ?>"><?= icon('link', 'icon sm') ?>Copier</button></dd>
    </dl>
    <a class="btn btn-primary" href="/admin/utilisateurs">J’ai noté ces informations</a>
  </section>
<?php endif ?>

<?php if ($errors): ?>
  <div class="flash flash-error" role="alert"><span class="flash-icon"><?= icon('x') ?></span><ul><?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<?php if (!$tempPassword): ?>
<form method="post" class="card stack" data-track-changes>
  <?= csrf_field() ?>
  <input type="hidden" name="op" value="save">
  <div class="grid-2">
    <div class="field"><label for="name">Nom et prénom</label><input type="text" id="name" name="name" value="<?= e($u['name']) ?>" required></div>
    <div class="field"><label for="email">Adresse e-mail</label><p class="help">Elle sert d’identifiant pour se connecter.</p><input type="email" id="email" name="email" value="<?= e($u['email']) ?>" required></div>
  </div>
  <div class="field">
    <span class="label">Ce que cette personne peut modifier</span>
    <?php foreach (ROLES as $k => $r): ?>
      <label class="radio-card"><input type="radio" name="role" value="<?= e($k) ?>" <?= $u['role'] === $k ? 'checked' : '' ?>><span><strong><?= e($r['label']) ?></strong><small><?= e($r['help']) ?></small></span></label>
    <?php endforeach ?>
  </div>
  <?php if (!$isNew): ?>
    <div class="field">
      <label class="switch">
        <input type="checkbox" name="active" value="1" <?= $u['active'] ? 'checked' : '' ?> data-toggle <?= (int) $u['id'] === (int) $me['id'] ? 'disabled' : '' ?>>
        <span class="switch-track" aria-hidden="true"><span class="switch-thumb"></span></span>
        <span class="switch-text" data-on="Compte actif : peut se connecter" data-off="Compte désactivé : ne peut plus se connecter"><?= $u['active'] ? 'Compte actif : peut se connecter' : 'Compte désactivé : ne peut plus se connecter' ?></span>
      </label>
      <?php if ((int) $u['id'] === (int) $me['id']): ?><input type="hidden" name="active" value="1"><?php endif ?>
      <p class="help">Désactivez le compte d’une personne qui quitte l’école plutôt que de le supprimer : l’historique de ses modifications est conservé.</p>
    </div>
  <?php endif ?>
  <div class="actions-left">
    <button class="btn btn-primary" type="submit"><?= icon('save') ?><?= $isNew ? 'Créer le compte' : 'Enregistrer' ?></button>
    <?php if ($isNew): ?><span class="muted">Un mot de passe temporaire sera créé et affiché à l’écran.</span><?php endif ?>
  </div>
</form>
<?php endif ?>

<?php if (!$isNew && !$tempPassword): ?>
  <section class="card">
    <h2>Mot de passe oublié ?</h2>
    <p class="muted">Créez un nouveau mot de passe temporaire pour <?= e($u['name']) ?>. L’ancien ne fonctionnera plus.</p>
    <form method="post" data-confirm="Créer un nouveau mot de passe temporaire pour <?= e($u['name']) ?> ? L’ancien mot de passe ne fonctionnera plus." data-confirm-ok="Créer le mot de passe">
      <?= csrf_field() ?><input type="hidden" name="op" value="reset">
      <button class="btn btn-secondary" type="submit"><?= icon('lock') ?>Créer un mot de passe temporaire</button>
    </form>
  </section>
<?php endif ?>
