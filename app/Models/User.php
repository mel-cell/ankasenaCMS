<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    public const ROLES = ['superadmin', 'osis', 'mpk', 'user'];

    protected $fillable = [
        'name', 'email', 'password', 'role', 'google_id', 'avatar_url',
        'nama_lengkap', 'kelas', 'jurusan_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function jurusan()
    {
        return $this->belongsTo(Major::class);
    }

    public function isSuper(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'osis', 'mpk'], true);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
}
