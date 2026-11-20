<?php
declare(strict_types=1);

/*
 * HTML for the admin form fields generated from the schema.
 * Input names follow PHP's array syntax: data[title][fr], data[cards][0][items][en]…
 * Repeater rows use the placeholder __i__ in their template; admin.js
 * renumbers every row before the form is sent.
 */

function icon(string $name, string $class = 'icon'): string
{
    $paths = [
        'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M10 21v-6h4v6"/>',
        'page' => '<path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"/><path d="M14 3v5h5"/><path d="M9 13h6M9 17h6"/>',
        'news' => '<path d="M4 5h13v14a2 2 0 0 0 2 2H6a2 2 0 0 1-2-2z"/><path d="M17 9h3v10a2 2 0 0 1-2 2"/><path d="M8 9h5M8 13h5M8 17h3"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'image' => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="m21 16-5-5-9 9"/>',
        'file' => '<path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"/><path d="M14 3v5h5"/>',
        'users' => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6 6 0 0 1 3.5 6"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'help' => '<circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 1 1 3.5 2.3c-.7.3-1 .9-1 1.7v.5"/><circle cx="12" cy="17" r=".6" fill="currentColor"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/>',
        'trash' => '<path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'up' => '<path d="m6 15 6-6 6 6"/>',
        'down' => '<path d="m6 9 6 6 6-6"/>',
        'left' => '<path d="m15 6-6 6 6 6"/>',
        'right' => '<path d="m9 6 6 6-6 6"/>',
        'x' => '<path d="M6 6l12 12M18 6 6 18"/>',
        'eye' => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
        'external' => '<path d="M14 4h6v6M20 4l-9 9"/><path d="M19 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5"/>',
        'check' => '<path d="m5 12 5 5 9-10"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'logout' => '<path d="M15 4h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-4"/><path d="M10 17l5-5-5-5M15 12H4"/>',
        'upload' => '<path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>',
        'megaphone' => '<path d="M3 10v4a1 1 0 0 0 1 1h3l6 4V5L7 9H4a1 1 0 0 0-1 1z"/><path d="M17 8a5 5 0 0 1 0 8"/>',
        'restore' => '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'save' => '<path d="M5 3h11l5 5v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z"/><path d="M7 3v6h9V3M7 21v-7h10v7"/>',
        'download' => '<path d="M12 4v12M7 11l5 5 5-5"/><path d="M4 20h16"/>',
        'link' => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
        'lock' => '<rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
    ];
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>';
}

/** Render every section of a form. $errors: path => message. */
function render_sections(array $sections, array $data, array $errors = []): string
{
    $html = '';
    foreach ($sections as $i => $s) {
        $html .= '<section class="card form-section" id="section-' . $i . '"><div class="form-section-head"><h2>' . e($s['title']) . '</h2>';
        if (!empty($s['help'])) {
            $html .= '<p class="muted">' . e($s['help']) . '</p>';
        }
        $html .= '</div>';
        foreach ($s['fields'] as $f) {
            $html .= render_field($f, $data[$f['name']] ?? null, 'data', $errors, $f['name']);
        }
        $html .= '</section>';
    }
    return $html;
}

function render_field(array $f, $value, string $prefix, array $errors, string $path): string
{
    $name = $prefix . '[' . $f['name'] . ']';
    $id = 'f-' . preg_replace('/[^a-z0-9]+/i', '-', $prefix . '-' . $f['name']);
    $error = $errors[$path] ?? null;
    $isLoc = !empty($f['loc']);
    $type = $f['type'];
    $labelFor = in_array($type, ['text', 'textarea', 'lines', 'email', 'url', 'tel', 'date', 'datetime', 'select', 'pagelink', 'color'], true);

    $cls = 'field field-' . $type . ($error ? ' has-error' : '') . ($isLoc ? ' is-loc' : '');
    $html = '<div class="' . $cls . '" data-field="' . e($path) . '">';
    $html .= '<div class="field-label">';
    $html .= $labelFor
        ? '<label for="' . $id . ($isLoc ? '-fr' : '') . '" data-for-base="' . $id . '">' . e($f['label']) . '</label>'
        : '<span class="label">' . e($f['label']) . '</span>';
    if (!empty($f['required'])) {
        $html .= ' <span class="req" title="Obligatoire">obligatoire</span>';
    }
    if ($isLoc) {
        $html .= ' <span class="loc-flag" aria-hidden="true"><span data-show-lang="fr">FR</span><span data-show-lang="en">EN</span></span>';
    }
    $html .= '</div>';
    if (!empty($f['help'])) {
        $html .= '<p class="help">' . e($f['help']) . '</p>';
    }
    if ($error) {
        $html .= '<p class="field-error" role="alert">' . e($error) . '</p>';
    }

    if ($isLoc) {
        $value = is_array($value) ? $value : [];
        foreach (LOCALES as $l) {
            $v = $value[$l] ?? ($type === 'table' ? [] : '');
            $html .= '<div class="loc-pane" data-lang="' . $l . '"' . ($l === 'en' ? ' hidden' : '') . '>';
            $html .= render_input($f, $v, $name . '[' . $l . ']', "$id-$l", $l);
            if ($l === 'en' && in_array($type, ['text', 'textarea', 'lines'], true) && trim((string) ($value['fr'] ?? '')) !== '') {
                $html .= '<details class="fr-ref"><summary>Voir le texte français</summary><p>' . nl2br(e((string) $value['fr'])) . '</p></details>';
            }
            if ($l === 'en') {
                $html .= '<p class="help fallback-note">Laissé vide, le texte français s’affichera sur le site anglais.</p>';
            }
            $html .= '</div>';
        }
    } else {
        $html .= render_input($f, $value, $name, $id, null);
    }
    return $html . '</div>';
}

function render_input(array $f, $value, string $name, string $id, ?string $lang): string
{
    $ph = isset($f['placeholder']) ? ' placeholder="' . e($f['placeholder']) . '"' : '';
    $max = isset($f['max']) ? ' maxlength="' . (int) $f['max'] . '" data-count' : '';
    $langAttr = $lang ? ' lang="' . $lang . '"' : '';
    switch ($f['type']) {
        case 'text':
            return '<input type="text" id="' . $id . '" name="' . e($name) . '" value="' . e($value) . '"' . $ph . $max . $langAttr . '>';
        case 'email':
        case 'tel':
            return '<input type="' . $f['type'] . '" id="' . $id . '" name="' . e($name) . '" value="' . e($value) . '"' . $ph . '>';
        case 'url':
            return '<input type="url" id="' . $id . '" name="' . e($name) . '" value="' . e($value) . '"' . $ph . ' inputmode="url">';
        case 'textarea':
            return '<textarea id="' . $id . '" name="' . e($name) . '" rows="' . (int) ($f['rows'] ?? 3) . '"' . $ph . $max . $langAttr . '>' . e($value) . '</textarea>';
        case 'lines':
            $n = max(3, min(14, substr_count((string) $value, "\n") + 2));
            return '<textarea class="lines" id="' . $id . '" name="' . e($name) . '" rows="' . $n . '"' . $langAttr . ' placeholder="Premier point&#10;Deuxième point&#10;…">' . e($value) . '</textarea>';
        case 'rich':
            return rich_editor($name, (string) $value, $id, $lang);
        case 'date':
            return '<input type="date" id="' . $id . '" name="' . e($name) . '" value="' . e($value) . '" class="short">';
        case 'datetime':
            return '<input type="datetime-local" id="' . $id . '" name="' . e($name) . '" value="' . e($value) . '" class="short">';
        case 'color':
            $v = $value ?: '#8BB1C5';
            return '<div class="color-input"><input type="color" id="' . $id . '" name="' . e($name) . '" value="' . e($v) . '"><code data-color-value>' . e($v) . '</code></div>';
        case 'toggle':
            $on = !empty($value);
            return '<label class="switch"><input type="hidden" name="' . e($name) . '" value="0"><input type="checkbox" name="' . e($name) . '" value="1"' . ($on ? ' checked' : '') . ' data-toggle>'
                . '<span class="switch-track" aria-hidden="true"><span class="switch-thumb"></span></span>'
                . '<span class="switch-text" data-on="' . e($f['on'] ?? 'Activé') . '" data-off="' . e($f['off'] ?? 'Désactivé') . '">' . e($on ? ($f['on'] ?? 'Activé') : ($f['off'] ?? 'Désactivé')) . '</span></label>';
        case 'select':
            $html = '<div class="choice-row">';
            $v = $value ?: ($f['default'] ?? array_key_first($f['options']));
            foreach ($f['options'] as $k => $label) {
                $html .= '<label class="choice choice-' . e($k) . '"><input type="radio" name="' . e($name) . '" value="' . e($k) . '"' . ($k === $v ? ' checked' : '') . '><span>' . e($label) . '</span></label>';
            }
            return $html . '</div>';
        case 'pagelink':
            $html = '<select id="' . $id . '" name="' . e($name) . '"><option value="">— Choisir une page —</option>';
            foreach (linkable_pages() as $path => $label) {
                $html .= '<option value="' . e($path) . '"' . ($path === $value ? ' selected' : '') . '>' . e($label) . '</option>';
            }
            return $html . '</select>';
        case 'image':
        case 'file':
            return media_input($f['type'], $name, (string) $value);
        case 'gallery':
            return gallery_input($name, is_array($value) ? $value : [], (int) ($f['max_items'] ?? 24));
        case 'table':
            return table_input($name, is_array($value) ? $value : []);
        case 'repeater':
            return repeater_input($f, is_array($value) ? $value : [], $name);
    }
    return '';
}

function rich_editor(string $name, string $value, string $id, ?string $lang): string
{
    $btn = fn ($cmd, $label, $text, $extra = '') => '<button type="button" data-cmd="' . $cmd . '" title="' . e($label) . '" aria-label="' . e($label) . '"' . $extra . '>' . $text . '</button>';
    return '<div class="rich" data-rich>'
        . '<div class="rich-toolbar" role="toolbar" aria-label="Mise en forme">'
        . $btn('formatBlock:p', 'Paragraphe normal', 'Texte')
        . $btn('formatBlock:h2', 'Grand titre', 'Titre')
        . $btn('formatBlock:h3', 'Petit titre', 'Sous-titre')
        . '<span class="sep"></span>'
        . $btn('bold', 'Gras', '<b>G</b>')
        . $btn('italic', 'Italique', '<i>I</i>')
        . $btn('underline', 'Souligné', '<u>S</u>')
        . '<span class="sep"></span>'
        . $btn('insertUnorderedList', 'Liste à puces', '• Liste')
        . $btn('insertOrderedList', 'Liste numérotée', '1. Liste')
        . $btn('formatBlock:blockquote', 'Citation', '« »')
        . '<span class="sep"></span>'
        . $btn('link', 'Ajouter un lien', icon('link', 'icon sm') . ' Lien')
        . $btn('unlink', 'Retirer le lien', 'Retirer le lien')
        . $btn('removeFormat', 'Effacer la mise en forme', 'Effacer')
        . '</div>'
        . '<div class="rich-area" contenteditable="true" role="textbox" aria-multiline="true" id="' . $id . '"' . ($lang ? ' lang="' . $lang . '"' : '') . ' spellcheck="true">' . $value . '</div>'
        . '<textarea name="' . e($name) . '" hidden>' . e($value) . '</textarea>'
        . '</div>';
}

function media_input(string $kind, string $name, string $value): string
{
    $m = media($value);
    $html = '<div class="media-input" data-media-input data-kind="' . $kind . '">';
    $html .= '<input type="hidden" name="' . e($name) . '" value="' . e($m ? $value : '') . '" data-media-value>';
    $html .= '<div class="media-preview" data-media-preview>' . media_preview_html($m, $kind) . '</div>';
    $html .= '<div class="media-actions">'
        . '<button type="button" class="btn btn-secondary" data-media-pick>' . icon($kind === 'image' ? 'image' : 'upload') . '<span data-pick-label>' . ($m ? ($kind === 'image' ? "Changer l'image" : 'Changer le fichier') : ($kind === 'image' ? 'Choisir une image' : 'Choisir un fichier')) . '</span></button>'
        . '<button type="button" class="btn btn-ghost" data-media-clear' . ($m ? '' : ' hidden') . '>' . icon('x') . 'Retirer</button>'
        . '</div>';
    return $html . '</div>';
}

function media_preview_html(?array $m, string $kind): string
{
    if (!$m) {
        return '<div class="media-empty">' . ($kind === 'image' ? icon('image', 'icon lg') . '<span>Aucune image</span>' : icon('file', 'icon lg') . '<span>Aucun fichier</span>') . '</div>';
    }
    if ($m['kind'] === 'image') {
        return '<img src="' . e(media_url($m, true)) . '" alt=""><div class="media-meta"><strong>' . e($m['original_name']) . '</strong><span>' . e(human_size((int) $m['size'])) . '</span>'
            . '<label class="alt-label">Description pour les personnes malvoyantes <input type="text" value="' . e($m['alt']) . '" data-alt-for="' . e($m['id']) . '" placeholder="Ex : Les élèves de CP dans la cour"></label></div>';
    }
    return '<div class="file-chip"><span class="file-badge">' . e(file_icon($m['mime'])) . '</span><div class="media-meta"><strong>' . e($m['original_name']) . '</strong><span>' . e(human_size((int) $m['size'])) . ' · <a href="' . e(media_url($m)) . '" target="_blank" rel="noopener">Ouvrir</a></span></div></div>';
}

function gallery_input(string $name, array $ids, int $max): string
{
    $html = '<div class="gallery-input" data-gallery data-name="' . e($name) . '[]" data-max="' . $max . '"><ul class="gallery-list" data-gallery-list>';
    foreach ($ids as $id) {
        if ($m = media((string) $id)) {
            $html .= gallery_item_html($m, $name . '[]');
        }
    }
    $html .= '</ul><button type="button" class="btn btn-secondary" data-gallery-add>' . icon('plus') . 'Ajouter des photos</button></div>';
    return $html;
}

function gallery_item_html(array $m, string $name): string
{
    return '<li class="gallery-item"><input type="hidden" name="' . e($name) . '" value="' . e($m['id']) . '"><img src="' . e(media_url($m, true)) . '" alt="">'
        . '<div class="gallery-tools"><button type="button" data-g-move="-1" aria-label="Déplacer avant">' . icon('left', 'icon sm') . '</button>'
        . '<button type="button" data-g-remove aria-label="Retirer cette photo">' . icon('x', 'icon sm') . '</button>'
        . '<button type="button" data-g-move="1" aria-label="Déplacer après">' . icon('right', 'icon sm') . '</button></div></li>';
}

function table_input(string $name, array $rows): string
{
    if (!$rows) {
        $rows = [['', ''], ['', '']];
    }
    $html = '<div class="table-input" data-table data-name="' . e($name) . '"><div class="table-scroll"><table><tbody>';
    foreach ($rows as $r => $row) {
        $html .= '<tr' . ($r === 0 ? ' class="head-row"' : '') . '>';
        foreach ($row as $cell) {
            $html .= '<td><input type="text" value="' . e($cell) . '" aria-label="' . ($r === 0 ? 'Titre de colonne' : 'Cellule') . '"></td>';
        }
        $html .= '<td class="row-tools"><button type="button" data-row-del title="Supprimer cette ligne" aria-label="Supprimer cette ligne">' . icon('trash', 'icon sm') . '</button></td></tr>';
    }
    $html .= '</tbody></table></div><div class="table-actions">'
        . '<button type="button" class="btn btn-small btn-secondary" data-row-add>' . icon('plus', 'icon sm') . 'Ajouter une ligne</button>'
        . '<button type="button" class="btn btn-small btn-ghost" data-col-add>' . icon('plus', 'icon sm') . 'Ajouter une colonne</button>'
        . '<button type="button" class="btn btn-small btn-ghost" data-col-del>' . icon('trash', 'icon sm') . 'Retirer la dernière colonne</button>'
        . '</div><p class="help">La première ligne (en gras) contient les titres des colonnes.</p></div>';
    return $html;
}

function repeater_input(array $f, array $items, string $name): string
{
    $label = $f['item_label'] ?? 'Élément';
    $html = '<div class="repeater' . (!empty($f['compact']) ? ' compact' : '') . '" data-repeater data-name="' . e($name) . '" data-item-label="' . e($label) . '"><ol class="repeater-items" data-items>';
    foreach (array_values($items) as $i => $item) {
        $html .= repeater_item($f, is_array($item) ? $item : [], $name, (string) $i, $label);
    }
    $html .= '</ol>';
    $html .= '<template data-template>' . repeater_item($f, [], $name, '__i__', $label) . '</template>';
    $html .= '<button type="button" class="btn btn-secondary add-item" data-add>' . icon('plus') . e($f['add_label'] ?? 'Ajouter') . '</button>';
    return $html . '</div>';
}

function repeater_item(array $f, array $item, string $name, string $index, string $label): string
{
    $first = $f['fields'][0];
    $summary = is_array($item[$first['name']] ?? null) ? ($item[$first['name']]['fr'] ?? '') : (string) ($item[$first['name']] ?? '');
    $html = '<li class="repeater-item" data-item>'
        . '<div class="item-head"><span class="item-number" data-number></span><span class="item-title" data-item-title>' . e($label) . '</span>'
        . '<span class="item-summary" data-summary>' . e(mb_strimwidth(strip_tags($summary), 0, 70, '…')) . '</span>'
        . '<div class="item-tools">'
        . '<button type="button" data-move="-1" title="Monter" aria-label="Monter">' . icon('up', 'icon sm') . '</button>'
        . '<button type="button" data-move="1" title="Descendre" aria-label="Descendre">' . icon('down', 'icon sm') . '</button>'
        . '<button type="button" data-remove class="danger" title="Supprimer" aria-label="Supprimer">' . icon('trash', 'icon sm') . '</button>'
        . '</div></div><div class="item-body">';
    foreach ($f['fields'] as $sub) {
        $html .= render_field($sub, $item[$sub['name']] ?? null, $name . '[' . $index . ']', [], '');
    }
    return $html . '</div></li>';
}
