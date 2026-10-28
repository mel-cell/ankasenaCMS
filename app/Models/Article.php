<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasSlug;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'cover_path', 'kategori', 'konten', 'penulis',
        'date', 'unggulan', 'meta_title', 'meta_description', 'published',
    ];

    protected function casts(): array
    {
        return ['date' => 'date', 'unggulan' => 'boolean', 'published' => 'boolean'];
    }

    public function scopePublished($q)
    {
        return $q->where('published', true);
    }
}
