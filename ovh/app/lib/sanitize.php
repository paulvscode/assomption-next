<?php
declare(strict_types=1);

/**
 * Allow-list HTML sanitizer for the rich-text editor.
 * Anything not listed here is unwrapped (tags) or dropped (attributes).
 */
function sanitize_html(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }
    $allowed = [
        'p' => [], 'br' => [], 'strong' => [], 'em' => [], 'u' => [],
        'h2' => [], 'h3' => [], 'ul' => [], 'ol' => [], 'li' => [],
        'blockquote' => [], 'a' => ['href'],
    ];
    $rename = ['b' => 'strong', 'i' => 'em', 'h1' => 'h2', 'h4' => 'h3', 'h5' => 'h3', 'h6' => 'h3', 'div' => 'p'];
    $drop = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'select', 'textarea', 'svg', 'math', 'template', 'noscript', 'head', 'title', 'meta', 'link'];

    $doc = new DOMDocument('1.0', 'UTF-8');
    $prev = libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="UTF-8"><body><div id="root">' . $html . '</div></body>', LIBXML_NONET | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    libxml_use_internal_errors($prev);
    $root = $doc->getElementById('root');
    if (!$root) {
        return '';
    }

    $walk = function (DOMNode $node) use (&$walk, $allowed, $rename, $drop, $doc) {
        for ($child = $node->firstChild; $child !== null; $child = $next) {
            $next = $child->nextSibling;
            if ($child instanceof DOMComment || $child instanceof DOMProcessingInstruction || $child instanceof DOMCdataSection) {
                $node->removeChild($child);
                continue;
            }
            if (!$child instanceof DOMElement) {
                continue;
            }
            $tag = strtolower($child->tagName);
            if (in_array($tag, $drop, true)) {
                $node->removeChild($child);
                continue;
            }
            if (isset($rename[$tag])) {
                $new = $doc->createElement($rename[$tag]);
                while ($child->firstChild) {
                    $new->appendChild($child->firstChild);
                }
                $node->replaceChild($new, $child);
                $child = $new;
                $tag = $rename[$tag];
            }
            if (!isset($allowed[$tag])) {
                // Unknown wrapper (span, font, table…): keep its content, drop the tag.
                $walk($child);
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }
            foreach (iterator_to_array($child->attributes ?? []) as $attr) {
                if (!in_array(strtolower($attr->name), $allowed[$tag], true)) {
                    $child->removeAttribute($attr->name);
                }
            }
            if ($tag === 'a') {
                $href = safe_url($child->getAttribute('href'));
                if ($href === '') {
                    $child->removeAttribute('href');
                } else {
                    $child->setAttribute('href', $href);
                }
            }
            $walk($child);
        }
    };
    $walk($root);

    $out = '';
    foreach ($root->childNodes as $n) {
        $out .= $doc->saveHTML($n);
    }
    // Remove empty paragraphs the editor tends to leave behind.
    $out = preg_replace('#<p>(\s|&nbsp;|<br>)*</p>#u', '', $out) ?? $out;
    return trim($out);
}

/** Accept http(s), mailto, tel and site-relative links; anything else becomes ''. */
function safe_url(string $url): string
{
    $url = trim($url);
    if ($url === '') {
        return '';
    }
    if (preg_match('#^(https?://|mailto:|tel:)#i', $url)) {
        return filter_var($url, FILTER_SANITIZE_URL) ?: '';
    }
    if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
        return $url;
    }
    if (str_starts_with($url, '#')) {
        return $url;
    }
    // "www.apel.fr" → "https://www.apel.fr"
    if (preg_match('#^[a-z0-9-]+(\.[a-z0-9-]+)+(/.*)?$#i', $url)) {
        return 'https://' . $url;
    }
    return '';
}

/** Rich text for display: external links open in a new tab. */
function rich_output(string $html): string
{
    return preg_replace_callback('#<a href="([^"]*)">#', function ($m) {
        $href = $m[1];
        $external = preg_match('#^https?://#i', html_entity_decode($href)) && !str_contains($href, (string) ($_SERVER['HTTP_HOST'] ?? '#'));
        return $external
            ? '<a href="' . $href . '" target="_blank" rel="noopener noreferrer">'
            : '<a href="' . $href . '">';
    }, $html) ?? $html;
}
