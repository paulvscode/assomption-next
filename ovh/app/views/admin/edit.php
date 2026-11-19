<?php
/**
 * Generic editor for a page or an entry (news / event).
 * @var string $kind 'page'|'entry' @var array $def @var array $sections @var array $data @var array $errors
 * @var string $action @var array $back @var ?string $viewUrl @var ?string $previewUrl @var ?string $historyUrl @var ?array $meta
 */
$isEntry = $kind === 'entry';
$isNew = $isNew ?? false;
$hasLoc = false;
foreach ($sections as $s) {
    foreach ($s['fields'] as $f) {
        if (!empty($f['loc'])) {
            $hasLoc = true;
        }
    }
}
?>
<form method="post" action="<?= e($action) ?>" id="edit-form" class="editor" data-track-changes data-editor novalidate>
  <?= csrf_field() ?>
  <input type="hidden" name="_lang" value="fr" data-lang-input>

  <div class="editor-bar">
    <a class="back-link" href="<?= e($back[0]) ?>"><?= icon('left', 'icon sm') ?><?= e($back[1]) ?></a>
    <div class="editor-bar-main">
      <h1><?= e($def['label']) ?></h1>
      <?php if ($meta && !empty($meta['updated_at'])): ?>
        <p class="muted small"><?= icon('clock', 'icon sm') ?> Modifié <?= e(human_date($meta['updated_at'])) ?><?= !empty($meta['updated_by_name']) ? ' par ' . e($meta['updated_by_name']) : '' ?></p>
      <?php endif ?>
    </div>
    <div class="editor-actions">
      <span class="save-state" data-save-state aria-live="polite"></span>
      <?php if ($previewUrl): ?>
        <button type="submit" class="btn btn-secondary" formaction="<?= e($previewUrl) ?>" formtarget="_blank" data-preview><?= icon('eye') ?><span class="hide-sm">Aperçu</span></button>
      <?php endif ?>
      <button type="submit" class="btn btn-primary" data-save><?= icon('save') ?><?= $isEntry && $isNew ? 'Enregistrer' : 'Enregistrer' ?></button>
    </div>
  </div>

  <?php if ($errors): ?>
    <div class="flash flash-error" role="alert" tabindex="-1" data-error-summary>
      <span class="flash-icon"><?= icon('x') ?></span>
      <div>
        <p><strong>Rien n’a été enregistré : <?= count($errors) > 1 ? 'quelques points sont' : 'un point est' ?> à corriger.</strong></p>
        <ul><?php foreach (array_unique($errors) as $msg): ?><li><?= e($msg) ?></li><?php endforeach ?></ul>
      </div>
    </div>
  <?php endif ?>

  <div class="editor-layout">
    <div class="editor-main">
      <?php if (!empty($def['intro'])): ?>
        <div class="card card-tip"><p><?= e($def['intro']) ?></p></div>
      <?php endif ?>

      <?php if ($hasLoc): ?>
        <div class="lang-switch card" role="group" aria-label="Langue modifiée">
          <span class="lang-switch-label">Vous modifiez la version :</span>
          <div class="segmented">
            <button type="button" data-lang="fr" aria-pressed="true"><span class="flag" aria-hidden="true">🇫🇷</span> Français</button>
            <button type="button" data-lang="en" aria-pressed="false"><span class="flag" aria-hidden="true">🇬🇧</span> English <span class="count" data-en-missing hidden></span></button>
          </div>
          <p class="help lang-help" data-lang-help-en hidden>Les textes anglais laissés vides sont remplacés par le texte français sur le site anglais.</p>
        </div>
      <?php endif ?>

      <?= render_sections($sections, $data, $errors) ?>
    </div>

    <aside class="editor-side">
      <?php if ($isEntry): ?>
        <section class="card">
          <h2>Publication</h2>
          <div class="field">
            <span class="label">Visibilité</span>
            <label class="radio-card"><input type="radio" name="status" value="published" <?= $entry['status'] === 'published' ? 'checked' : '' ?>><span><strong>Publié</strong><small>Visible par tout le monde sur le site.</small></span></label>
            <label class="radio-card"><input type="radio" name="status" value="draft" <?= $entry['status'] !== 'published' ? 'checked' : '' ?>><span><strong>Brouillon</strong><small>Enregistré, mais invisible sur le site.</small></span></label>
          </div>
          <?php if (count($entrySections) > 1): ?>
            <div class="field">
              <label for="section">Rubrique</label>
              <select name="section" id="section">
                <?php foreach ($entrySections as $s): ?>
                  <option value="<?= e($s) ?>" <?= $entry['section'] === $s ? 'selected' : '' ?>>Actualités <?= e($s === 'ecole' ? "de l'école" : ($s === 'apel' ? "de l'APEL" : "de l'OGEC")) ?></option>
                <?php endforeach ?>
              </select>
            </div>
          <?php else: ?>
            <input type="hidden" name="section" value="<?= e($entry['section']) ?>">
          <?php endif ?>
          <?php if (!$isNew): ?>
            <p class="help">Adresse sur le site :<br><a href="<?= e($viewUrl) ?>" target="_blank" rel="noopener" class="break"><?= e($viewUrl) ?></a></p>
          <?php endif ?>
        </section>
      <?php endif ?>

      <section class="card side-nav">
        <h2>Sur cette page</h2>
        <ol>
          <?php foreach ($sections as $i => $s): ?>
            <li><a href="#section-<?= $i ?>"><?= e($s['title']) ?></a></li>
          <?php endforeach ?>
        </ol>
      </section>

      <section class="card side-links">
        <?php if ($viewUrl): ?>
          <a class="btn btn-ghost btn-block" href="<?= e($viewUrl) ?>" target="_blank" rel="noopener"><?= icon('external') ?>Voir sur le site</a>
        <?php endif ?>
        <?php if ($historyUrl): ?>
          <a class="btn btn-ghost btn-block" href="<?= e($historyUrl) ?>"><?= icon('restore') ?>Versions précédentes</a>
        <?php endif ?>
        <p class="help">Astuce : <kbd>Ctrl</kbd> + <kbd>S</kbd> (ou <kbd>⌘</kbd> + <kbd>S</kbd> sur Mac) enregistre la page.</p>
      </section>
    </aside>
  </div>

  <div class="editor-footer">
    <?php if ($previewUrl): ?>
      <button type="submit" class="btn btn-secondary btn-large" formaction="<?= e($previewUrl) ?>" formtarget="_blank" data-preview><?= icon('eye') ?>Aperçu</button>
    <?php endif ?>
    <button type="submit" class="btn btn-primary btn-large" data-save><?= icon('save') ?>Enregistrer</button>
  </div>
</form>

<?php if ($isEntry && !$isNew): ?>
  <section class="card danger-zone">
    <h2>Autres actions</h2>
    <div class="actions-left">
      <form method="post" action="/admin/<?= e($slug) ?>/<?= (int) $entry['id'] ?>/dupliquer"><?= csrf_field() ?><button class="btn btn-secondary" type="submit"><?= icon('plus') ?>Faire une copie</button></form>
      <form method="post" action="/admin/<?= e($slug) ?>/<?= (int) $entry['id'] ?>/supprimer" data-confirm="Mettre « <?= e($entry['data']['title']['fr'] ?? '') ?> » à la corbeille ? Il disparaîtra du site, mais vous pourrez le récupérer depuis la corbeille." data-confirm-ok="Mettre à la corbeille">
        <?= csrf_field() ?><button class="btn btn-danger-outline" type="submit"><?= icon('trash') ?>Mettre à la corbeille</button>
      </form>
    </div>
  </section>
<?php endif ?>
