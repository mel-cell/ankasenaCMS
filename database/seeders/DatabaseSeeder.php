<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Candidate;
use App\Models\Division;
use App\Models\Election;
use App\Models\Major;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! User::where('role', 'superadmin')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@ankasena.local',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'ganti-saya')),
                'role' => 'superadmin',
            ]);
        }

        if (! Setting::find('situs')) {
            // Nilai awal = globals Statamic lama (content/globals/default/pengaturan.yaml).
            Setting::put('situs', [
                'nama_organisasi' => 'Ankasena',
                'sekolah' => 'SMK Negeri 6 Malang',
                'tagline' => 'Dapatkan pengalaman organisasi yang nyata, penuh kreativitas & inovasi',
                'deskripsi' => 'Apa itu Ankasena? Wadah siswa untuk belajar memimpin lewat karya dan kolaborasi.',
                'periode' => '2025/2026',
                'visi' => 'Ruang bagi siswa untuk berkreasi, berinovasi, dan belajar memimpin dari pengalaman nyata.',
                'misi' => [
                    ['poin' => 'Membuka ruang aspirasi siswa yang mudah diakses.'],
                    ['poin' => 'Menjalankan program yang relevan dan berkelanjutan.'],
                    ['poin' => 'Menjaga transparansi anggaran dan keputusan.'],
                ],
                'alamat' => 'Malang, Jawa Timur',
                'instagram' => 'osisankasena',
                'email' => 'osis@ankasena.local',
                'voting_open' => false,
                'twibon_aktif' => false,
                'pesan_twibon' => 'Twibon segera dibuka.',
                'deskripsi_osis' => 'Organ pelaksana program kerja siswa — dari bidang olahraga, seni, hingga litbang yang bergerak untuk sekolah.',
                'deskripsi_mpk' => 'Dewan perwakilan kelas yang menampung aspirasi, mengawasi kinerja OSIS, dan menjaga transparansi.',
                'statistik' => [
                    ['angka' => '2', 'label' => 'Lembaga', 'deskripsi' => 'OSIS sebagai penggerak program, MPK sebagai penyalur aspirasi.'],
                    ['angka' => '8', 'label' => 'Sekbid di OSIS', 'deskripsi' => 'Delapan seksi bidang yang menjalankan program sesuai keahliannya.'],
                    ['angka' => '4', 'label' => 'Divisi di MPK', 'deskripsi' => 'Empat divisi yang mengawasi dan menampung suara perwakilan kelas.'],
                ],
                'nilai' => [
                    ['judul' => 'Kreativitas', 'deskripsi' => 'Seni, media, dan acara yang lahir dari ide siswa.'],
                    ['judul' => 'Inovasi', 'deskripsi' => 'Program baru yang menjawab kebutuhan teman-teman di sekolah.'],
                    ['judul' => 'Kepemimpinan', 'deskripsi' => 'Belajar mengatur tim, rapat, dan mengambil keputusan.'],
                    ['judul' => 'Kolaborasi', 'deskripsi' => 'Bekerja lintas bidang untuk hasil yang lebih besar.'],
                ],
                'deskripsi_singkat' => 'Satu tahun penuh program, kolaborasi, dan karya yang digerakkan langsung oleh siswa.',
                'warna_aksen' => '#FCBF49',
                'warna_aksen_2' => '#F77F00',
                'warna_aksen_3' => '#D62828',
                'warna_ink' => '#F5EFE0',
                'warna_bg' => '#003049',
                'cta_judul' => 'Siap jadi bagian dari Ankasena?',
                'cta_deskripsi' => 'Kami terbuka untuk kolaborasi acara, sponsor kegiatan, dan ide program bersama komunitas maupun dunia usaha.',
            ]);
        }

        // Foto hero: file legacy public/assets/img/hero-landing.jpg sudah ada di repo.
        if (! isset(Setting::group('situs')['foto_hero'])) {
            Setting::put('situs', array_merge(Setting::group('situs'), ['foto_hero' => 'img/hero-landing.jpg']));
        }

        $majors = Major::count() ? [] : [
            ['title' => 'Akuntansi dan Keuangan Lembaga', 'singkatan' => 'AKL'],
            ['title' => 'Manajemen Perkantoran dan Layanan Bisnis', 'singkatan' => 'MPLB'],
            ['title' => 'Bisnis Digital', 'singkatan' => 'BD'],
            ['title' => 'Bisnis Digital Pemasaran', 'singkatan' => 'BDP'],
        ];
        foreach ($majors as $m) {
            Major::create($m);
        }
        // ponytail: nama jurusan seed UNVERIFIED — lengkapi ke 10 jurusan asli SMKN 6 Malang lewat admin.

        if (! Division::exists()) {
            // Struktur standar: 8 Sekbid OSIS + 4 Divisi MPK (deskripsi generik, edit di admin).
            foreach ([
                ['Sekbid Imtaq & Akhlak Mulia', 'Sekbid', 'Program keagamaan dan pembinaan karakter siswa.'],
                ['Sekbid Kewargaan & Kebangsaan', 'Sekbid', 'Kegiatan kebangsaan, toleransi, dan bela negara.'],
                ['Sekbid Sains & Prestasi', 'Sekbid', 'Olimpiade, lomba akademik, dan pengembangan riset.'],
                ['Sekbid Seni & Kreativitas', 'Sekbid', 'Seni rupa, musik, dan panggung karya siswa.'],
                ['Sekbid Olahraga & Kesehatan', 'Sekbid', 'Kebugaran, turnamen antarkelas, dan pola hidup sehat.'],
                ['Sekbid Media & Komunikasi', 'Sekbid', 'Mading, media sosial, dan dokumentasi kegiatan.'],
                ['Sekbid Kewirausahaan', 'Sekbid', 'Pasukan pelajar dan proyek bisnis sekolah.'],
                ['Sekbid Lingkungan & Kepedulian Sosial', 'Sekbid', 'Adipura sekolah, sosial, dan bakti sosial.'],
                ['Divisi Pengawasan Kerja OSIS', 'Divisi', 'Mengawasi program kerja dan anggaran OSIS.'],
                ['Divisi Penyalur Aspirasi', 'Divisi', 'Menampung dan menindaklanjuti usulan kelas.'],
                ['Divisi Humas & Dokumentasi', 'Divisi', 'Publikasi kegiatan dan komunikasi publik MPK.'],
                ['Divisi Sekretariat & Kaderisasi', 'Divisi', 'Administrasi MPK dan kaderisasi anggota baru.'],
            ] as [$title, $jenis, $deskripsi]) {
                Division::create(compact('title', 'jenis', 'deskripsi'));
            }
        }

        if (! Election::exists()) {
            $e = Election::create([
                'title' => 'Pemilihan Ketua OSIS 2026/2027',
                'tipe' => 'osis',
                'mulai' => '2026-10-28 08:00',
                'selesai' => '2026-11-04 15:00',
                'keterangan' => 'Satu akun Google = satu suara. Pilih satu kandidat — suara tidak dapat diubah.',
                'published' => true,
            ]);

            $akl = Major::where('singkatan', 'AKL')->first();
            $mplb = Major::where('singkatan', 'MPLB')->first();

            // ponytail: nama kandidat placeholder — isi nama asli + foto lewat admin.
            Candidate::create(['title' => 'Calon Ketua 1', 'kelas' => 'XI AKL 1', 'jurusan_id' => $akl?->id, 'no_urut' => 1, 'election_id' => $e->id]);
            Candidate::create(['title' => 'Calon Ketua 2', 'kelas' => 'XI MPLB 2', 'jurusan_id' => $mplb?->id, 'no_urut' => 2, 'election_id' => $e->id]);
        }

        if (! Article::exists()) {
            Article::create([
                'title' => 'Lomba cipta poster lingkungan sekolah',
                'excerpt' => 'Karya terbaik akan dipasang di mading sekolah dan media sosial Ankasena.',
                'kategori' => 'workshop',
                'cover_path' => 'img/workshop.jpg',
                'penulis' => 'Bidang Kreatif Ankasena',
                'date' => '2026-10-05',
                'konten' => "Terbuka untuk seluruh siswa. Karya dikumpulkan paling lambat akhir bulan melalui panitia bidang kreatif.",
                'unggulan' => true,
            ]);
            Article::create([
                'title' => 'Rapat pleno MPK pembahasan proker',
                'excerpt' => 'Seluruh divisi MPK menyepakati kalender program kerja semester ini.',
                'kategori' => 'kegiatan',
                'penulis' => 'Sekretariat MPK',
                'date' => '2026-09-18',
                'konten' => "Rapat pleno dihadiri perwakilan setiap kelas dan menghasilkan empat program prioritas.",
            ]);
            Article::create([
                'title' => 'Pendaftaran divisi MPK dibuka',
                'excerpt' => 'Siswi/siswa kelas X dan XI bisa mendaftar divisi sesuai minat hingga akhir pekan.',
                'kategori' => 'info',
                'penulis' => 'Divisi Kaderisasi MPK',
                'date' => '2026-09-02',
                'konten' => "Formulir tersedia di ruang MPK. Siapkan satu pas foto dan surat izin wali.",
            ]);
        }
    }
}
