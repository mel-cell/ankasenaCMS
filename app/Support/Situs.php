<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Akses settings global (key 'situs') + helper URL gambar
 * (path legacy 'img/...' dari aset Statamic lama vs upload baru 'storage/...').
 */
class Situs
{
    private static ?array $cache = null;

    public static function get(?string $key = null, $default = null)
    {
        self::$cache ??= Setting::group('situs');

        if ($key === null) {
            return self::$cache;
        }

        return self::$cache[$key] ?? $default;
    }

    public static function img(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        // Upload baru (FileUpload disk public, directory uploads/) → /storage/uploads/...
        if (str_starts_with($path, 'uploads/')) {
            return asset('storage/'.$path);
        }

        // Legacy asset Statamic: root container di public/assets.
        return asset('assets/'.$path);
    }
}
