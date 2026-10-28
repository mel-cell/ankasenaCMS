<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasSlug;

    protected $fillable = ['title', 'slug', 'jenis', 'deskripsi', 'ikon_path', 'konten', 'published'];

    protected function casts(): array
    {
        return ['published' => 'boolean'];
    }

    public function scopePublished($q)
    {
        return $q->where('published', true);
    }
}
