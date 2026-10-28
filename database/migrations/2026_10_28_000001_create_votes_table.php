<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Suara voting disimpan di DB (bukan flat-file) demi integritas:
        // unique(pemilu_id, user_id) = 1 akun 1 suara per pemilihan, dijamin database.
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->string('pemilu_id');   // entry id collection pemilu
            $table->string('user_id');     // id user Statamic (flat-file, tanpa FK)
            $table->string('kandidat_id'); // entry id collection kandidat
            $table->timestamps();

            $table->unique(['pemilu_id', 'user_id']);
            $table->index(['pemilu_id', 'kandidat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
