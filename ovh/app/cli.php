<?php
declare(strict_types=1);

/*
 * Command-line helper (local machine, or OVH over SSH).
 *   php app/cli.php create-admin "Nom" email@exemple.fr
 *   php app/cli.php reset-password email@exemple.fr
 * A temporary password is printed; it must be changed at first login.
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require __DIR__ . '/bootstrap.php';

$cmd = $argv[1] ?? '';
if ($cmd === 'create-admin' && isset($argv[2], $argv[3])) {
    if (!filter_var($argv[3], FILTER_VALIDATE_EMAIL)) {
        fwrite(STDERR, "Adresse e-mail invalide.\n");
        exit(1);
    }
    $pwd = generate_temp_password();
    q('INSERT INTO users (email, name, password_hash, role, must_change_password, created_at) VALUES (?, ?, ?, ?, 1, ?)',
        [$argv[3], $argv[2], password_hash($pwd, PASSWORD_DEFAULT), 'admin', now()]);
    echo "Compte administrateur créé.\nIdentifiant : {$argv[3]}\nMot de passe temporaire : $pwd\n";
} elseif ($cmd === 'reset-password' && isset($argv[2])) {
    $pwd = generate_temp_password();
    $n = q('UPDATE users SET password_hash = ?, must_change_password = 1, active = 1 WHERE email = ?', [password_hash($pwd, PASSWORD_DEFAULT), $argv[2]])->rowCount();
    q('DELETE FROM login_attempts WHERE email = ?', [mb_strtolower($argv[2])]);
    echo $n ? "Mot de passe temporaire : $pwd\n" : "Aucun compte avec cette adresse.\n";
} else {
    echo "Usage:\n  php app/cli.php create-admin \"Nom\" email\n  php app/cli.php reset-password email\n";
}
