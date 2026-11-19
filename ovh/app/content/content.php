<?php
declare(strict_types=1);

const LOCALES = ['fr', 'en'];

// ── Cleaning submitted data against the schema ─────────────────────────────

/**
 * Turn raw form input into clean data for the given fields.
 * Unknown keys are ignored; every value is typed and sanitized.
 * $errors collects human-readable problems keyed by field path.
 */
function clean_fields(array $fields, array $input, array &$errors, string $path = ''): array
{
    $out = [];
    foreach ($fields as $f) {
        $name = $f['name'];
        $raw = $input[$name] ?? null;
        $key = $path === '' ? $name : "$path.$name";
        if (!empty($f['loc'])) {
            $val = [];
            foreach (LOCALES as $l) {
                $val[$l] = clean_value($f, is_array($raw) ? ($raw[$l] ?? null) : null, $errors, "$key.$l");
            }
            if (!empty($f['required']) && is_blank($val['fr'])) {
                $errors[$key] = '« ' . $f['label'] . ' » doit être rempli (au moins en français).';
            }
        } else {
            $val = clean_value($f, $raw, $errors, $key);
            if (!empty($f['required']) && is_blank($val)) {
                $errors[$key] = '« ' . $f['label'] . ' » doit être rempli.';
            }
        }
        $out[$name] = $val;
    }
    return $out;
}

function is_blank($v): bool
{
    if (is_array($v)) {
        return count(array_filter($v, fn ($x) => !is_blank($x))) === 0;
    }
    return trim(strip_tags((string) $v)) === '';
}

function clean_value(array $f, $raw, array &$errors, string $key)
{
    switch ($f['type']) {
        case 'text':
        case 'tel':
            $v = str_val($raw);
            $v = preg_replace('/\s+/u', ' ', $v) ?? $v;
            return mb_substr($v, 0, $f['max'] ?? 500);
        case 'textarea':
        case 'lines':
            $v = str_replace("\r\n", "\n", is_scalar($raw) ? (string) $raw : '');
            $v = trim(preg_replace("/\n{3,}/", "\n\n", $v) ?? $v);
            return mb_substr($v, 0, $f['max'] ?? 10000);
        case 'rich':
            return sanitize_html(is_scalar($raw) ? (string) $raw : '');
        case 'email':
            $v = str_val($raw);
            if ($v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
                $errors[$key] = "« {$f['label']} » n'est pas une adresse e-mail valide.";
            }
            return $v;
        case 'url':
            $v = str_val($raw);
            $safe = safe_url($v);
            if ($v !== '' && $safe === '') {
                $errors[$key] = "« {$f['label']} » n'est pas une adresse web valide (elle doit commencer par https://).";
            }
            return $safe;
        case 'color':
            $v = str_val($raw);
            return preg_match('/^#[0-9a-fA-F]{6}$/', $v) ? strtoupper($v) : '';
        case 'toggle':
            return !empty($raw) && $raw !== '0';
        case 'select':
            $v = str_val($raw);
            return array_key_exists($v, $f['options']) ? $v : ($f['default'] ?? array_key_first($f['options']));
        case 'pagelink':
            $v = str_val($raw);
            return array_key_exists($v, linkable_pages()) ? $v : '';
        case 'date':
            $v = str_val($raw);
            if ($v === '') {
                return '';
            }
            $d = DateTime::createFromFormat('!Y-m-d', $v);
            if (!$d || $d->format('Y-m-d') !== $v) {
                $errors[$key] = "« {$f['label']} » n'est pas une date valide.";
                return '';
            }
            return $v;
        case 'datetime':
            $v = str_val($raw);
            if ($v === '') {
                return '';
            }
            $d = DateTime::createFromFormat('Y-m-d\TH:i', substr($v, 0, 16));
            if (!$d) {
                $errors[$key] = "« {$f['label']} » n'est pas une date valide.";
                return '';
            }
            return $d->format('Y-m-d\TH:i');
        case 'image':
        case 'file':
            $v = str_val($raw);
            $m = media($v);
            if (!$m || ($f['type'] === 'image' && $m['kind'] !== 'image')) {
                return '';
            }
            return $v;
        case 'gallery':
            $ids = is_array($raw) ? $raw : [];
            $ids = array_values(array_unique(array_filter(array_map('str_val', $ids), fn ($id) => ($m = media($id)) && $m['kind'] === 'image')));
            return array_slice($ids, 0, $f['max_items'] ?? 24);
        case 'table':
            $rows = [];
            if (is_array($raw)) {
                foreach ($raw as $r) {
                    if (!is_array($r)) {
                        continue;
                    }
                    $cells = array_map(fn ($c) => mb_substr(preg_replace('/\s+/u', ' ', str_val($c)) ?? '', 0, 500), array_values($r));
                    if (count(array_filter($cells, fn ($c) => $c !== '')) > 0) {
                        $rows[] = array_slice($cells, 0, 8);
                    }
                }
            }
            // Pad all rows to the same width.
            $w = $rows ? max(array_map('count', $rows)) : 0;
            return array_map(fn ($r) => array_pad($r, $w, ''), array_slice($rows, 0, 100));
        case 'repeater':
            $items = [];
            if (is_array($raw)) {
                foreach (array_values($raw) as $i => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $itemErrors = [];
                    $clean = clean_fields($f['fields'], $item, $itemErrors, "$key.$i");
                    // A block left completely empty is simply dropped, without complaining.
                    if (!is_blank(array_map(fn ($v) => is_bool($v) ? '' : $v, $clean))) {
                        $items[] = $clean;
                        $errors += $itemErrors;
                    }
                }
            }
            return array_slice($items, 0, 100);
    }
    return null;
}

// ── Reading content in templates ───────────────────────────────────────────

/**
 * Read-only view of stored content for one locale, with French fallback.
 *   $c->t('title')      plain text (escape with e())
 *   $c->html('body')    sanitized rich text, ready to print
 *   $c->lines('items')  list of non-empty lines
 *   $c->img('photo')    image URL or ''
 */
final class C
{
    public function __construct(public array $data, public string $locale)
    {
    }

    public function raw(string $k)
    {
        return $this->data[$k] ?? null;
    }

    public function t(string $k, string $fallback = ''): string
    {
        $v = $this->data[$k] ?? null;
        if (is_array($v)) {
            $s = trim((string) ($v[$this->locale] ?? ''));
            if ($s === '') {
                $s = trim((string) ($v['fr'] ?? ''));
            }
            return $s !== '' ? $s : $fallback;
        }
        $s = trim((string) ($v ?? ''));
        return $s !== '' ? $s : $fallback;
    }

    public function has(string $k): bool
    {
        return $this->t($k) !== '' || (is_array($this->data[$k] ?? null) && !isset($this->data[$k]['fr']) && count($this->data[$k]) > 0);
    }

    /** Multi-line text, escaped, with line breaks kept. */
    public function nl(string $k): string
    {
        return nl2br(e($this->t($k)), false);
    }

    public function html(string $k): string
    {
        $v = $this->data[$k] ?? null;
        $s = '';
        if (is_array($v)) {
            $s = (string) ($v[$this->locale] ?? '');
            if (is_blank($s)) {
                $s = (string) ($v['fr'] ?? '');
            }
        } elseif (is_string($v)) {
            $s = $v;
        }
        return is_blank($s) ? '' : rich_output($s);
    }

    public function lines(string $k): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", $this->t($k))), fn ($l) => $l !== ''));
    }

    public function bool(string $k): bool
    {
        return !empty($this->data[$k]);
    }

    public function img(string $k, bool $small = false): string
    {
        return media_url(media((string) ($this->data[$k] ?? '')), $small);
    }

    public function media(string $k): ?array
    {
        return media((string) ($this->data[$k] ?? ''));
    }

    public function gallery(string $k): array
    {
        $out = [];
        foreach ((array) ($this->data[$k] ?? []) as $id) {
            if ($m = media((string) $id)) {
                $out[] = $m;
            }
        }
        return $out;
    }

    /** @return C[] */
    public function rows(string $k): array
    {
        $items = $this->data[$k] ?? [];
        return is_array($items) ? array_map(fn ($r) => new C(is_array($r) ? $r : [], $this->locale), array_values($items)) : [];
    }

    /** Table rows for the current locale (falls back to French). */
    public function table(string $k): array
    {
        $v = $this->data[$k] ?? [];
        $rows = $v[$this->locale] ?? [];
        if (!$rows) {
            $rows = $v['fr'] ?? [];
        }
        return is_array($rows) ? $rows : [];
    }
}

function page_content(string $key, string $locale): C
{
    return new C(page_data($key), $locale);
}

// ── Translations of fixed interface text ───────────────────────────────────

function tr(string $key, ?string $locale = null): string
{
    static $msgs = [];
    $locale ??= $GLOBALS['locale'] ?? 'fr';
    if (!isset($msgs[$locale])) {
        $msgs[$locale] = require APP_DIR . "/content/lang/$locale.php";
    }
    return $msgs[$locale][$key] ?? $msgs['fr'][$key] ?? $key;
}

function locale(): string
{
    return $GLOBALS['locale'] ?? 'fr';
}

/** Public URL of a page path in the current (or given) locale. */
function site_url(string $path = '', ?string $locale = null): string
{
    $locale ??= locale();
    return '/' . $locale . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function entry_url(array $entry, ?string $locale = null): string
{
    $path = $entry['type'] === 'event' ? 'apel/evenements' : ENTRY_SECTIONS[$entry['section']]['news_path'];
    return site_url($path . '/' . $entry['slug'], $locale);
}
