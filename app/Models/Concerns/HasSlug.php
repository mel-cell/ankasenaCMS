<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            $base = Str::slug($model->title ?? $model->nama) ?: uniqid('item');
            $slug = $base;
            $i = 2;
            while (static::where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i++;
            }
            $model->slug = $slug;
        });
    }
}
