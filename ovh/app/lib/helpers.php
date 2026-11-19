<?php
declare(strict_types=1);

/** Escape for HTML text and attributes. */
function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function base_url(string $path = ''): string
{
    $base = rtrim((string) config('base_url'), '/');
    if ($base === '') {
        $scheme = is_https() ? 'https' : 'http';
        $base = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    return $base . $path;
}

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
}

function request_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $path = rawurldecode($path);
    if ($path !== '/' && str_ends_with($path, '/')) {
        $path = rtrim($path, '/');
    }
    return $path;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function redirect(string $to, int $code = 303): never
{
    header('Location: ' . $to, true, $code);
    exit;
}

function json_response($data, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/** Render a PHP view file with variables; returns the HTML. */
function render(string $view, array $vars = []): string
{
    $file = APP_DIR . '/views/' . $view . '.php';
    if (!is_file($file)) {
        throw new RuntimeException("View not found: $view");
    }
    extract($vars, EXTR_SKIP);
    ob_start();
    try {
        include $file;
    } catch (Throwable $t) {
        ob_end_clean();
        throw $t;
    }
    return (string) ob_get_clean();
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

function str_val($v): string
{
    return is_scalar($v) ? trim((string) $v) : '';
}

/** "il y a 5 minutes", "hier à 14:32", "le 3 mars 2025" */
function human_date(?string $datetime): string
{
    if (!$datetime) {
        return '';
    }
    $ts = strtotime($datetime);
    if ($ts === false) {
        return '';
    }
    $diff = time() - $ts;
    if ($diff < 60) {
        return "à l'instant";
    }
    if ($diff < 3600) {
        $m = intdiv($diff, 60);
        return "il y a $m minute" . ($m > 1 ? 's' : '');
    }
    if (date('Y-m-d', $ts) === date('Y-m-d')) {
        return "aujourd'hui à " . date('H:i', $ts);
    }
    if (date('Y-m-d', $ts) === date('Y-m-d', strtotime('-1 day'))) {
        return 'hier à ' . date('H:i', $ts);
    }
    return 'le ' . format_date(date('Y-m-d', $ts), 'fr');
}

function format_date(?string $date, string $locale = 'fr', bool $withTime = false): string
{
    if (!$date) {
        return '';
    }
    $ts = strtotime($date);
    if ($ts === false) {
        return '';
    }
    $monthsFr = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $monthsEn = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $d = (int) date('j', $ts);
    $m = (int) date('n', $ts) - 1;
    $y = date('Y', $ts);
    $out = $locale === 'en'
        ? "$d {$monthsEn[$m]} $y"
        : ($d === 1 ? '1er' : (string) $d) . " {$monthsFr[$m]} $y";
    if ($withTime && date('H:i', $ts) !== '00:00') {
        $out .= $locale === 'en' ? ', ' . date('g:i a', $ts) : ' à ' . str_replace(':', 'h', date('H:i', $ts));
    }
    return $out;
}

function slugify(string $text): string
{
    $text = trim($text);
    if (function_exists('transliterator_transliterate')) {
        $text = (string) transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
    } else {
        $text = strtolower((string) iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text));
    }
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    $text = trim($text, '-');
    return substr($text, 0, 80) ?: 'article';
}

function human_size(int $bytes): string
{
    if ($bytes < 1024) {
        return $bytes . ' o';
    }
    if ($bytes < 1024 * 1024) {
        return round($bytes / 1024) . ' Ko';
    }
    return number_format($bytes / 1024 / 1024, 1, ',', ' ') . ' Mo';
}

function client_ip(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

/** Headers shared by every HTML response. */
function send_security_headers(bool $admin = false): void
{
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    $csp = [
        "default-src 'self'",
        "img-src 'self' data:",
        "style-src 'self' 'unsafe-inline'",
        "script-src 'self'",
        "font-src 'self'",
        "form-action 'self'",
        "base-uri 'self'",
        "object-src 'none'",
    ];
    if ($admin) {
        $csp[] = "frame-ancestors 'self'";
        $csp[] = "frame-src 'self'";
        header('X-Frame-Options: SAMEORIGIN');
        header('Cache-Control: no-store');
        header('X-Robots-Tag: noindex, nofollow');
    } else {
        $csp[] = "frame-ancestors 'self'";
        $csp[] = "frame-src https://www.openstreetmap.org";
        // The map looks up the address only after a click on "Afficher la carte".
        $csp[] = "connect-src 'self' https://nominatim.openstreetmap.org";
        header('X-Frame-Options: SAMEORIGIN');
    }
    if (is_https()) {
        header('Strict-Transport-Security: max-age=31536000');
    }
    header('Content-Security-Policy: ' . implode('; ', $csp));
}
