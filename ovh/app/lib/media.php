<?php
declare(strict_types=1);

const IMAGE_TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
const DOCUMENT_TYPES = [
    'application/pdf' => 'pdf',
    'application/msword' => 'doc',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    'application/vnd.ms-excel' => 'xls',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
    'application/vnd.oasis.opendocument.text' => 'odt',
    'application/vnd.oasis.opendocument.spreadsheet' => 'ods',
];
const IMAGE_MAX_SIDE = 2000;
const IMAGE_SMALL_SIDE = 720;

final class UploadError extends RuntimeException
{
}

/**
 * Validate and store one uploaded file. Images are re-encoded (which strips
 * GPS/EXIF data and any hidden payload) and resized; documents are checked
 * by content, not by name. Returns the media row.
 */
function store_upload(array $file, string $expect, ?int $userId): array
{
    $err = $file['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
        throw new UploadError('Ce fichier est trop lourd. Taille maximale : ' . max_upload_mb() . ' Mo.');
    }
    if ($err !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'] ?? '')) {
        throw new UploadError("Le fichier n'a pas pu être envoyé. Réessayez.");
    }
    $size = (int) filesize($file['tmp_name']);
    if ($size > max_upload_mb() * 1024 * 1024) {
        throw new UploadError('Ce fichier est trop lourd. Taille maximale : ' . max_upload_mb() . ' Mo.');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']) ?: '';
    $original = clean_filename((string) ($file['name'] ?? 'fichier'));

    $isImage = isset(IMAGE_TYPES[$mime]);
    $isDoc = isset(DOCUMENT_TYPES[$mime]);
    if ($expect === 'image' && !$isImage) {
        throw new UploadError("Ce fichier n'est pas une image. Formats acceptés : JPG, PNG, WebP, GIF.");
    }
    if ($expect === 'file' && !$isImage && !$isDoc) {
        throw new UploadError('Format non accepté. Formats acceptés : PDF, Word, Excel, OpenOffice, JPG, PNG.');
    }

    $id = bin2hex(random_bytes(8));
    $sub = date('Y/m');
    $dir = rtrim((string) config('uploads_dir'), '/') . '/' . $sub;
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException("Cannot create upload directory $dir");
    }
    $base = slugify(pathinfo($original, PATHINFO_FILENAME));
    $width = $height = null;
    $pathSmall = null;

    if ($isImage) {
        [$path, $width, $height, $mime, $pathSmall] = process_image($file['tmp_name'], $mime, $dir, $sub, "$base-$id");
        $size = (int) filesize(rtrim((string) config('uploads_dir'), '/') . '/' . $path);
    } else {
        $ext = DOCUMENT_TYPES[$mime];
        $path = "$sub/$base-$id.$ext";
        if (!move_uploaded_file($file['tmp_name'], "$dir/$base-$id.$ext")) {
            throw new RuntimeException('move_uploaded_file failed');
        }
        @chmod("$dir/$base-$id.$ext", 0644);
    }

    $row = [
        'id' => $id,
        'kind' => $isImage ? 'image' : 'file',
        'path' => $path,
        'path_small' => $pathSmall,
        'original_name' => $original,
        'mime' => $mime,
        'size' => $size,
        'width' => $width,
        'height' => $height,
        'alt' => '',
        'created_at' => now(),
        'created_by' => $userId,
    ];
    q(
        'INSERT INTO media (id, kind, path, path_small, original_name, mime, size, width, height, alt, created_at, created_by)
         VALUES (:id, :kind, :path, :path_small, :original_name, :mime, :size, :width, :height, :alt, :created_at, :created_by)',
        $row
    );
    return $row;
}

function process_image(string $tmp, string $mime, string $dir, string $sub, string $name): array
{
    if (!function_exists('imagecreatefromstring')) {
        throw new RuntimeException('GD extension missing');
    }
    $info = @getimagesize($tmp);
    if (!$info || $info[0] < 1 || $info[1] < 1) {
        throw new UploadError("Cette image semble abîmée et n'a pas pu être lue.");
    }
    if ($info[0] * $info[1] > 50_000_000) {
        throw new UploadError('Cette image est trop grande (plus de 50 mégapixels).');
    }
    if ($mime === 'image/gif') {
        // Keep GIFs as they are (animations), after the checks above.
        $path = "$sub/$name.gif";
        move_uploaded_file($tmp, "$dir/$name.gif");
        @chmod("$dir/$name.gif", 0644);
        return [$path, $info[0], $info[1], $mime, null];
    }
    $src = @imagecreatefromstring((string) file_get_contents($tmp));
    if (!$src) {
        throw new UploadError("Cette image n'a pas pu être lue.");
    }
    // Phones store photos sideways and record the rotation in EXIF.
    if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
        $exif = @exif_read_data($tmp);
        $o = (int) ($exif['Orientation'] ?? 1);
        $angle = [3 => 180, 6 => -90, 8 => 90][$o] ?? 0;
        if ($angle) {
            $rot = imagerotate($src, $angle, 0);
            if ($rot) {
                imagedestroy($src);
                $src = $rot;
            }
        }
    }
    $hasAlpha = $mime === 'image/png' || $mime === 'image/webp';
    $ext = $hasAlpha ? ($mime === 'image/webp' ? 'webp' : 'png') : 'jpg';
    $outMime = $hasAlpha ? $mime : 'image/jpeg';

    [$w, $h] = save_resized($src, "$dir/$name.$ext", IMAGE_MAX_SIDE, $ext);
    save_resized($src, "$dir/$name-sm.$ext", IMAGE_SMALL_SIDE, $ext);
    imagedestroy($src);
    return ["$sub/$name.$ext", $w, $h, $outMime, "$sub/$name-sm.$ext"];
}

function save_resized($src, string $dest, int $maxSide, string $ext): array
{
    $w = imagesx($src);
    $h = imagesy($src);
    $scale = min(1, $maxSide / max($w, $h));
    $nw = max(1, (int) round($w * $scale));
    $nh = max(1, (int) round($h * $scale));
    $dst = imagecreatetruecolor($nw, $nh);
    if ($ext !== 'jpg') {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    } else {
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
    }
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
    $ok = match ($ext) {
        'png' => imagepng($dst, $dest, 7),
        'webp' => imagewebp($dst, $dest, 82),
        default => imagejpeg($dst, $dest, 82),
    };
    imagedestroy($dst);
    if (!$ok) {
        throw new RuntimeException("Could not write $dest");
    }
    @chmod($dest, 0644);
    return [$nw, $nh];
}

function clean_filename(string $name): string
{
    $name = basename(str_replace('\\', '/', $name));
    $name = preg_replace('/[\x00-\x1F\x7F]/u', '', $name) ?? 'fichier';
    return mb_substr($name, 0, 120) ?: 'fichier';
}

function max_upload_mb(): int
{
    $toMb = function (string $v): int {
        $n = (int) $v;
        return match (strtolower(substr(trim($v), -1))) {
            'g' => $n * 1024,
            'k' => max(1, intdiv($n, 1024)),
            'm' => $n,
            default => max(1, intdiv($n, 1024 * 1024)),
        };
    };
    $limits = [(int) config('max_upload_mb')];
    foreach (['upload_max_filesize', 'post_max_size'] as $k) {
        $v = ini_get($k);
        if ($v) {
            $limits[] = $toMb($v);
        }
    }
    return max(1, min($limits));
}

function media(string $id): ?array
{
    static $cache = [];
    if ($id === '' || !preg_match('/^[a-f0-9]{16}$/', $id)) {
        return null;
    }
    if (!array_key_exists($id, $cache)) {
        $cache[$id] = q_one('SELECT * FROM media WHERE id = ?', [$id]);
    }
    return $cache[$id];
}

function media_url(?array $m, bool $small = false): string
{
    if (!$m) {
        return '';
    }
    $path = $small && $m['path_small'] ? $m['path_small'] : $m['path'];
    return rtrim((string) config('uploads_url'), '/') . '/' . implode('/', array_map('rawurlencode', explode('/', $path)));
}

/** Pages and entries that still reference a media item. */
function media_usage(string $id): array
{
    $like = '%"' . $id . '"%';
    $uses = [];
    foreach (q_all('SELECT key FROM pages WHERE data LIKE ?', [$like]) as $r) {
        $def = page_def($r['key']);
        $uses[] = ['label' => $def['label'] ?? $r['key'], 'url' => '/admin/pages/' . $r['key']];
    }
    foreach (q_all('SELECT id, type, data FROM entries WHERE data LIKE ? AND deleted_at IS NULL', [$like]) as $r) {
        $d = json_decode($r['data'], true) ?: [];
        $uses[] = [
            'label' => ($r['type'] === 'event' ? 'Événement : ' : 'Actualité : ') . ($d['title']['fr'] ?? 'sans titre'),
            'url' => '/admin/' . ($r['type'] === 'event' ? 'evenements' : 'actualites') . '/' . $r['id'],
        ];
    }
    return $uses;
}

function delete_media(array $m): void
{
    $dir = rtrim((string) config('uploads_dir'), '/');
    foreach ([$m['path'], $m['path_small']] as $p) {
        if ($p && is_file("$dir/$p") && !str_contains($p, '..')) {
            @unlink("$dir/$p");
        }
    }
    q('DELETE FROM media WHERE id = ?', [$m['id']]);
}

function file_icon(string $mime): string
{
    return match (true) {
        $mime === 'application/pdf' => 'PDF',
        str_contains($mime, 'word') || str_contains($mime, 'opendocument.text') => 'DOC',
        str_contains($mime, 'sheet') || str_contains($mime, 'excel') => 'XLS',
        str_starts_with($mime, 'image/') => 'IMG',
        default => 'FICHIER',
    };
}
