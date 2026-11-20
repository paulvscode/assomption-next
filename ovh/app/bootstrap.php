<?php
declare(strict_types=1);

define('APP_DIR', __DIR__);

$configFile = APP_DIR . '/config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Configuration manquante : copiez app/config.sample.php vers app/config.php.\n";
    exit;
}

$GLOBALS['config'] = array_merge([
    'env' => 'production',
    'base_url' => '',
    'data_dir' => APP_DIR . '/../data',
    'uploads_dir' => APP_DIR . '/../www/uploads',
    'uploads_url' => '/uploads',
    'setup_key' => '',
    'secure_cookies' => true,
    'max_upload_mb' => 20,
    'timezone' => 'Europe/Paris',
], require $configFile);

function config(string $key)
{
    return $GLOBALS['config'][$key] ?? null;
}

date_default_timezone_set(config('timezone'));
mb_internal_encoding('UTF-8');

if (config('env') === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', rtrim(config('data_dir'), '/') . '/php-errors.log');
}

set_exception_handler(function (Throwable $e) {
    error_log((string) $e);
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
    }
    if (config('env') === 'development') {
        echo '<pre style="white-space:pre-wrap">' . htmlspecialchars((string) $e) . '</pre>';
    } else {
        echo '<!doctype html><meta charset="utf-8"><title>Erreur</title>'
            . '<div style="font-family:system-ui;max-width:32rem;margin:15vh auto;padding:0 1rem;color:#3D3B42">'
            . '<h1>Une erreur est survenue</h1><p>Merci de réessayer dans quelques instants. '
            . 'Si le problème persiste, contactez la personne qui gère le site.</p></div>';
    }
    exit;
});

require APP_DIR . '/lib/helpers.php';
require APP_DIR . '/lib/db.php';
require APP_DIR . '/lib/auth.php';
require APP_DIR . '/lib/sanitize.php';
require APP_DIR . '/lib/media.php';
require APP_DIR . '/content/schema.php';
require APP_DIR . '/content/content.php';
