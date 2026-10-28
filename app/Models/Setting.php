<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return ['value' => 'json'];
    }

    /**
     * Baca group settings ('situs') sebagai array; cache singkat biar hemat query.
     */
    public static function group(string $key, array $default = []): array
    {
        return Cache::remember("settings.$key", 300, fn () => static::find($key)?->value ?? $default);
    }

    public static function put(string $key, array $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("settings.$key");
    }
}
