<?php /** @var array $stats */ ?>
<div class="page-head"><div><h1>Sauvegarde</h1><p class="lead">Téléchargez une copie de tout le contenu du site, à conserver en lieu sûr.</p></div></div>
<section class="card stack">
  <ul class="stats">
    <li><strong><?= $stats['pages'] ?></strong><span>pages</span></li>
    <li><strong><?= $stats['entries'] ?></strong><span>actualités et événements</span></li>
    <li><strong><?= $stats['media'] ?></strong><span>fichiers (<?= e(human_size($stats['mediaSize'])) ?>)</span></li>
  </ul>
  <form method="post"><?= csrf_field() ?><button class="btn btn-primary btn-large" type="submit"><?= icon('download') ?>Télécharger la sauvegarde des textes (<?= e(human_size((int) $stats['dbSize'])) ?>)</button></form>
  <p class="help">Ce fichier contient tous les textes, les comptes et l’historique. Les images et documents eux-mêmes se trouvent dans le dossier <code>www/uploads</code> du serveur : récupérez-le avec votre logiciel FTP (ou les sauvegardes automatiques d’OVH) pour une copie complète.</p>
  <p class="help">Nous vous conseillons de faire une sauvegarde au début de chaque trimestre et avant toute grosse modification.</p>
</section>
