<?php
declare(strict_types=1);

/* Rendering helpers of the public site, shared with the admin preview. */


function output_page(string $html, int $code = 200): never
{
    http_response_code($code);
    send_security_headers();
    header('Content-Type: text/html; charset=utf-8');
    header(current_user() ? 'Cache-Control: private, no-store' : 'Cache-Control: public, max-age=60');
    echo $html;
    exit;
}

function layout(string $content, array $meta): string
{
    $settings = page_content('settings', locale());
    return render('site/layout', $meta + [
        'content' => $content,
        'settings' => $settings,
        'description' => $settings->t('description'),
        'altPath' => null,
        'editUrl' => null,
        'notice' => null,
    ]);
}

function render_page(string $key, array $extra = []): never
{
    $c = page_content($key, locale());
    $def = page_def($key);
    $content = render("site/pages/$key", ['c' => $c, 'settings' => page_content('settings', locale())]);
    $title = $key === 'landing' ? '' : strip_tags($c->t('title', $def['label']));
    $untranslated = locale() === 'en' && $key !== 'landing' && is_untranslated($c->data, page_fields($def));
    output_page(layout($content, $extra + [
        'notice' => $untranslated ? tr('notTranslated') : null,
        'noticeStyle' => 'soft',
        'title' => $title,
        'altPath' => $def['path'],
        'editUrl' => can_edit($def['owner']) ? "/admin/pages/$key" : null,
    ]));
}

function render_list(string $type, string $section): never
{
    $entries = published_entries($type, $section);
    $l = locale();
    if ($type === 'event') {
        $today = date('Y-m-d\T00:00');
        $upcoming = array_values(array_filter($entries, fn ($e) => (($e['data']['end'] ?? '') ?: $e['date']) >= $today));
        $past = array_reverse(array_values(array_filter($entries, fn ($e) => (($e['data']['end'] ?? '') ?: $e['date']) < $today)));
        $content = render('site/events', ['upcoming' => $upcoming, 'past' => $past]);
        $title = tr('allEvents');
        $altPath = 'apel/evenements';
    } else {
        $title = tr(['ecole' => 'schoolNewsTitle', 'apel' => 'apelNewsTitle', 'ogec' => 'ogecNewsTitle'][$section]);
        $content = render('site/news-list', ['entries' => $entries, 'title' => $title]);
        $altPath = ENTRY_SECTIONS[$section]['news_path'];
    }
    $create = $type === 'event' ? '/admin/evenements/nouveau' : "/admin/actualites/nouveau?section=$section";
    output_page(layout($content, [
        'title' => $title,
        'altPath' => $altPath,
        'editUrl' => can_edit($section) ? $create : null,
        'editLabel' => $type === 'event' ? 'Ajouter un événement' : 'Publier une actualité',
    ]));
}

function render_entry(string $type, string $section, string $slug, ?array $entry = null, ?string $notice = null): never
{
    $entry ??= find_entry_by_slug($type, $section, $slug, (bool) current_user());
    if (!$entry) {
        not_found(locale());
    }
    if ($entry['status'] !== 'published' && $notice === null) {
        $notice = tr('draftNotice');
    }
    $noticeStyle = null;
    if ($notice === null && locale() === 'en') {
        $fields = array_merge(...array_map(fn ($s) => $s['fields'], entry_fields($type)));
        if (is_untranslated($entry['data'], $fields)) {
            $notice = tr('notTranslated');
            $noticeStyle = 'soft';
        }
    }
    $c = new C($entry['data'], locale());
    $listPath = $type === 'event' ? 'apel/evenements' : ENTRY_SECTIONS[$section]['news_path'];
    $content = render('site/entry', ['c' => $c, 'entry' => $entry, 'listUrl' => site_url($listPath)]);
    $settings = page_content('settings', locale());
    output_page(layout($content, [
        'title' => $c->t('title'),
        'description' => $c->t('excerpt') ?: $settings->t('description'),
        'ogImage' => $c->img('coverImage'),
        'altPath' => $listPath . '/' . $entry['slug'],
        'notice' => $notice,
        'noticeStyle' => $noticeStyle,
        'editUrl' => can_edit($section) && !empty($entry['id'])
            ? '/admin/' . ($type === 'event' ? 'evenements' : 'actualites') . '/' . $entry['id']
            : null,
    ]));
}

function not_found(string $loc): never
{
    $GLOBALS['locale'] = $loc;
    output_page(layout(render('site/404'), ['title' => tr('notFoundTitle'), 'robots' => 'noindex']), 404);
}

function send_sitemap(): never
{
    $urls = [];
    foreach (LOCALES as $l) {
        foreach (page_defs() as $def) {
            if ($def['path'] !== null) {
                $urls[] = site_url($def['path'], $l);
            }
        }
        foreach (ENTRY_SECTIONS as $section => $s) {
            $urls[] = site_url($s['news_path'], $l);
            foreach (published_entries('news', $section) as $e) {
                $urls[] = entry_url($e, $l);
            }
        }
        $urls[] = site_url('apel/evenements', $l);
        foreach (published_entries('event', 'apel') as $e) {
            $urls[] = entry_url($e, $l);
        }
    }
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach (array_unique($urls) as $u) {
        echo '  <url><loc>' . e(base_url($u)) . "</loc></url>\n";
    }
    echo "</urlset>\n";
    exit;
}

// ── Small template helpers ─────────────────────────────────────────────────

function page_header(string $title, string $sub = '', string $width = 'max-w-4xl', string $size = 'text-3xl sm:text-4xl'): string
{
    $html = '<div class="border-b border-border bg-surface py-12"><div class="mx-auto ' . $width . ' px-4 sm:px-6 lg:px-8">'
        . '<h1 class="font-display ' . $size . ' font-bold text-primary">' . e($title) . '</h1>';
    if ($sub !== '') {
        $html .= '<p class="mt-3 font-display text-lg italic text-muted">' . e($sub) . '</p>';
    }
    return $html . '</div></div>';
}

function prose(string $html, string $class = ''): string
{
    return $html === '' ? '' : '<div class="prose ' . $class . '">' . $html . '</div>';
}

function bullets(array $items, string $class = 'space-y-3', string $text = 'text-sm leading-relaxed'): string
{
    if (!$items) {
        return '';
    }
    $html = '<ul class="' . $class . '">';
    foreach ($items as $i) {
        $html .= '<li class="flex items-start gap-3 ' . $text . ' text-ink"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-primary/60" aria-hidden="true"></span><span>' . e($i) . '</span></li>';
    }
    return $html . '</ul>';
}

/** A row "↓ Document name …… Télécharger (PDF, 230 Ko)" or "À venir". */
function doc_row(string $label, ?array $m, string $bg = 'bg-surface'): string
{
    if ($m) {
        $ext = strtoupper(pathinfo($m['path'], PATHINFO_EXTENSION));
        return '<li><a href="' . e(media_url($m)) . '" target="_blank" rel="noopener" class="group flex items-center gap-3 rounded-xl border border-border ' . $bg . ' px-5 py-4 text-sm transition-colors hover:border-primary/40 hover:bg-white">'
            . '<span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary text-[10px] font-bold text-white">' . e($ext) . '</span>'
            . '<span class="font-medium text-ink group-hover:underline">' . e($label) . '</span>'
            . '<span class="ml-auto shrink-0 text-xs text-muted">' . e(tr('download')) . ' · ' . e(human_size((int) $m['size'])) . '</span></a></li>';
    }
    return '<li class="flex items-center gap-3 rounded-xl border border-dashed border-border ' . $bg . ' px-5 py-4 text-sm">'
        . '<span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-border text-muted" aria-hidden="true">↓</span>'
        . '<span class="font-medium text-ink">' . e($label) . '</span>'
        . '<span class="ml-auto shrink-0 text-xs text-muted">' . e(tr('comingSoon')) . '</span></li>';
}

/** @param C[] $rows */
function docs_list(array $rows, string $class = 'space-y-3', string $bg = 'bg-surface'): string
{
    $html = '';
    foreach ($rows as $r) {
        if ($r->t('label') !== '' || $r->media('file')) {
            $html .= doc_row($r->t('label', $r->media('file')['original_name'] ?? ''), $r->media('file'), $bg);
        }
    }
    return $html === '' ? '' : '<ul class="' . $class . '">' . $html . '</ul>';
}

function data_table(array $rows): string
{
    if (!$rows) {
        return '';
    }
    $head = array_shift($rows);
    $html = '<div class="overflow-x-auto rounded-2xl border border-border"><table class="w-full text-sm"><thead><tr class="bg-surface">';
    foreach ($head as $h) {
        $html .= '<th scope="col" class="px-5 py-3 text-left font-semibold text-primary">' . e($h) . '</th>';
    }
    $html .= '</tr></thead><tbody class="divide-y divide-border">';
    foreach ($rows as $i => $r) {
        $html .= '<tr class="' . ($i % 2 ? 'bg-surface/50' : 'bg-white') . '">';
        foreach ($r as $j => $cell) {
            $html .= $j === 0
                ? '<th scope="row" class="px-5 py-3 text-left font-semibold text-primary">' . e($cell) . '</th>'
                : '<td class="px-5 py-3 text-ink">' . e($cell) . '</td>';
        }
        $html .= '</tr>';
    }
    return $html . '</tbody></table></div>';
}

function img_tag(string $url, string $alt, string $class, bool $lazy = true): string
{
    return $url === '' ? '' : '<img src="' . e($url) . '" alt="' . e($alt) . '" class="' . $class . '"' . ($lazy ? ' loading="lazy"' : '') . ' decoding="async">';
}

function media_alt(?array $m, string $fallback = ''): string
{
    return trim((string) ($m['alt'] ?? '')) ?: $fallback;
}

// ── Dates written out in full ──────────────────────────────────────────────

function long_date(string $date, string $locale): string
{
    $ts = strtotime($date);
    if ($ts === false) {
        return '';
    }
    $daysFr = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    $daysEn = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $w = (int) date('w', $ts);
    return ($locale === 'en' ? $daysEn[$w] : $daysFr[$w]) . ' ' . format_date(date('Y-m-d', $ts), $locale);
}

/** "Du vendredi 18 octobre 2024 au lundi 4 novembre 2024" / "Friday 18 October 2024 to Monday 4 November 2024" */
function period_text(string $start, string $end, string $locale): string
{
    if ($end === '' || $end === $start) {
        $s = long_date($start, $locale);
        return $locale === 'en' ? $s : mb_strtoupper(mb_substr($s, 0, 1)) . mb_substr($s, 1);
    }
    return $locale === 'en'
        ? long_date($start, 'en') . ' to ' . long_date($end, 'en')
        : 'Du ' . long_date($start, 'fr') . ' au ' . long_date($end, 'fr');
}

/** The next (or current) date of the holiday calendar, or null. */
function next_calendar_date(): ?array
{
    $today = date('Y-m-d');
    $periods = page_data('schoolBreaks')['periods'] ?? [];
    usort($periods, fn ($a, $b) => strcmp((string) ($a['start'] ?? ''), (string) ($b['start'] ?? '')));
    foreach ($periods as $p) {
        $start = (string) ($p['start'] ?? '');
        $end = (string) (($p['end'] ?? '') ?: $start);
        if ($start !== '' && $end >= $today) {
            return $p + ['ongoing' => $start <= $today, 'days' => (int) round((strtotime($start) - strtotime($today)) / 86400)];
        }
    }
    return null;
}

/** Small line icons for the public site. */
function site_icon(string $name, string $class = 'h-6 w-6'): string
{
    $p = [
        'enrol' => '<path d="M9 4h6a1 1 0 0 1 1 1v1H8V5a1 1 0 0 1 1-1z"/><path d="M8 5H6a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2"/><path d="m9 14 2 2 4-4"/>',
        'meal' => '<path d="M7 3v8a2 2 0 0 0 2 2v8M11 3v8a2 2 0 0 1-2 2M9 3v6"/><path d="M17 21V3c-2 1-3 4-3 8h3"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'bag' => '<path d="M6 8h12a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2z"/><path d="M9 8V6a3 3 0 0 1 6 0v2M4 13h16M10 13v2h4v-2"/>',
        'euro' => '<path d="M17 6.5A6.5 6.5 0 1 0 17 17.5"/><path d="M4 10h9M4 14h9"/>',
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
        'doc' => '<path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"/><path d="M14 3v5h5M9 13h6M9 17h6"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/>',
        'pin' => '<path d="M12 21s-7-6.2-7-12a7 7 0 0 1 14 0c0 5.8-7 12-7 12z"/><circle cx="12" cy="9" r="2.5"/>',
        'award' => '<circle cx="12" cy="9" r="6"/><path d="m8.5 14-1.5 7 5-3 5 3-1.5-7"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
    ];
    return '<svg class="' . $class . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($p[$name] ?? $p['info']) . '</svg>';
}

/** "L'essentiel" box shown at the top of a page when a summary is filled in. */
function summary_box(C $c): string
{
    $items = $c->lines('summary');
    if (!$items) {
        return '';
    }
    return '<aside class="mb-10 rounded-2xl border-l-4 border-accent bg-surface p-6" aria-label="' . e(tr('inShort')) . '">'
        . '<p class="mb-3 text-xs font-bold uppercase tracking-widest text-primary">' . e(tr('inShort')) . '</p>'
        . bullets($items, 'space-y-2', 'text-base font-medium') . '</aside>';
}

/**
 * True when the English page shows French text because the main fields
 * (title / rich text) have no English version yet.
 */
function is_untranslated(array $data, array $fields): bool
{
    foreach ($fields as $f) {
        if (empty($f['loc']) || !in_array($f['type'], ['rich', 'text', 'textarea'], true)) {
            continue;
        }
        if (!in_array($f['name'], ['title', 'body', 'intro', 'text', 'statement'], true)) {
            continue;
        }
        $v = $data[$f['name']] ?? null;
        if (is_array($v) && !is_blank($v['fr'] ?? '') && is_blank($v['en'] ?? '')) {
            return true;
        }
    }
    return false;
}

function short_month(int $ts, string $locale): string
{
    $fr = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
    $en = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $m = (int) date('n', $ts) - 1;
    return $locale === 'en' ? $en[$m] : $fr[$m];
}
