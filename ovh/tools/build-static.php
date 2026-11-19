<?php
declare(strict_types=1);

/*
 * Builds a static, read-only demo of the site (public pages + clickable admin)
 * for hosting on GitHub Pages. Nothing can be saved in the demo.
 *
 *   php -S localhost:8000 -t ovh/www ovh/www/index.php      (in another terminal)
 *   php ovh/tools/build-static.php <out-dir> <base-path> <admin-session-id> [server]
 *   e.g. php ovh/tools/build-static.php /tmp/demo /assomption-next abc123
 *
 * <admin-session-id> is the value of the "assomption_session" cookie of a
 * logged-in administrator (used to capture the admin screens).
 */

if (PHP_SAPI !== 'cli' || $argc < 4) {
    fwrite(STDERR, "Usage: php build-static.php <out-dir> <base-path> <admin-session-id> [http://localhost:8000]\n");
    exit(1);
}
[$_, $out, $base, $sid] = $argv;
$server = rtrim($argv[4] ?? 'http://localhost:8000', '/');
$base = '/' . trim($base, '/');
$www = realpath(__DIR__ . '/../www');

require __DIR__ . '/../app/bootstrap.php';

function fetch(string $url, ?string $sid): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => false, CURLOPT_HTTPHEADER => ['Accept-Language: fr']]);
    if ($sid) {
        curl_setopt($ch, CURLOPT_COOKIE, "assomption_session=$sid");
    }
    $body = (string) curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    return [$code, $body];
}

function rewrite(string $html, string $base, bool $admin): string
{
    // Absolute site links → under the GitHub Pages sub-path.
    $html = preg_replace('#\b(href|src|action|formaction|content)="/(?!/)#', '$1="' . $base . '/', $html);
    $html = str_replace('<head>', '<head><meta name="robots" content="noindex, nofollow"><script>window.DEMO_BASE=' . json_encode($base) . ';</script>', $html);
    if ($admin) {
        $html = str_replace('<script src="' . $base . '/assets/admin.js', '<script src="' . $base . '/assets/demo.js"></script><script src="' . $base . '/assets/admin.js', $html);
    } else {
        $html = preg_replace('#<body([^>]*)>#', '<body$1><div style="background:#2B6684;color:#fff;font:600 13px/1.4 system-ui;padding:8px 16px;text-align:center">Version de démonstration · <a href="' . $base . '/admin/" style="color:#fff;text-decoration:underline">Découvrir l’espace d’administration →</a></div>', $html, 1);
    }
    return $html;
}

function save(string $out, string $path, string $html): void
{
    $file = rtrim($out, '/') . '/' . trim($path, '/');
    $file = str_ends_with($file, '.json') || str_ends_with($file, '.xml') || str_ends_with($file, '.html') ? $file : rtrim($file, '/') . '/index.html';
    @mkdir(dirname($file), 0755, true);
    file_put_contents($file, $html);
}

// ── Which pages to capture ─────────────────────────────────────────────────
$public = [];
foreach (LOCALES as $l) {
    $public[] = "/$l";
    foreach (page_defs() as $def) {
        if ($def['path'] !== null && $def['path'] !== '') {
            $public[] = "/$l/{$def['path']}";
        }
    }
    foreach (ENTRY_SECTIONS as $section => $s) {
        $public[] = "/$l/{$s['news_path']}";
        foreach (published_entries('news', $section) as $e) {
            $public[] = entry_url($e, $l);
        }
    }
    $public[] = "/$l/apel/evenements";
    foreach (published_entries('event', 'apel') as $e) {
        $public[] = entry_url($e, $l);
    }
}

$admin = ['/admin', '/admin/pages', '/admin/actualites', '/admin/actualites/nouveau', '/admin/evenements', '/admin/evenements/nouveau',
    '/admin/medias', '/admin/utilisateurs', '/admin/utilisateurs/nouveau', '/admin/compte', '/admin/aide', '/admin/sauvegarde', '/admin/corbeille'];
foreach (array_keys(page_defs()) as $k) {
    $admin[] = "/admin/pages/$k";
    $admin[] = "/admin/pages/$k/historique";
}
foreach (q_all('SELECT id, type FROM entries WHERE deleted_at IS NULL') as $r) {
    $slug = $r['type'] === 'event' ? 'evenements' : 'actualites';
    $admin[] = "/admin/$slug/{$r['id']}";
    $admin[] = "/admin/$slug/{$r['id']}/historique";
}
foreach (q_all('SELECT id FROM users') as $r) {
    $admin[] = "/admin/utilisateurs/{$r['id']}";
}

// ── Capture ────────────────────────────────────────────────────────────────
if (is_dir($out)) {
    exec('rm -rf ' . escapeshellarg($out));
}
mkdir($out, 0755, true);
$count = 0;
foreach ($public as $p) {
    [$code, $html] = fetch($server . $p, null);
    if ($code !== 200) {
        fwrite(STDERR, "skip $p ($code)\n");
        continue;
    }
    save($out, $p, rewrite($html, $base, false));
    $count++;
}
foreach ($admin as $p) {
    [$code, $html] = fetch($server . $p, $sid);
    if ($code !== 200) {
        fwrite(STDERR, "skip $p ($code) — is the session id valid?\n");
        continue;
    }
    save($out, $p, rewrite($html, $base, true));
    $count++;
}
// Login page (captured without session) and media list used by the picker.
[, $html] = fetch($server . '/admin/connexion', null);
save($out, '/admin/connexion', rewrite($html, $base, true));
[, $json] = fetch($server . '/admin/api/medias?kind=', $sid);
save($out, '/admin/api/medias.json', str_replace('"/uploads/', '"' . $base . '/uploads/', str_replace('\/uploads\/', $base . '/uploads/', $json)));
[, $html] = fetch($server . '/fr/page-introuvable', null);
save($out, '/404.html', rewrite($html, $base, false));
file_put_contents("$out/index.html", '<!doctype html><meta charset="utf-8"><meta name="robots" content="noindex"><meta http-equiv="refresh" content="0; url=' . $base . '/fr/"><link rel="canonical" href="' . $base . '/fr/"><a href="' . $base . '/fr/">École de l’Assomption</a>');
touch("$out/.nojekyll");

// Static files
exec('cp -R ' . escapeshellarg("$www/assets") . ' ' . escapeshellarg("$out/assets"));
exec('cp -R ' . escapeshellarg("$www/uploads") . ' ' . escapeshellarg("$out/uploads"));
exec('rm -f ' . escapeshellarg("$out/uploads/.htaccess") . ' ' . escapeshellarg("$out/uploads/.gitignore"));
copy("$www/favicon.ico", "$out/favicon.ico");
copy(__DIR__ . '/demo.js', "$out/assets/demo.js");

echo "$count pages written to $out\n";
