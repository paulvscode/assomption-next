<?php
declare(strict_types=1);

/*
 * Public site router.
 * URLs are the same as on the previous Next.js site: /fr/..., /en/...
 */

require_once APP_DIR . '/site_functions.php';

session_resume_if_any();
$viewer = current_user(); // logged-in staff see drafts and an "edit" button

$path = request_path();

if ($path === '/') {
    $pref = strtolower(substr((string) ($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr'), 0, 2));
    redirect($pref === 'en' ? '/en' : '/fr', 302);
}
if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\nDisallow: /admin\nSitemap: " . base_url('/sitemap.xml') . "\n";
    exit;
}
if ($path === '/sitemap.xml') {
    send_sitemap();
}

$parts = explode('/', trim($path, '/'));
$loc = array_shift($parts);
if (!in_array($loc, LOCALES, true)) {
    not_found('fr');
}
$GLOBALS['locale'] = $loc;
$rest = implode('/', $parts);

// Home
if ($rest === '') {
    render_page('landing');
}

// Fixed pages
foreach (page_defs() as $key => $def) {
    if ($def['path'] !== null && $def['path'] !== '' && $def['path'] === $rest) {
        render_page($key);
    }
}

// News lists and articles
foreach (ENTRY_SECTIONS as $section => $s) {
    if ($rest === $s['news_path']) {
        render_list('news', $section);
    }
    if (preg_match('#^' . preg_quote($s['news_path'], '#') . '/([a-z0-9-]+)$#', $rest, $m)) {
        render_entry('news', $section, $m[1]);
    }
}
if ($rest === 'apel/evenements') {
    render_list('event', 'apel');
}
if (preg_match('#^apel/evenements/([a-z0-9-]+)$#', $rest, $m)) {
    render_entry('event', 'apel', $m[1]);
}

not_found($loc);
