<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skema konten pasca-Statamic. Tabel `users` bawaan Laravel sudah ada dari
 * awal → cukup ditambah kolom (role, google, profil siswa), bukan dibuat ulang.
 * Kolom 1:1 dengan blueprint lama (resources/blueprints/**).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('singkatan')->nullable();
            $t->timestamps();
        });

        Schema::table('users', function (Blueprint $t) {
            $t->string('password')->nullable()->change(); // login Google tanpa password lokal
            $t->string('role', 20)->default('user');      // superadmin|osis|mpk|user
            $t->string('google_id')->nullable()->unique();
            $t->string('avatar_url')->nullable();
            $t->string('nama_lengkap')->nullable();
            $t->string('kelas')->nullable();
            $t->foreignId('jurusan_id')->nullable()->constrained('majors')->nullOnDelete();
        });

        Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->string('cover_path')->nullable();
            $t->string('kategori')->nullable();
            $t->text('konten')->nullable(); // markdown
            $t->string('penulis')->nullable();
            $t->date('date')->nullable();
            $t->boolean('unggulan')->default(false);
            $t->string('meta_title')->nullable();
            $t->string('meta_description')->nullable();
            $t->boolean('published')->default(true);
            $t->timestamps();
        });

        Schema::create('officials', function (Blueprint $t) {
            $t->id();
            $t->string('nama');
            $t->string('jabatan');
            $t->string('divisi')->nullable();
            $t->string('kelas')->nullable();
            $t->string('angkatan')->nullable();
            $t->string('foto_path')->nullable();
            $t->text('bio')->nullable();
            $t->string('instagram')->nullable();
            $t->integer('urutan')->default(100);
            $t->string('periode')->default('2025/2026');
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });

        Schema::create('divisions', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('jenis')->default('Sekbid'); // Sekbid|Divisi
            $t->string('deskripsi', 200)->nullable();
            $t->string('ikon_path')->nullable();
            $t->text('konten')->nullable(); // markdown
            $t->boolean('published')->default(true);
            $t->timestamps();
        });

        Schema::create('elections', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('tipe', 10)->default('osis'); // osis|mpk
            $t->dateTime('mulai');
            $t->dateTime('selesai');
            $t->string('banner_path')->nullable();
            $t->text('keterangan')->nullable();
            $t->boolean('published')->default(true);
            $t->timestamps();
        });

        Schema::create('candidates', function (Blueprint $t) {
            $t->id();
            $t->string('title'); // nama
            $t->string('kelas');
            $t->foreignId('jurusan_id')->constrained('majors')->nullOnDelete();
            $t->integer('no_urut')->default(1);
            $t->string('foto_path')->nullable();
            $t->text('visi')->nullable();
            $t->text('misi')->nullable(); // satu poin per baris
            $t->foreignId('election_id')->constrained('elections')->cascadeOnDelete();
            $t->text('content')->nullable(); // profil lengkap, markdown
            $t->boolean('published')->default(true);
            $t->timestamps();
        });

        Schema::create('suggestions', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->nullable();
            $t->text('pesan');
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->json('value')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['settings', 'suggestions', 'candidates', 'elections', 'divisions', 'officials', 'articles'] as $t) {
            Schema::dropIfExists($t);
        }
        Schema::table('users', function (Blueprint $t) {
            $t->dropConstrainedForeignId('jurusan_id');
            foreach (['role', 'google_id', 'avatar_url', 'nama_lengkap', 'kelas'] as $c) {
                if (Schema::hasColumn('users', $c)) {
                    $t->dropColumn($c);
                }
            }
        });
        Schema::dropIfExists('majors');
    }
};
