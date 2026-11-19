<?php
declare(strict_types=1);

const ROLES = [
    'admin' => [
        'label' => 'Administrateur',
        'help' => "Peut tout modifier, y compris les réglages du site et les comptes des autres personnes.",
    ],
    'ecole' => [
        'label' => 'École',
        'help' => "Peut modifier les pages de l'école, la page d'accueil et publier les actualités de l'école.",
    ],
    'apel' => [
        'label' => 'APEL',
        'help' => "Peut modifier la page de l'APEL et publier ses actualités et ses événements.",
    ],
    'ogec' => [
        'label' => 'OGEC',
        'help' => "Peut modifier la page de l'OGEC et publier ses actualités.",
    ],
];

const SESSION_IDLE_SECONDS = 4 * 3600;   // logged out after 4 hours without activity
const SESSION_MAX_SECONDS = 12 * 3600;   // and in any case after 12 hours
const LOGIN_MAX_ATTEMPTS = 5;
const LOGIN_WINDOW_SECONDS = 15 * 60;

function session_start_secure(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (bool) config('secure_cookies') && is_https();
    session_name('assomption_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.gc_maxlifetime', (string) SESSION_MAX_SECONDS);
    // Store sessions next to the database rather than in the shared /tmp.
    $dir = rtrim((string) config('data_dir'), '/') . '/sessions';
    if (!is_dir($dir)) {
        @mkdir($dir, 0700, true);
    }
    if (is_dir($dir) && is_writable($dir)) {
        session_save_path($dir);
    }
    session_start();
}

/** Only start a session when the visitor already has a session cookie (keeps public pages cookie-free). */
function session_resume_if_any(): void
{
    if (isset($_COOKIE['assomption_session'])) {
        session_start_secure();
    }
}

function current_user(): ?array
{
    static $user = false;
    if ($user !== false) {
        return $user;
    }
    $user = null;
    if (session_status() !== PHP_SESSION_ACTIVE || empty($_SESSION['uid'])) {
        return null;
    }
    $t = time();
    if (($t - (int) ($_SESSION['last_seen'] ?? 0)) > SESSION_IDLE_SECONDS
        || ($t - (int) ($_SESSION['started'] ?? 0)) > SESSION_MAX_SECONDS) {
        logout();
        return null;
    }
    $_SESSION['last_seen'] = $t;
    $row = q_one('SELECT * FROM users WHERE id = ? AND active = 1', [(int) $_SESSION['uid']]);
    if (!$row) {
        logout();
        return null;
    }
    // A password change on another device invalidates this session.
    if (!hash_equals((string) ($_SESSION['pwd'] ?? ''), substr(hash('sha256', $row['password_hash']), 0, 16))) {
        logout();
        return null;
    }
    $user = $row;
    return $user;
}

function login_user(array $user): void
{
    session_start_secure();
    session_regenerate_id(true);
    $_SESSION = [
        'uid' => (int) $user['id'],
        'started' => time(),
        'last_seen' => time(),
        'pwd' => substr(hash('sha256', $user['password_hash']), 0, 16),
        'csrf' => bin2hex(random_bytes(32)),
    ];
    q('UPDATE users SET last_login_at = ? WHERE id = ?', [now(), $user['id']]);
}

function refresh_session_password(string $hash): void
{
    $_SESSION['pwd'] = substr(hash('sha256', $hash), 0, 16);
}

function logout(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = [];
        $p = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $p['path'],
            'secure' => $p['secure'],
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_destroy();
    }
}

/**
 * Check credentials with brute-force protection.
 * Returns the user row, or a string error code: 'locked' | 'invalid'.
 */
function attempt_login(string $email, string $password)
{
    $ip = client_ip();
    $since = time() - LOGIN_WINDOW_SECONDS;
    q('DELETE FROM login_attempts WHERE at < ?', [$since]);
    $byIp = (int) q_one('SELECT COUNT(*) AS n FROM login_attempts WHERE ip = ? AND at >= ?', [$ip, $since])['n'];
    $byEmail = (int) q_one('SELECT COUNT(*) AS n FROM login_attempts WHERE email = ? AND at >= ?', [mb_strtolower($email), $since])['n'];
    if ($byIp >= LOGIN_MAX_ATTEMPTS * 4 || $byEmail >= LOGIN_MAX_ATTEMPTS) {
        return 'locked';
    }
    $user = q_one('SELECT * FROM users WHERE email = ? AND active = 1', [$email]);
    // Always run a hash check so response time doesn't reveal whether the account exists.
    $hash = $user['password_hash'] ?? password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
    $ok = password_verify($password, $hash);
    if (!$user || !$ok) {
        q('INSERT INTO login_attempts (ip, email, at) VALUES (?, ?, ?)', [$ip, mb_strtolower($email), time()]);
        return 'invalid';
    }
    q('DELETE FROM login_attempts WHERE email = ?', [mb_strtolower($email)]);
    if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
        $user['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        q('UPDATE users SET password_hash = ? WHERE id = ?', [$user['password_hash'], $user['id']]);
    }
    return $user;
}

/** Returns an error message, or null when the password is acceptable. */
function password_problem(string $password, string $email = ''): ?string
{
    if (mb_strlen($password) < 10) {
        return 'Le mot de passe doit contenir au moins 10 caractères.';
    }
    $common = ['motdepasse', 'password', 'azertyuiop', '1234567890', 'assomption', 'ecole', 'lehavre', 'apel', 'ogec', 'qwertyuiop'];
    $lower = mb_strtolower($password);
    foreach ($common as $c) {
        if (str_contains($lower, $c) && mb_strlen($lower) < mb_strlen($c) + 6) {
            return 'Ce mot de passe est trop facile à deviner. Ajoutez quelques mots ou chiffres.';
        }
    }
    if ($email !== '' && str_contains($lower, mb_strtolower(explode('@', $email)[0]))) {
        return "Le mot de passe ne doit pas contenir votre adresse e-mail.";
    }
    return null;
}

/** A readable temporary password, e.g. "cerise-tigre-4821". */
function generate_temp_password(): string
{
    $words = ['pomme', 'cerise', 'nuage', 'soleil', 'marin', 'phare', 'voile', 'tigre', 'castor', 'prune', 'olive', 'plume', 'cactus', 'koala', 'galet', 'saule', 'menthe', 'violon'];
    return $words[random_int(0, count($words) - 1)] . '-' . $words[random_int(0, count($words) - 1)]
        . '-' . random_int(1000, 9999);
}

// ── Permissions ────────────────────────────────────────────────────────────

function is_admin(?array $user = null): bool
{
    $user ??= current_user();
    return $user && $user['role'] === 'admin';
}

/** Can the user edit content that belongs to $owner ('ecole' | 'apel' | 'ogec' | 'admin')? */
function can_edit(string $owner, ?array $user = null): bool
{
    $user ??= current_user();
    if (!$user) {
        return false;
    }
    return $user['role'] === 'admin' || $user['role'] === $owner;
}

// ── CSRF ───────────────────────────────────────────────────────────────────

function csrf_token(): string
{
    session_start_secure();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $sent = (string) ($_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    $expected = (string) ($_SESSION['csrf'] ?? '');
    if ($expected === '' || !hash_equals($expected, $sent)) {
        http_response_code(419);
        echo render('admin/expired');
        exit;
    }
}

// ── Flash messages ─────────────────────────────────────────────────────────

function flash(string $type, string $message, ?array $action = null): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message, 'action' => $action];
}

function take_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}
