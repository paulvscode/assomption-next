<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo) {
        return $pdo;
    }
    $dir = rtrim((string) config('data_dir'), '/');
    if (!is_dir($dir) && !mkdir($dir, 0750, true) && !is_dir($dir)) {
        throw new RuntimeException("Cannot create data directory: $dir");
    }
    $pdo = new PDO('sqlite:' . $dir . '/site.sqlite', null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    // Shared hosting storage is not always safe for WAL, so keep the
    // default rollback journal and just wait a little on concurrent writes.
    $pdo->exec('PRAGMA busy_timeout = 5000');
    $pdo->exec('PRAGMA foreign_keys = ON');
    migrate($pdo);
    return $pdo;
}

function q(string $sql, array $params = []): PDOStatement
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function q_one(string $sql, array $params = []): ?array
{
    $row = q($sql, $params)->fetch();
    return $row === false ? null : $row;
}

function q_all(string $sql, array $params = []): array
{
    return q($sql, $params)->fetchAll();
}

function migrate(PDO $pdo): void
{
    $version = (int) $pdo->query('PRAGMA user_version')->fetchColumn();
    $migrations = [
        1 => function (PDO $pdo) {
            $pdo->exec(<<<SQL
                CREATE TABLE users (
                    id INTEGER PRIMARY KEY,
                    email TEXT NOT NULL UNIQUE COLLATE NOCASE,
                    name TEXT NOT NULL,
                    password_hash TEXT NOT NULL,
                    role TEXT NOT NULL,
                    active INTEGER NOT NULL DEFAULT 1,
                    must_change_password INTEGER NOT NULL DEFAULT 0,
                    created_at TEXT NOT NULL,
                    last_login_at TEXT
                );
                CREATE TABLE pages (
                    key TEXT PRIMARY KEY,
                    data TEXT NOT NULL,
                    updated_at TEXT,
                    updated_by INTEGER REFERENCES users(id) ON DELETE SET NULL
                );
                CREATE TABLE entries (
                    id INTEGER PRIMARY KEY,
                    type TEXT NOT NULL,
                    section TEXT NOT NULL,
                    slug TEXT NOT NULL,
                    status TEXT NOT NULL DEFAULT 'draft',
                    date TEXT NOT NULL,
                    data TEXT NOT NULL,
                    created_at TEXT NOT NULL,
                    updated_at TEXT NOT NULL,
                    updated_by INTEGER REFERENCES users(id) ON DELETE SET NULL,
                    deleted_at TEXT,
                    UNIQUE (type, section, slug)
                );
                CREATE INDEX entries_list ON entries (type, section, status, date);
                CREATE TABLE media (
                    id TEXT PRIMARY KEY,
                    kind TEXT NOT NULL,
                    path TEXT NOT NULL,
                    path_small TEXT,
                    original_name TEXT NOT NULL,
                    mime TEXT NOT NULL,
                    size INTEGER NOT NULL,
                    width INTEGER,
                    height INTEGER,
                    alt TEXT NOT NULL DEFAULT '',
                    created_at TEXT NOT NULL,
                    created_by INTEGER REFERENCES users(id) ON DELETE SET NULL
                );
                CREATE TABLE revisions (
                    id INTEGER PRIMARY KEY,
                    target TEXT NOT NULL,
                    data TEXT NOT NULL,
                    created_at TEXT NOT NULL,
                    created_by INTEGER REFERENCES users(id) ON DELETE SET NULL
                );
                CREATE INDEX revisions_target ON revisions (target, id);
                CREATE TABLE login_attempts (
                    id INTEGER PRIMARY KEY,
                    ip TEXT NOT NULL,
                    email TEXT NOT NULL,
                    at INTEGER NOT NULL
                );
                CREATE INDEX login_attempts_lookup ON login_attempts (ip, at);
                SQL);
            require_once APP_DIR . '/content/seed.php';
            seed_content($pdo);
        },
        2 => function (PDO $pdo) {
            require_once APP_DIR . '/content/seed_v2.php';
            seed_v2($pdo);
        },
    ];
    foreach ($migrations as $v => $fn) {
        if ($v > $version) {
            $pdo->beginTransaction();
            try {
                $fn($pdo);
                $pdo->exec('PRAGMA user_version = ' . (int) $v);
                $pdo->commit();
            } catch (Throwable $t) {
                $pdo->rollBack();
                throw $t;
            }
        }
    }
}

// ── Pages ──────────────────────────────────────────────────────────────────

function page_data(string $key): array
{
    static $cache = [];
    // Admin "preview" renders the public page with unsaved form data.
    if (isset($GLOBALS['page_override'][$key])) {
        return $GLOBALS['page_override'][$key];
    }
    if (!array_key_exists($key, $cache)) {
        $row = q_one('SELECT data FROM pages WHERE key = ?', [$key]);
        $cache[$key] = $row ? (json_decode($row['data'], true) ?: []) : [];
    }
    return $cache[$key];
}

function page_meta(string $key): ?array
{
    return q_one(
        'SELECT p.updated_at, u.name AS updated_by_name FROM pages p
         LEFT JOIN users u ON u.id = p.updated_by WHERE p.key = ?',
        [$key]
    );
}

function save_page(string $key, array $data, ?int $userId): void
{
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $old = q_one('SELECT data FROM pages WHERE key = ?', [$key]);
    if ($old && $old['data'] === $json) {
        return; // nothing changed, no new revision
    }
    db()->beginTransaction();
    if ($old) {
        add_revision("page:$key", $old['data'], $userId);
    }
    q(
        'INSERT INTO pages (key, data, updated_at, updated_by) VALUES (?, ?, ?, ?)
         ON CONFLICT(key) DO UPDATE SET data = excluded.data, updated_at = excluded.updated_at, updated_by = excluded.updated_by',
        [$key, $json, now(), $userId]
    );
    db()->commit();
}

function add_revision(string $target, string $data, ?int $userId): void
{
    q('INSERT INTO revisions (target, data, created_at, created_by) VALUES (?, ?, ?, ?)', [$target, $data, now(), $userId]);
    // Keep the 30 most recent versions of each item.
    q(
        'DELETE FROM revisions WHERE target = ? AND id NOT IN
         (SELECT id FROM revisions WHERE target = ? ORDER BY id DESC LIMIT 30)',
        [$target, $target]
    );
}

function revisions_for(string $target): array
{
    return q_all(
        'SELECT r.id, r.created_at, u.name AS user_name FROM revisions r
         LEFT JOIN users u ON u.id = r.created_by WHERE r.target = ? ORDER BY r.id DESC',
        [$target]
    );
}

// ── Entries (news & events) ────────────────────────────────────────────────

function entry_decode(?array $row): ?array
{
    if (!$row) {
        return null;
    }
    $row['data'] = json_decode($row['data'], true) ?: [];
    return $row;
}

function find_entry(int $id): ?array
{
    return entry_decode(q_one('SELECT * FROM entries WHERE id = ?', [$id]));
}

function find_entry_by_slug(string $type, string $section, string $slug, bool $includeDrafts): ?array
{
    $sql = 'SELECT * FROM entries WHERE type = ? AND section = ? AND slug = ? AND deleted_at IS NULL';
    if (!$includeDrafts) {
        $sql .= " AND status = 'published'";
    }
    return entry_decode(q_one($sql, [$type, $section, $slug]));
}

function published_entries(string $type, string $section, ?int $limit = null): array
{
    $order = $type === 'event' ? 'date ASC' : 'date DESC, id DESC';
    $sql = "SELECT * FROM entries WHERE type = ? AND section = ? AND status = 'published' AND deleted_at IS NULL ORDER BY $order";
    if ($limit) {
        $sql .= ' LIMIT ' . (int) $limit;
    }
    return array_map('entry_decode', q_all($sql, [$type, $section]));
}

function unique_slug(string $type, string $section, string $base, ?int $exceptId): string
{
    $slug = $base;
    $i = 2;
    while (q_one(
        'SELECT id FROM entries WHERE type = ? AND section = ? AND slug = ? AND id IS NOT ?',
        [$type, $section, $slug, $exceptId]
    )) {
        $slug = $base . '-' . $i++;
    }
    return $slug;
}
