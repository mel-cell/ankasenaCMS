<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $fillable = [
        'title', 'tipe', 'mulai', 'selesai', 'banner_path', 'keterangan', 'published',
    ];

    protected function casts(): array
    {
        return ['mulai' => 'datetime', 'selesai' => 'datetime', 'published' => 'boolean'];
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'pemilu_id');
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function status(): string
    {
        $now = Carbon::now();

        return $now->lt($this->mulai) ? 'belum' : ($now->gt($this->selesai) ? 'selesai' : 'aktif');
    }
}
