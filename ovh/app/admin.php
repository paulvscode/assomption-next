<?php
declare(strict_types=1);

/*
 * Admin area: /admin/…
 * Every POST is CSRF-checked; every screen checks the user's role.
 */

require_once APP_DIR . '/admin_fields.php';
require_once APP_DIR . '/site_functions.php';

session_start_secure();
send_security_headers(true);

$path = substr(request_path(), strlen('/admin')) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'POST') {
    csrf_check();
}

// ── Screens available without being logged in ─────────────────────────────

if ($path === '/setup') {
    admin_setup();
}
if ($path === '/connexion' || $path === '/login') {
    admin_login();
}
if ($path === '/deconnexion' && $method === 'POST') {
    logout();
    session_start_secure();
    flash('success', 'Vous êtes déconnecté(e). À bientôt !');
    redirect('/admin/connexion');
}

$user = current_user();
if (!$user) {
    if (str_starts_with($path, '/api/')) {
        json_response(['error' => 'Votre session a expiré. Reconnectez-vous dans un autre onglet, puis réessayez.'], 401);
    }
    if ((int) q_one('SELECT COUNT(*) AS n FROM users')['n'] === 0) {
        redirect('/admin/setup');
    }
    $_SESSION['after_login'] = $_SERVER['REQUEST_URI'] ?? '/admin';
    redirect('/admin/connexion');
}

// First login with a temporary password: change it before anything else.
if ((int) $user['must_change_password'] === 1 && $path !== '/compte') {
    redirect('/admin/compte');
}

// ── Routes ────────────────────────────────────────────────────────────────

$routes = [
    ['GET', '#^/$#', 'admin_dashboard'],
    ['POST', '#^/annonce$#', 'admin_quick_banner'],
    ['GET', '#^/pages$#', 'admin_pages'],
    ['GET|POST', '#^/pages/([a-zA-Z]+)$#', 'admin_edit_page'],
    ['GET|POST', '#^/pages/([a-zA-Z]+)/historique$#', 'admin_page_history'],
    ['GET', '#^/(actualites|evenements)$#', 'admin_entries'],
    ['GET|POST', '#^/(actualites|evenements)/(nouveau|\d+)$#', 'admin_edit_entry'],
    ['POST', '#^/(actualites|evenements)/(\d+)/(supprimer|restaurer|dupliquer)$#', 'admin_entry_action'],
    ['GET|POST', '#^/(actualites|evenements)/(\d+)/historique$#', 'admin_entry_history'],
    ['GET', '#^/corbeille$#', 'admin_trash'],
    ['POST', '#^/apercu/page/([a-zA-Z]+)$#', 'admin_preview_page'],
    ['POST', '#^/apercu/(actualites|evenements)$#', 'admin_preview_entry'],
    ['GET|POST', '#^/medias$#', 'admin_media'],
    ['POST', '#^/medias/([a-f0-9]{16})/supprimer$#', 'admin_media_delete'],
    ['GET', '#^/api/medias$#', 'api_media_list'],
    ['GET', '#^/api/ping$#', 'api_ping'],
    ['POST', '#^/api/upload$#', 'api_upload'],
    ['POST', '#^/api/medias/([a-f0-9]{16})/alt$#', 'api_media_alt'],
    ['GET|POST', '#^/utilisateurs$#', 'admin_users'],
    ['GET|POST', '#^/utilisateurs/(nouveau|\d+)$#', 'admin_user_edit'],
    ['GET|POST', '#^/compte$#', 'admin_account'],
    ['GET', '#^/aide$#', 'admin_help'],
    ['GET|POST', '#^/sauvegarde$#', 'admin_backup'],
];
foreach ($routes as [$methods, $re, $handler]) {
    if (preg_match($re, $path, $m) && in_array($method, explode('|', $methods), true)) {
        array_shift($m);
        $handler(...$m);
        exit;
    }
}
admin_page('Page introuvable', render('admin/message', [
    'title' => 'Cette page n’existe pas',
    'text' => "Le lien que vous avez suivi est peut-être incomplet.",
]), 404);

// ── Rendering helpers ─────────────────────────────────────────────────────

function admin_page(string $title, string $content, int $code = 200, array $opts = []): never
{
    http_response_code($code);
    header('Content-Type: text/html; charset=utf-8');
    echo render('admin/layout', ['title' => $title, 'content' => $content, 'user' => current_user(), 'flashes' => take_flashes()] + $opts);
    exit;
}

function forbidden(): never
{
    admin_page('Accès refusé', render('admin/message', [
        'title' => "Vous n'avez pas accès à cette partie",
        'text' => "Votre compte ne permet pas de modifier ce contenu. Si vous pensez que c'est une erreur, demandez à l'administrateur du site.",
    ]), 403);
}

function require_admin(): void
{
    if (!is_admin()) {
        forbidden();
    }
}

function entry_type_from_slug(string $s): string
{
    return $s === 'evenements' ? 'event' : 'news';
}

/** Sections (école / APEL / OGEC) where the user may post news. */
function allowed_sections(string $type): array
{
    $sections = $type === 'event' ? ['apel'] : array_keys(ENTRY_SECTIONS);
    return array_values(array_filter($sections, fn ($s) => can_edit($s)));
}

// ── Setup & login ─────────────────────────────────────────────────────────

function admin_setup(): never
{
    $hasUsers = (int) q_one('SELECT COUNT(*) AS n FROM users')['n'] > 0;
    if ($hasUsers) {
        redirect('/admin/connexion');
    }
    $key = (string) config('setup_key');
    $errors = [];
    $old = ['name' => '', 'email' => ''];
    if (is_post()) {
        $old = ['name' => str_val($_POST['name'] ?? ''), 'email' => str_val($_POST['email'] ?? '')];
        $pwd = (string) ($_POST['password'] ?? '');
        if ($key === '' || !hash_equals($key, (string) ($_POST['setup_key'] ?? ''))) {
            $errors[] = "La clé d'installation est incorrecte. Elle se trouve dans le fichier app/config.php (setup_key).";
        }
        if ($old['name'] === '') {
            $errors[] = 'Indiquez votre nom.';
        }
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse e-mail n'est pas valide.";
        }
        if ($p = password_problem($pwd, $old['email'])) {
            $errors[] = $p;
        }
        if (!$errors) {
            q('INSERT INTO users (email, name, password_hash, role, created_at) VALUES (?, ?, ?, ?, ?)', [
                $old['email'], $old['name'], password_hash($pwd, PASSWORD_DEFAULT), 'admin', now(),
            ]);
            login_user(q_one('SELECT * FROM users WHERE email = ?', [$old['email']]));
            flash('success', 'Bienvenue ! Votre compte administrateur est créé.');
            redirect('/admin');
        }
    }
    http_response_code($key === '' ? 503 : 200);
    echo render('admin/setup', ['errors' => $errors, 'old' => $old, 'keyMissing' => $key === '']);
    exit;
}

function admin_login(): never
{
    if (current_user()) {
        redirect('/admin');
    }
    $error = null;
    $email = '';
    if (is_post()) {
        $email = str_val($_POST['email'] ?? '');
        $result = attempt_login($email, (string) ($_POST['password'] ?? ''));
        if ($result === 'locked') {
            $error = 'Trop de tentatives. Pour votre sécurité, attendez 15 minutes avant de réessayer.';
        } elseif ($result === 'invalid') {
            $error = 'Adresse e-mail ou mot de passe incorrect. Vérifiez les majuscules et réessayez.';
        } else {
            $next = $_SESSION['after_login'] ?? '/admin';
            login_user($result);
            $next = is_string($next) && str_starts_with($next, '/admin') && !str_starts_with($next, '//') ? $next : '/admin';
            redirect($next);
        }
    }
    echo render('admin/login', ['error' => $error, 'email' => $email, 'flashes' => take_flashes()]);
    exit;
}

// ── Dashboard ─────────────────────────────────────────────────────────────

function admin_dashboard(): void
{
    $user = current_user();
    $sections = allowed_sections('news');
    $recent = [];
    foreach (q_all('SELECT p.key, p.updated_at, u.name AS who FROM pages p LEFT JOIN users u ON u.id = p.updated_by WHERE p.updated_by IS NOT NULL ORDER BY p.updated_at DESC LIMIT 6') as $r) {
        $def = page_def($r['key']);
        if ($def) {
            $recent[] = ['label' => $def['label'], 'kind' => 'Page', 'url' => '/admin/pages/' . $r['key'], 'at' => $r['updated_at'], 'who' => $r['who']];
        }
    }
    foreach (q_all('SELECT e.id, e.type, e.status, e.data, e.updated_at, u.name AS who FROM entries e LEFT JOIN users u ON u.id = e.updated_by WHERE e.deleted_at IS NULL ORDER BY e.updated_at DESC LIMIT 6') as $r) {
        $d = json_decode($r['data'], true);
        $recent[] = [
            'label' => $d['title']['fr'] ?? 'Sans titre',
            'kind' => $r['type'] === 'event' ? 'Événement' : 'Actualité',
            'draft' => $r['status'] !== 'published',
            'url' => '/admin/' . ($r['type'] === 'event' ? 'evenements' : 'actualites') . '/' . $r['id'],
            'at' => $r['updated_at'],
            'who' => $r['who'],
        ];
    }
    usort($recent, fn ($a, $b) => strcmp($b['at'], $a['at']));

    $drafts = [];
    foreach (q_all("SELECT id, type, section, data, updated_at FROM entries WHERE status = 'draft' AND deleted_at IS NULL ORDER BY updated_at DESC") as $r) {
        if (can_edit($r['section'])) {
            $d = json_decode($r['data'], true);
            $drafts[] = ['label' => $d['title']['fr'] ?? 'Sans titre', 'url' => '/admin/' . ($r['type'] === 'event' ? 'evenements' : 'actualites') . '/' . $r['id'], 'at' => $r['updated_at']];
        }
    }

    // Pages that change every school year and have not been touched since 1 September.
    $yearStart = (int) date('n') >= 9 ? date('Y') . '-09-01' : (date('Y') - 1) . '-09-01';
    $stale = [];
    foreach (['professorsPage', 'backToSchool', 'schoolBreaks', 'schoolProject', 'applicationForm', 'fees'] as $k) {
        $def = page_def($k);
        $meta = page_meta($k);
        if ($def && can_edit($def['owner']) && (!$meta || !$meta['updated_by_name'] || $meta['updated_at'] < $yearStart)) {
            $stale[$k] = $def['label'];
        }
    }
    $noFutureDates = can_edit('ecole') && next_calendar_date() === null;

    admin_page('Accueil', render('admin/dashboard', [
        'stale' => $stale,
        'noFutureDates' => $noFutureDates,
        'schoolYear' => substr($yearStart, 0, 4) . '-' . ((int) substr($yearStart, 0, 4) + 1),
        'user' => $user,
        'sections' => $sections,
        'canEvents' => (bool) allowed_sections('event'),
        'canBanner' => can_edit('ecole'),
        'landing' => page_data('landing'),
        'recent' => array_slice($recent, 0, 8),
        'drafts' => $drafts,
        'myPages' => array_filter(page_defs(), fn ($d) => can_edit($d['owner']) && $d['owner'] !== 'admin'),
    ]));
}

function admin_quick_banner(): never
{
    if (!can_edit('ecole')) {
        forbidden();
    }
    $data = page_data('landing');
    $data['bannerEnabled'] = !empty($_POST['bannerEnabled']);
    $data['bannerText'] = [
        'fr' => mb_substr(str_val($_POST['bannerText_fr'] ?? ''), 0, 200),
        'en' => mb_substr(str_val($_POST['bannerText_en'] ?? ''), 0, 200),
    ];
    $style = str_val($_POST['bannerStyle'] ?? 'info');
    $data['bannerStyle'] = in_array($style, ['info', 'urgent', 'calm'], true) ? $style : 'info';
    if ($data['bannerEnabled'] && $data['bannerText']['fr'] === '') {
        flash('error', "Écrivez le texte de l'annonce avant de l'afficher.");
        redirect('/admin#annonce');
    }
    save_page('landing', $data, (int) current_user()['id']);
    flash('success', $data['bannerEnabled'] ? "C'est en ligne : l'annonce s'affiche en haut de la page d'accueil." : "L'annonce est masquée du site.", ['label' => 'Voir le site', 'url' => '/fr']);
    redirect('/admin#annonce');
}

// ── Pages ─────────────────────────────────────────────────────────────────

function admin_pages(): void
{
    $groups = [];
    foreach (page_defs() as $key => $def) {
        if (can_edit($def['owner'])) {
            $groups[$def['group']][$key] = $def + ['meta' => page_meta($key)];
        }
    }
    admin_page('Pages du site', render('admin/pages', ['groups' => $groups]));
}

function admin_edit_page(string $key): void
{
    $def = page_def($key);
    if (!$def) {
        redirect('/admin/pages');
    }
    if (!can_edit($def['owner'])) {
        forbidden();
    }
    $errors = [];
    $data = page_data($key);
    if (is_post()) {
        $data = clean_fields(page_fields($def), (array) ($_POST['data'] ?? []), $errors);
        if (!$errors) {
            save_page($key, $data, (int) current_user()['id']);
            $action = $def['path'] !== null ? ['label' => 'Voir la page sur le site', 'url' => site_url($def['path'], 'fr'), 'external' => true] : null;
            flash('success', 'C’est enregistré ! Les modifications sont déjà visibles sur le site.', $action);
            redirect("/admin/pages/$key");
        }
        http_response_code(422);
    }
    $meta = page_meta($key);
    admin_page($def['label'], render('admin/edit', [
        'kind' => 'page',
        'def' => $def,
        'key' => $key,
        'sections' => $def['sections'],
        'data' => $data,
        'errors' => $errors,
        'meta' => $meta,
        'action' => "/admin/pages/$key",
        'back' => ['/admin/pages', 'Toutes les pages'],
        'viewUrl' => $def['path'] !== null ? site_url($def['path'], 'fr') : null,
        'previewUrl' => $def['path'] !== null ? "/admin/apercu/page/$key" : null,
        'historyUrl' => "/admin/pages/$key/historique",
    ]), $errors ? 422 : 200, ['wide' => true]);
}

function admin_page_history(string $key): void
{
    $def = page_def($key);
    if (!$def || !can_edit($def['owner'])) {
        forbidden();
    }
    $target = "page:$key";
    if (is_post()) {
        $rev = q_one('SELECT * FROM revisions WHERE id = ? AND target = ?', [(int) ($_POST['revision'] ?? 0), $target]);
        if ($rev) {
            save_page($key, json_decode($rev['data'], true) ?: [], (int) current_user()['id']);
            flash('success', 'La version du ' . format_date($rev['created_at'], 'fr', true) . ' a été restaurée. La version que vous venez de remplacer reste disponible dans l’historique.');
        }
        redirect("/admin/pages/$key");
    }
    admin_page('Historique — ' . $def['label'], render('admin/history', [
        'label' => $def['label'],
        'revisions' => revisions_for($target),
        'back' => "/admin/pages/$key",
        'action' => "/admin/pages/$key/historique",
    ]));
}

function admin_preview_page(string $key): never
{
    $def = page_def($key);
    if (!$def || !can_edit($def['owner']) || $def['path'] === null) {
        forbidden();
    }
    $errors = [];
    $data = clean_fields(page_fields($def), (array) ($_POST['data'] ?? []), $errors);
    $GLOBALS['page_override'][$key] = $data;
    $GLOBALS['locale'] = in_array($_POST['_lang'] ?? 'fr', LOCALES, true) ? $_POST['_lang'] : 'fr';
    header('X-Robots-Tag: noindex');
    render_page($key, ['notice' => tr('previewNotice'), 'noticeStyle' => null, 'editUrl' => null]);
}

// ── News & events ─────────────────────────────────────────────────────────

function admin_entries(string $slug): void
{
    $type = entry_type_from_slug($slug);
    $sections = allowed_sections($type);
    if (!$sections) {
        forbidden();
    }
    $filter = in_array($_GET['section'] ?? '', $sections, true) ? $_GET['section'] : null;
    $search = str_val($_GET['q'] ?? '');
    $in = implode(',', array_fill(0, count($sections), '?'));
    $params = array_merge([$type], $sections);
    $sql = "SELECT e.*, u.name AS who FROM entries e LEFT JOIN users u ON u.id = e.updated_by WHERE e.type = ? AND e.section IN ($in) AND e.deleted_at IS NULL";
    if ($filter) {
        $sql .= ' AND e.section = ?';
        $params[] = $filter;
    }
    if ($search !== '') {
        $sql .= ' AND e.data LIKE ?';
        $params[] = '%' . str_replace(['%', '_'], ['\%', '\_'], $search) . '%';
        $sql .= " ESCAPE '\\'";
    }
    $sql .= $type === 'event' ? ' ORDER BY e.date DESC' : ' ORDER BY e.date DESC, e.id DESC';
    $entries = array_map('entry_decode', q_all($sql, $params));
    $trashCount = (int) q_one("SELECT COUNT(*) AS n FROM entries WHERE type = ? AND section IN ($in) AND deleted_at IS NOT NULL", array_merge([$type], $sections))['n'];
    admin_page($type === 'event' ? 'Événements' : 'Actualités', render('admin/entries', [
        'type' => $type,
        'slug' => $slug,
        'entries' => $entries,
        'sections' => $sections,
        'filter' => $filter,
        'search' => $search,
        'trashCount' => $trashCount,
    ]));
}

function admin_edit_entry(string $slug, string $id): void
{
    $type = entry_type_from_slug($slug);
    $sections = allowed_sections($type);
    if (!$sections) {
        forbidden();
    }
    $isNew = $id === 'nouveau';
    if ($isNew) {
        $want = (string) ($_POST['section'] ?? $_GET['section'] ?? '');
        $section = in_array($want, $sections, true) ? $want : $sections[0];
        $entry = [
            'id' => null, 'type' => $type, 'section' => $section, 'status' => 'published', 'slug' => '',
            'data' => $type === 'event' ? ['start' => date('Y-m-d') . 'T18:00'] : ['date' => date('Y-m-d')],
        ];
    } else {
        $entry = find_entry((int) $id);
        if (!$entry || $entry['type'] !== $type || $entry['deleted_at']) {
            flash('error', "Ce contenu n'existe plus (il a peut-être été supprimé).");
            redirect("/admin/$slug");
        }
        if (!can_edit($entry['section'])) {
            forbidden();
        }
    }
    $sectionsDef = entry_fields($type);
    $errors = [];
    if (is_post()) {
        $fields = array_merge(...array_map(fn ($s) => $s['fields'], $sectionsDef));
        $data = clean_fields($fields, (array) ($_POST['data'] ?? []), $errors);
        $status = ($_POST['status'] ?? '') === 'draft' ? 'draft' : 'published';
        $section = in_array($_POST['section'] ?? '', $sections, true) ? $_POST['section'] : $entry['section'];
        if ($type === 'event' && $data['end'] !== '' && $data['end'] < $data['start']) {
            $errors['end'] = 'La fin doit être après le début.';
        }
        $entry['data'] = $data;
        $entry['status'] = $status;
        $entry['section'] = $section;
        if (!$errors) {
            $uid = (int) current_user()['id'];
            $date = $type === 'event' ? $data['start'] : $data['date'];
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($isNew) {
                $entrySlug = unique_slug($type, $section, slugify($data['title']['fr']), null);
                q('INSERT INTO entries (type, section, slug, status, date, data, created_at, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [$type, $section, $entrySlug, $status, $date, $json, now(), now(), $uid]);
                $newId = (int) db()->lastInsertId();
            } else {
                $old = q_one('SELECT * FROM entries WHERE id = ?', [$entry['id']]);
                $entrySlug = $old['section'] === $section ? $old['slug'] : unique_slug($type, $section, $old['slug'], (int) $entry['id']);
                add_revision('entry:' . $entry['id'], json_encode(['data' => json_decode($old['data'], true), 'status' => $old['status'], 'section' => $old['section']], JSON_UNESCAPED_UNICODE), $uid);
                q('UPDATE entries SET section = ?, slug = ?, status = ?, date = ?, data = ?, updated_at = ?, updated_by = ? WHERE id = ?',
                    [$section, $entrySlug, $status, $date, $json, now(), $uid, $entry['id']]);
                $newId = (int) $entry['id'];
            }
            $saved = find_entry($newId);
            if ($status === 'published') {
                flash('success', $isNew ? 'C’est publié ! Votre ' . ($type === 'event' ? 'événement' : 'actualité') . ' est en ligne.' : 'C’est enregistré ! Les modifications sont en ligne.',
                    ['label' => 'Voir sur le site', 'url' => entry_url($saved, 'fr'), 'external' => true]);
            } else {
                flash('info', 'Brouillon enregistré. Il n’est pas visible sur le site tant que vous ne le publiez pas.',
                    ['label' => 'Voir l’aperçu', 'url' => entry_url($saved, 'fr'), 'external' => true]);
            }
            redirect("/admin/$slug/$newId");
        }
    }
    $label = $type === 'event' ? 'événement' : 'actualité';
    $title = $isNew ? ($type === 'event' ? 'Nouvel événement' : 'Nouvelle actualité') : (($entry['data']['title']['fr'] ?? '') ?: 'Sans titre');
    admin_page($title, render('admin/edit', [
        'kind' => 'entry',
        'type' => $type,
        'slug' => $slug,
        'entry' => $entry,
        'isNew' => $isNew,
        'sections' => $sectionsDef,
        'entrySections' => $sections,
        'data' => $entry['data'],
        'errors' => $errors,
        'def' => ['label' => $title, 'intro' => null],
        'action' => "/admin/$slug/" . ($isNew ? 'nouveau' : $entry['id']),
        'back' => ["/admin/$slug", $type === 'event' ? 'Tous les événements' : 'Toutes les actualités'],
        'viewUrl' => !$isNew ? entry_url($entry, 'fr') : null,
        'previewUrl' => "/admin/apercu/$slug",
        'historyUrl' => !$isNew ? "/admin/$slug/{$entry['id']}/historique" : null,
        'meta' => !$isNew ? ['updated_at' => $entry['updated_at'], 'updated_by_name' => q_one('SELECT name FROM users WHERE id = ?', [$entry['updated_by']])['name'] ?? null] : null,
        'label' => $label,
    ]), $errors ? 422 : 200, ['wide' => true]);
}

function admin_entry_action(string $slug, string $id, string $action): never
{
    $type = entry_type_from_slug($slug);
    $entry = find_entry((int) $id);
    if (!$entry || $entry['type'] !== $type || !can_edit($entry['section'])) {
        forbidden();
    }
    $what = $type === 'event' ? "L'événement" : "L'actualité";
    $title = '« ' . ($entry['data']['title']['fr'] ?? 'Sans titre') . ' »';
    switch ($action) {
        case 'supprimer':
            q('UPDATE entries SET deleted_at = ?, updated_by = ? WHERE id = ?', [now(), current_user()['id'], $entry['id']]);
            flash('success', "$what $title a été mise à la corbeille. Elle n'est plus visible sur le site.", ['label' => 'Annuler', 'url' => "/admin/$slug/{$entry['id']}/restaurer", 'post' => true]);
            redirect("/admin/$slug");
        case 'restaurer':
            q('UPDATE entries SET deleted_at = NULL, updated_at = ?, updated_by = ? WHERE id = ?', [now(), current_user()['id'], $entry['id']]);
            flash('success', "$what $title est de retour" . ($entry['status'] === 'published' ? ' sur le site.' : ' (en brouillon).'));
            redirect("/admin/$slug/{$entry['id']}");
        case 'dupliquer':
            $data = $entry['data'];
            $data['title']['fr'] = ($data['title']['fr'] ?? '') . ' (copie)';
            $date = $type === 'event' ? $data['start'] : $data['date'];
            q('INSERT INTO entries (type, section, slug, status, date, data, created_at, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $type, $entry['section'], unique_slug($type, $entry['section'], slugify($data['title']['fr']), null), 'draft', $date,
                json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), now(), now(), current_user()['id'],
            ]);
            flash('success', 'Copie créée en brouillon. Modifiez-la puis publiez-la.');
            redirect("/admin/$slug/" . db()->lastInsertId());
    }
    redirect("/admin/$slug");
}

function admin_entry_history(string $slug, string $id): void
{
    $entry = find_entry((int) $id);
    if (!$entry || !can_edit($entry['section'])) {
        forbidden();
    }
    $target = 'entry:' . $entry['id'];
    if (is_post()) {
        $rev = q_one('SELECT * FROM revisions WHERE id = ? AND target = ?', [(int) ($_POST['revision'] ?? 0), $target]);
        if ($rev) {
            $r = json_decode($rev['data'], true) ?: [];
            $uid = (int) current_user()['id'];
            $cur = q_one('SELECT data, status, section FROM entries WHERE id = ?', [$entry['id']]);
            add_revision($target, json_encode(['data' => json_decode($cur['data'], true), 'status' => $cur['status'], 'section' => $cur['section']], JSON_UNESCAPED_UNICODE), $uid);
            $data = $r['data'] ?? [];
            q('UPDATE entries SET data = ?, status = ?, date = ?, updated_at = ?, updated_by = ? WHERE id = ?', [
                json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                $r['status'] ?? $entry['status'],
                $entry['type'] === 'event' ? ($data['start'] ?? $entry['date']) : ($data['date'] ?? $entry['date']),
                now(), $uid, $entry['id'],
            ]);
            flash('success', 'La version du ' . format_date($rev['created_at'], 'fr', true) . ' a été restaurée.');
        }
        redirect("/admin/$slug/{$entry['id']}");
    }
    $label = $entry['data']['title']['fr'] ?? 'Sans titre';
    admin_page('Historique — ' . $label, render('admin/history', [
        'label' => $label,
        'revisions' => revisions_for($target),
        'back' => "/admin/$slug/{$entry['id']}",
        'action' => "/admin/$slug/{$entry['id']}/historique",
    ]));
}

function admin_preview_entry(string $slug): never
{
    $type = entry_type_from_slug($slug);
    $sections = allowed_sections($type);
    if (!$sections) {
        forbidden();
    }
    $errors = [];
    $fields = array_merge(...array_map(fn ($s) => $s['fields'], entry_fields($type)));
    $data = clean_fields($fields, (array) ($_POST['data'] ?? []), $errors);
    $section = in_array($_POST['section'] ?? '', $sections, true) ? $_POST['section'] : $sections[0];
    $GLOBALS['locale'] = in_array($_POST['_lang'] ?? 'fr', LOCALES, true) ? $_POST['_lang'] : 'fr';
    header('X-Robots-Tag: noindex');
    $entry = ['id' => null, 'type' => $type, 'section' => $section, 'slug' => 'apercu', 'status' => 'published', 'data' => $data];
    render_entry($type, $section, 'apercu', $entry, tr('previewNotice'));
}

function admin_trash(): void
{
    $rows = [];
    foreach (q_all('SELECT e.*, u.name AS who FROM entries e LEFT JOIN users u ON u.id = e.updated_by WHERE e.deleted_at IS NOT NULL ORDER BY e.deleted_at DESC') as $r) {
        if (can_edit($r['section'])) {
            $rows[] = entry_decode($r);
        }
    }
    admin_page('Corbeille', render('admin/trash', ['entries' => $rows]));
}

// ── Media ─────────────────────────────────────────────────────────────────

function admin_media(): void
{
    if (is_post() && !empty($_FILES['files'])) {
        $ok = 0;
        $errs = [];
        foreach (normalize_files($_FILES['files']) as $file) {
            try {
                store_upload($file, 'file', (int) current_user()['id']);
                $ok++;
            } catch (UploadError $e) {
                $errs[] = $file['name'] . ' : ' . $e->getMessage();
            }
        }
        if ($ok) {
            flash('success', $ok === 1 ? 'Le fichier a été ajouté.' : "$ok fichiers ont été ajoutés.");
        }
        foreach ($errs as $er) {
            flash('error', $er);
        }
        redirect('/admin/medias' . (isset($_GET['type']) ? '?type=' . urlencode((string) $_GET['type']) : ''));
    }
    $kind = in_array($_GET['type'] ?? '', ['image', 'file'], true) ? $_GET['type'] : null;
    $search = str_val($_GET['q'] ?? '');
    $sql = 'SELECT m.*, u.name AS who FROM media m LEFT JOIN users u ON u.id = m.created_by WHERE 1';
    $params = [];
    if ($kind) {
        $sql .= ' AND m.kind = ?';
        $params[] = $kind;
    }
    if ($search !== '') {
        $sql .= ' AND (m.original_name LIKE ? OR m.alt LIKE ?)';
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    $items = q_all($sql . ' ORDER BY m.created_at DESC LIMIT 300', $params);
    admin_page('Images & documents', render('admin/media', ['items' => $items, 'kind' => $kind, 'search' => $search]), 200, ['wide' => true]);
}

function admin_media_delete(string $id): never
{
    $m = media($id);
    if (!$m) {
        redirect('/admin/medias');
    }
    if (!is_admin() && (int) $m['created_by'] !== (int) current_user()['id']) {
        flash('error', "Seule la personne qui a ajouté ce fichier (ou un administrateur) peut le supprimer.");
        redirect('/admin/medias');
    }
    $uses = media_usage($id);
    if ($uses) {
        flash('error', '« ' . $m['original_name'] . ' » est encore utilisé dans : ' . implode(', ', array_column($uses, 'label')) . '. Retirez-le de ces pages avant de le supprimer.');
        redirect('/admin/medias');
    }
    delete_media($m);
    flash('success', '« ' . $m['original_name'] . ' » a été supprimé.');
    redirect('/admin/medias');
}

function normalize_files(array $f): array
{
    if (!is_array($f['name'])) {
        return [$f];
    }
    $out = [];
    foreach ($f['name'] as $i => $name) {
        $out[] = ['name' => $name, 'type' => $f['type'][$i], 'tmp_name' => $f['tmp_name'][$i], 'error' => $f['error'][$i], 'size' => $f['size'][$i]];
    }
    return $out;
}

function media_json(array $m): array
{
    return [
        'id' => $m['id'],
        'kind' => $m['kind'],
        'name' => $m['original_name'],
        'url' => media_url($m),
        'thumb' => media_url($m, true),
        'size' => human_size((int) $m['size']),
        'alt' => $m['alt'],
        'badge' => file_icon($m['mime']),
        'preview' => media_preview_html($m, $m['kind']),
    ];
}

function api_media_list(): never
{
    $kind = ($_GET['kind'] ?? '') === 'image' ? 'image' : null;
    $q = str_val($_GET['q'] ?? '');
    $sql = 'SELECT * FROM media WHERE 1';
    $p = [];
    if ($kind) {
        $sql .= ' AND kind = ?';
        $p[] = $kind;
    }
    if ($q !== '') {
        $sql .= ' AND (original_name LIKE ? OR alt LIKE ?)';
        $p[] = "%$q%";
        $p[] = "%$q%";
    }
    json_response(['items' => array_map('media_json', q_all($sql . ' ORDER BY created_at DESC LIMIT 120', $p))]);
}

/** Called every few minutes while a form is open, so a long edit never hits the idle timeout. */
function api_ping(): never
{
    json_response(['ok' => true]);
}

function api_upload(): never
{
    $expect = ($_POST['kind'] ?? '') === 'image' ? 'image' : 'file';
    if (empty($_FILES['file'])) {
        json_response(['error' => 'Aucun fichier reçu. Il est peut-être trop lourd (maximum ' . max_upload_mb() . ' Mo).'], 400);
    }
    try {
        $m = store_upload($_FILES['file'], $expect, (int) current_user()['id']);
        json_response(['item' => media_json($m)]);
    } catch (UploadError $e) {
        json_response(['error' => $e->getMessage()], 422);
    }
}

function api_media_alt(string $id): never
{
    if (!media($id)) {
        json_response(['error' => 'Fichier introuvable'], 404);
    }
    q('UPDATE media SET alt = ? WHERE id = ?', [mb_substr(str_val($_POST['alt'] ?? ''), 0, 250), $id]);
    json_response(['ok' => true]);
}

// ── Users ─────────────────────────────────────────────────────────────────

function admin_users(): void
{
    require_admin();
    $users = q_all('SELECT * FROM users ORDER BY active DESC, name COLLATE NOCASE');
    admin_page('Comptes', render('admin/users', ['users' => $users, 'me' => current_user()]));
}

function admin_user_edit(string $id): void
{
    require_admin();
    $me = current_user();
    $isNew = $id === 'nouveau';
    $u = $isNew ? ['id' => null, 'name' => '', 'email' => '', 'role' => 'ecole', 'active' => 1] : q_one('SELECT * FROM users WHERE id = ?', [(int) $id]);
    if (!$u) {
        redirect('/admin/utilisateurs');
    }
    $errors = [];
    $tempPassword = null;
    if (is_post()) {
        $op = $_POST['op'] ?? 'save';
        if (!$isNew && $op === 'reset') {
            $tempPassword = generate_temp_password();
            q('UPDATE users SET password_hash = ?, must_change_password = 1 WHERE id = ?', [password_hash($tempPassword, PASSWORD_DEFAULT), $u['id']]);
            q('DELETE FROM login_attempts WHERE email = ?', [mb_strtolower($u['email'])]);
        } else {
            $u['name'] = str_val($_POST['name'] ?? '');
            $u['email'] = str_val($_POST['email'] ?? '');
            $role = (string) ($_POST['role'] ?? '');
            $u['role'] = array_key_exists($role, ROLES) ? $role : 'ecole';
            $u['active'] = $isNew ? 1 : (int) !empty($_POST['active']);
            if ($u['name'] === '') {
                $errors[] = 'Indiquez le nom de la personne.';
            }
            if (!filter_var($u['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse e-mail n'est pas valide.";
            } elseif (q_one('SELECT id FROM users WHERE email = ? AND id IS NOT ?', [$u['email'], $u['id']])) {
                $errors[] = 'Un compte existe déjà avec cette adresse e-mail.';
            }
            if (!$isNew && (int) $u['id'] === (int) $me['id'] && ($u['role'] !== 'admin' || !$u['active'])) {
                $errors[] = 'Vous ne pouvez pas retirer vos propres droits d’administrateur ni désactiver votre propre compte.';
            }
            if (!$errors) {
                if ($isNew) {
                    $tempPassword = generate_temp_password();
                    q('INSERT INTO users (email, name, password_hash, role, must_change_password, created_at) VALUES (?, ?, ?, ?, 1, ?)',
                        [$u['email'], $u['name'], password_hash($tempPassword, PASSWORD_DEFAULT), $u['role'], now()]);
                    $u['id'] = (int) db()->lastInsertId();
                    $isNew = false;
                } else {
                    q('UPDATE users SET name = ?, email = ?, role = ?, active = ? WHERE id = ?', [$u['name'], $u['email'], $u['role'], $u['active'], $u['id']]);
                    flash('success', 'Le compte de ' . $u['name'] . ' est enregistré.');
                    redirect('/admin/utilisateurs');
                }
            }
        }
    }
    admin_page($isNew ? 'Ajouter une personne' : $u['name'], render('admin/user-form', [
        'u' => $u, 'isNew' => $isNew, 'errors' => $errors, 'tempPassword' => $tempPassword, 'me' => $me,
    ]));
}

function admin_account(): void
{
    $u = current_user();
    $errors = [];
    if (is_post()) {
        $current = (string) ($_POST['current'] ?? '');
        $new = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['confirm'] ?? '');
        if (!password_verify($current, $u['password_hash'])) {
            $errors[] = 'Votre mot de passe actuel est incorrect.';
        }
        if ($new !== $confirm) {
            $errors[] = 'Les deux nouveaux mots de passe ne sont pas identiques.';
        }
        if ($p = password_problem($new, $u['email'])) {
            $errors[] = $p;
        }
        if ($new === $current) {
            $errors[] = 'Choisissez un mot de passe différent de l’actuel.';
        }
        if (!$errors) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            q('UPDATE users SET password_hash = ?, must_change_password = 0 WHERE id = ?', [$hash, $u['id']]);
            refresh_session_password($hash);
            flash('success', 'Votre nouveau mot de passe est enregistré.');
            redirect('/admin');
        }
    }
    admin_page('Mon compte', render('admin/account', ['u' => $u, 'errors' => $errors]));
}

function admin_help(): void
{
    admin_page('Aide', render('admin/help', ['user' => current_user()]), 200);
}

function admin_backup(): void
{
    require_admin();
    if (is_post()) {
        $tmp = tempnam(sys_get_temp_dir(), 'bak');
        @unlink($tmp);
        db()->exec('VACUUM INTO ' . db()->quote($tmp));
        header('Content-Type: application/vnd.sqlite3');
        header('Content-Disposition: attachment; filename="sauvegarde-site-' . date('Y-m-d') . '.sqlite"');
        header('Content-Length: ' . filesize($tmp));
        readfile($tmp);
        @unlink($tmp);
        exit;
    }
    $dbFile = rtrim((string) config('data_dir'), '/') . '/site.sqlite';
    $stats = [
        'pages' => (int) q_one('SELECT COUNT(*) AS n FROM pages')['n'],
        'entries' => (int) q_one('SELECT COUNT(*) AS n FROM entries WHERE deleted_at IS NULL')['n'],
        'media' => (int) q_one('SELECT COUNT(*) AS n FROM media')['n'],
        'mediaSize' => (int) q_one('SELECT COALESCE(SUM(size), 0) AS n FROM media')['n'],
        'dbSize' => is_file($dbFile) ? filesize($dbFile) : 0,
    ];
    admin_page('Sauvegarde', render('admin/backup', ['stats' => $stats]));
}
