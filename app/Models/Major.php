<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = ['title', 'singkatan'];

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'jurusan_id');
    }
}
