<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'title', 'kelas', 'jurusan_id', 'no_urut', 'foto_path',
        'visi', 'misi', 'election_id', 'content', 'published',
    ];

    protected function casts(): array
    {
        return ['no_urut' => 'integer', 'published' => 'boolean'];
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function jurusan()
    {
        return $this->belongsTo(Major::class, 'jurusan_id');
    }
}
