<?php /** @var array $users @var array $me */ ?>
<div class="page-head">
  <div>
    <h1>Comptes</h1>
    <p class="lead">Les personnes qui peuvent modifier le site. Chacune a son propre identifiant : ne partagez pas les mots de passe.</p>
  </div>
  <a class="btn btn-primary btn-large" href="/admin/utilisateurs/nouveau"><?= icon('plus') ?>Ajouter une personne</a>
</div>
<section class="card">
  <ul class="list">
    <?php foreach ($users as $u): ?>
      <li>
        <a class="list-link<?= $u['active'] ? '' : ' is-muted' ?>" href="/admin/utilisateurs/<?= (int) $u['id'] ?>">
          <span class="avatar" aria-hidden="true"><?= e(mb_strtoupper(mb_substr($u['name'], 0, 1))) ?></span>
          <span class="list-main">
            <strong><?= e($u['name']) ?><?= (int) $u['id'] === (int) $me['id'] ? ' (vous)' : '' ?></strong>
            <small><?= e($u['email']) ?> · <?= $u['last_login_at'] ? 'dernière connexion ' . e(human_date($u['last_login_at'])) : 'jamais connecté(e)' ?></small>
          </span>
          <span class="badges">
            <span class="badge badge-role-<?= e($u['role']) ?>"><?= e(ROLES[$u['role']]['label'] ?? $u['role']) ?></span>
            <?php if (!$u['active']): ?><span class="badge badge-muted">Désactivé</span><?php endif ?>
            <?php if ($u['must_change_password'] && $u['active']): ?><span class="badge badge-draft">Mot de passe temporaire</span><?php endif ?>
          </span>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
</section>
<section class="card card-tip">
  <h2>Qui peut faire quoi ?</h2>
  <dl class="roles">
    <?php foreach (ROLES as $r): ?><dt><?= e($r['label']) ?></dt><dd><?= e($r['help']) ?></dd><?php endforeach ?>
  </dl>
</section>
