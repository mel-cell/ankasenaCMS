<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    protected $table = 'officials';

    protected $fillable = [
        'nama', 'jabatan', 'divisi', 'kelas', 'angkatan', 'foto_path', 'bio',
        'instagram', 'urutan', 'periode', 'aktif',
    ];

    protected function casts(): array
    {
        return ['aktif' => 'boolean', 'urutan' => 'integer'];
    }
}
