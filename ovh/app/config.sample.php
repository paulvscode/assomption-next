<?php
/**
 * Copy this file to config.php and adjust the values.
 * config.php is ignored by git: never commit it.
 */
return [
    // 'production' hides error details; 'development' shows them.
    'env' => 'production',

    // Public address of the site, without a trailing slash.
    // Used for the sitemap, canonical links and links in the admin.
    'base_url' => 'https://www.ecole-assomption-lehavre.fr',

    // Where the SQLite database lives. Keep it OUTSIDE the web root (www/).
    'data_dir' => __DIR__ . '/../data',

    // Where uploaded images and documents are stored, and their public URL.
    'uploads_dir' => __DIR__ . '/../www/uploads',
    'uploads_url' => '/uploads',

    // Secret key that unlocks the one-time setup page (/admin/setup),
    // which creates the first administrator account. Put a long random
    // value here, e.g. the output of: php -r "echo bin2hex(random_bytes(16));"
    // The setup page turns itself off as soon as one account exists.
    'setup_key' => '',

    // Send the session cookie over HTTPS only. Leave true in production.
    'secure_cookies' => true,

    // Largest upload accepted, in megabytes (the PHP limit on the host also applies).
    'max_upload_mb' => 20,

    'timezone' => 'Europe/Paris',
];
