<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Avatars are uploaded as "SVG" that actually wrap a large base64 raster
 * (Figma export). That's ~1MB to download per avatar. This service extracts the
 * embedded raster once and caches a small JPEG thumbnail next to it, returning a
 * lightweight URL for the app to use. Falls back to the original on any failure.
 */
class AvatarThumbnailService
{
    private const SIZE = 256;
    private const QUALITY = 82;

    /**
     * Public URL of a small thumbnail for [$path] (generating it on first use).
     * Returns the original URL for non-SVG or when extraction isn't possible.
     */
    public function thumbUrlFor(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'svg') {
            return storage_public_url($path);
        }

        $thumbPath = $this->thumbPath($path);
        $disk = Storage::disk('public');

        if (! $disk->exists($thumbPath) && ! $this->generate($path, $thumbPath)) {
            return storage_public_url($path);
        }

        return storage_public_url($thumbPath);
    }

    private function thumbPath(string $path): string
    {
        $dir = trim(dirname($path), '/.');
        $name = pathinfo($path, PATHINFO_FILENAME);

        return ($dir === '' ? '' : $dir.'/').'thumbs/'.$name.'.jpg';
    }

    private function generate(string $svgPath, string $thumbPath): bool
    {
        if (! function_exists('imagecreatefromstring')) {
            return false;
        }

        try {
            $disk = Storage::disk('public');
            if (! $disk->exists($svgPath)) {
                return false;
            }

            $svg = $disk->get($svgPath);
            $matched = preg_match(
                '#data:image/(?:png|jpe?g|gif|webp);base64,([A-Za-z0-9+/=\s]+)#',
                (string) $svg,
                $m,
            );
            if (! $matched) {
                return false;
            }

            $raw = base64_decode(preg_replace('/\s+/', '', $m[1]), true);
            if ($raw === false) {
                return false;
            }

            $image = @imagecreatefromstring($raw);
            if ($image === false) {
                return false;
            }

            $scaled = imagescale($image, self::SIZE);
            imagedestroy($image);
            if ($scaled === false) {
                return false;
            }

            ob_start();
            imagejpeg($scaled, null, self::QUALITY);
            $jpeg = (string) ob_get_clean();
            imagedestroy($scaled);

            if ($jpeg === '') {
                return false;
            }

            $disk->put($thumbPath, $jpeg);

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
