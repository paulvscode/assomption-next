<?php
/** @var array $user @var array $sections @var bool $canEvents @var bool $canBanner @var array $landing @var array $recent @var array $drafts @var array $myPages */
$first = explode(' ', trim($user['name']))[0];
$hour = (int) date('G');
$hello = $hour < 18 ? 'Bonjour' : 'Bonsoir';
$tasks = [];
foreach ($sections as $s) {
    $tasks[] = ['/admin/actualites/nouveau?section=' . $s, 'news', 'Publier une actualité' . (count($sections) > 1 ? ' ' . ($s === 'ecole' ? "de l'école" : ($s === 'apel' ? "de l'APEL" : "de l'OGEC")) : ''), 'Un article avec une photo, visible tout de suite sur le site.'];
}
if ($canEvents) {
    $tasks[] = ['/admin/evenements/nouveau', 'calendar', 'Annoncer un événement', 'Une date, un lieu, une description.'];
}
$tasks[] = ['/admin/pages', 'page', 'Modifier une page', 'Changer un texte, une photo ou un document.'];
$tasks[] = ['/admin/medias', 'upload', 'Mettre en ligne un document', 'Un PDF, une liste de fournitures, une photo…'];
$banner = $landing['bannerText'] ?? ['fr' => '', 'en' => ''];
$style = $landing['bannerStyle'] ?? 'info';
?>
<div class="page-head">
  <div>
    <h1><?= e($hello) ?> <?= e($first) ?> 👋</h1>
    <p class="lead">Que souhaitez-vous faire aujourd’hui ?</p>
  </div>
</div>

<div class="task-grid">
  <?php foreach ($tasks as [$href, $ic, $label, $desc]): ?>
    <a class="task" href="<?= e($href) ?>">
      <span class="task-icon"><?= icon($ic) ?></span>
      <span><strong><?= e($label) ?></strong><small><?= e($desc) ?></small></span>
    </a>
  <?php endforeach ?>
</div>

<?php if ($canBanner): ?>
  <section class="card" id="annonce">
    <div class="card-head">
      <span class="task-icon small"><?= icon('megaphone') ?></span>
      <div>
        <h2>Annonce sur la page d’accueil</h2>
        <p class="muted">Pour une information importante : fermeture exceptionnelle, réunion, période d’inscriptions…</p>
      </div>
    </div>
    <form method="post" action="/admin/annonce" class="stack" data-track-changes>
      <?= csrf_field() ?>
      <div class="field">
        <label class="switch">
          <input type="checkbox" name="bannerEnabled" value="1" <?= !empty($landing['bannerEnabled']) ? 'checked' : '' ?> data-toggle>
          <span class="switch-track" aria-hidden="true"><span class="switch-thumb"></span></span>
          <span class="switch-text" data-on="L’annonce est affichée sur le site" data-off="L’annonce est masquée"><?= !empty($landing['bannerEnabled']) ? 'L’annonce est affichée sur le site' : 'L’annonce est masquée' ?></span>
        </label>
      </div>
      <div class="grid-2">
        <div class="field"><label for="bt-fr">Texte en français</label><input type="text" id="bt-fr" name="bannerText_fr" maxlength="200" data-count value="<?= e($banner['fr'] ?? '') ?>" placeholder="Ex : L’école sera fermée le vendredi 15 novembre."></div>
        <div class="field"><label for="bt-en">Texte en anglais <span class="muted">(facultatif)</span></label><input type="text" id="bt-en" name="bannerText_en" maxlength="200" data-count value="<?= e($banner['en'] ?? '') ?>" lang="en"></div>
      </div>
      <div class="field">
        <span class="label">Couleur</span>
        <div class="choice-row">
          <?php foreach (['info' => 'Jaune — information', 'urgent' => 'Rouge — urgent', 'calm' => 'Bleu — rappel'] as $k => $lbl): ?>
            <label class="choice choice-<?= $k ?>"><input type="radio" name="bannerStyle" value="<?= $k ?>" <?= $style === $k ? 'checked' : '' ?>><span><?= e($lbl) ?></span></label>
          <?php endforeach ?>
        </div>
      </div>
      <div class="actions-left"><button type="submit" class="btn btn-primary"><?= icon('save') ?>Enregistrer l’annonce</button></div>
    </form>
  </section>
<?php endif ?>

<div class="grid-2 align-start">
  <section class="card">
    <h2>Dernières modifications</h2>
    <?php if ($recent): ?>
      <ul class="list">
        <?php foreach ($recent as $r): ?>
          <li>
            <a href="<?= e($r['url']) ?>" class="list-link">
              <span class="tag"><?= e($r['kind']) ?></span>
              <span class="list-main"><strong><?= e($r['label']) ?></strong>
                <small><?= e(human_date($r['at'])) ?><?= $r['who'] ? ' par ' . e($r['who']) : '' ?></small></span>
              <?php if (!empty($r['draft'])): ?><span class="badge badge-draft">Brouillon</span><?php endif ?>
            </a>
          </li>
        <?php endforeach ?>
      </ul>
    <?php else: ?>
      <p class="muted">Rien pour l’instant. Les modifications que vous ferez apparaîtront ici.</p>
    <?php endif ?>
  </section>

  <div class="stack">
    <?php if ($noFutureDates || $stale): ?>
      <section class="card card-warn">
        <h2>À vérifier pour l’année <?= e($schoolYear) ?></h2>
        <?php if ($noFutureDates): ?>
          <p><strong>Le calendrier des vacances ne contient plus aucune date à venir.</strong> La page d’accueil n’affiche donc plus la prochaine date. <a href="/admin/pages/schoolBreaks">Ajouter les dates de cette année →</a></p>
        <?php endif ?>
        <?php if ($stale): ?>
          <p class="muted" style="margin-top:.6rem">Ces pages n’ont pas été modifiées depuis la rentrée :</p>
          <ul class="chips" style="margin-top:.5rem">
            <?php foreach ($stale as $k => $label): ?><li><a class="chip" href="/admin/pages/<?= e($k) ?>"><?= e($label) ?></a></li><?php endforeach ?>
          </ul>
        <?php endif ?>
      </section>
    <?php endif ?>
    <?php if ($drafts): ?>
      <section class="card card-warn">
        <h2>Brouillons en attente</h2>
        <p class="muted">Ces contenus ne sont pas encore visibles sur le site.</p>
        <ul class="list">
          <?php foreach ($drafts as $d): ?>
            <li><a href="<?= e($d['url']) ?>" class="list-link"><span class="list-main"><strong><?= e($d['label']) ?></strong><small>Modifié <?= e(human_date($d['at'])) ?></small></span><span class="btn btn-small btn-secondary">Reprendre</span></a></li>
          <?php endforeach ?>
        </ul>
      </section>
    <?php endif ?>
    <section class="card">
      <h2>Vos pages</h2>
      <ul class="chips">
        <?php foreach ($myPages as $key => $d): ?>
          <li><a class="chip" href="/admin/pages/<?= e($key) ?>"><?= e($d['label']) ?></a></li>
        <?php endforeach ?>
      </ul>
    </section>
    <section class="card card-tip">
      <h2>Le saviez-vous ?</h2>
      <p>Quand vous êtes connecté(e), un bouton <strong>« Modifier cette page »</strong> apparaît en bas à droite de chaque page du site. Naviguez sur le site comme un visiteur, puis cliquez dessus pour modifier ce que vous voyez.</p>
      <p><a href="/admin/aide">Consulter le guide d’utilisation →</a></p>
    </section>
  </div>
</div>
