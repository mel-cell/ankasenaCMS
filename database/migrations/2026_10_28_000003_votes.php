<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel votes: sisa era Statamic. Buat hanya jika belum ada (id string —
// pemilu_id/user_id/kandidat_id simpan angka sebagai teks, ikut desain lama).
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('votes')) {
            return;
        }

        Schema::create('votes', function (Blueprint $t) {
            $t->id();
            $t->string('pemilu_id');
            $t->string('user_id');
            $t->string('kandidat_id');
            $t->timestamps();
            $t->unique(['pemilu_id', 'user_id']); // 1 akun 1 suara per pemilu
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
