# AGENTS.md — Ankasena

Situs profil **OSIS MPK** + CMS + voting + twibon generator.

## Stack

| Layer | Pilih |
|---|---|
| PHP | 8.4 (Debian trixie, `/usr/bin/php8.4`) |
| Framework | Laravel 13.38 |
| CMS | Statamic 6 (`statamic/cms`) |
| DB | PostgreSQL `ankasena` @ 127.0.0.1:5432 |
| Asset | Vite + Tailwind |
| Image | Canvas API di browser (twibon), GD (Statamic) |

## Perintah

```bash
sudo bash bin/setup-env.sh   # sekali: extension PHP + buat DB ankasena
php artisan migrate
composer install
npm install
npm run dev              # vite
php artisan serve        # http://localhost:8000
php please ...           # CLI Statamic (mis. `php please make:user`)
```

## Struktur

```
app/                     # Laravel: controller voting/twibon, model
content/                 # Statamic flat-file (SEMUA konten ada di sini)
  collections/
    pages/               # halaman statis (beranda, tentang, kontak)
    artikel/             # blog
    kabinet/             # pengurus
    calon/               # kandidat voting        [fase 2]
    template_twibon/     # bingkai twibon          [fase 3]
  globals/
    pengaturan.yaml      # setting situs + toggle fitur
  trees/
resources/views/          # Antlers (.antlers.html) + Blade
resources/css|js/
public/assets/            # upload gambar via CP Statamic
routes/web.php            # route voting & twibon (Blade)
database/migrations/      # tabel voters & votes (fase 2)
```

## Model konten (isian)

### Global `pengaturan`
`nama_organisasi`, `sekolah`, `tagline`, `deskripsi`, `logo`, `favicon`,
`periode`, `visi`, `misi` (grid: `poin`), `alamat`,
`instagram`, `tiktok`, `youtube`, `email`, `whatsapp`,
`voting_open` (toggle), `twibon_aktif` (toggle), `pesan_twibon`.

### Collection `artikel`
`title` (req), `slug`, `excerpt` (textarea, ≤200), `cover` (assets, 1 gambar),
`kategori` (taxonomy), `konten` (markdown), `penulis`, `date` (date),
`unggulan` (toggle), `meta_title`, `meta_description`.

### Collection `kabinet`
`nama` (req), `jabatan` (select), `divisi` (select), `kelas`, `angkatan`,
`foto` (assets, 1 gambar, focal point), `bio` (textarea), `instagram`,
`urutan` (integer), `aktif` (toggle, default true), `periode`.

Jabatan: Ketua, Wakil Ketua, Sekretaris I, Sekretaris II, Bendahara I,
Bendahara II, Koordinator Divisi, Anggota.

### Collection `calon` — fase 2
`nama`, `slug`, `foto`, `jabatan_dipilih` (ketua|wakil), `visi`, `misi`
(grid), `urutan`, `warna` (hex, buat kartu).

### Collection `pemilih` — fase 2 (allowlist)
`email` (req, unik), `nama`, `kelas`, `aktif` (toggle).

### Collection `template_twibon` — fase 3
`nama`, `gambar` (PNG full-bleed, jendela foto transparan di tengah),
`slot_teks` (grid: `label`, `x`, `y`, `ukuran`, `warna`, `font`, `max_width`),
`aktif`.

### Tabel DB (fase 2)
- `voters` — `email` UNIQUE, `google_sub`, `nama`, `voted_at`
- `votes` — `voter_id` UNIQUE (1 orang 1 suara, dijaga DB), `calon_id`

## Konvensi

- Konten **selalu** lewat Statamic CP (`/cp`) → flat-file + git. Jangan hardcode di view.
- Query data dinamis (voting) lewat Eloquent, bukan flat-file.
- Halaman konten: Antlers. Halaman ber-logic (voting/twibon): Blade + controller.
- Bahasa UI: Indonesia. Nama variabel/kode: Inggris.
- Tailwind utility-first. Token warna/font di `resources/css/app.css` (lihat `DESIGN.md`).
- Jangan tambah dependency tanpa alasan kuat.

## Gotcha

- `/etc/php/8.4/cli/php.ini` baris `extension=pdo_mysql` harus tetap **dikomentari**
  (dimuat dua kali → warning `undefined symbol: pdo_dbh_ce`). Extension dimuat via `conf.d/`.
- `statamic:install` masih perlu dijalankan ulang setelah extension PHP terpasang (terputus saat scaffold).
- Statamic 6 butuh PHP ≥ 8.3 + ext mbstring/bcmath/gd/exif/xml/curl/zip.
- `content/` ikut git. Database SQLite (`database/database.sqlite`) **tidak** ikut git.
- Twibon dirender di browser: jangan upload foto user ke server, jangan simpan output.

## Fase

1. **Profil** — beranda, tentang, kabinet, blog, kontak. ← sekarang
2. **Voting** — Google OAuth + allowlist email + 1 suara/orang + hasil.
3. **Twibon** — template Canva + generator Canvas + download PNG.
