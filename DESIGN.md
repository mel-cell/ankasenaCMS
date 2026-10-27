# DESIGN.md — Ankasena

Design system. Token di sini adalah sumber kebenaran; implementasi di
`resources/css/site.css` + `resources/css/landing.css`. Kalau butuh nilai baru, update file ini dulu.

## Arah

Editorial sekolah modern. Kertas hangat + tinta gelap, aksen amber.
Rapi, lapang, tipografi jadi bintang. **Bukan** dashboard, bukan startup gradient.

- Kesan: hangat, kredibel, muda.
- Hindari: shadow tebal, gradient ungu-biru, emoji dekoratif, glassmorphism.

## Warna

| Token | Hex | Pakai untuk |
|---|---|---|
| `ink` | `#0E1B2A` | teks utama, heading, tombol primer |
| `ink-soft` | `#3A4859` | teks sekunder |
| `muted` | `#6B7787` | caption, metadata |
| `paper` | `#FAF8F4` | background halaman |
| `paper-2` | `#F1EDE5` | section alternatif, card |
| `line` | `#E1DAD0` | border, divider |
| `amber` | `#C9862F` | aksen, underline, angka, hover |
| `amber-soft` | `#F6E7CF` | badge, highlight |
| `danger` | `#B4423A` | error, tombol hapus |

Kontras minimum 4.5:1 untuk teks body. Jangan taruh teks amber di atas paper.

## Tipografi

| Peran | Font | Catatan |
|---|---|---|
| Display | **Fraunces** | heading h1–h2, optical size besar, weight 500–600 |
| Body | **Plus Jakarta Sans** | semua teks, weight 400/500/600 |
| Mono | **JetBrains Mono** | kode di artikel |

Skala (rem, mobile → desktop):

| Nama | Size | Line-height | Pakai |
|---|---|---|---|
| `display` | 2.75 → 4.5 | 1.02 | hero h1 |
| `h1` | 2.25 → 3 | 1.1 | judul halaman |
| `h2` | 1.75 → 2.25 | 1.15 | section |
| `h3` | 1.25 → 1.5 | 1.25 | sub-section, kartu |
| `body` | 1 → 1.0625 | 1.65 | paragraf |
| `small` | 0.875 | 1.5 | metadata |
| `micro` | 0.75 | 1.4 | label, tracking 0.08em, uppercase |

## Spacing & grid

- Skala 4px: `4 8 12 16 24 32 48 64 96 128`.
- Container: `max-w-[76rem]`, padding horizontal `1.25rem` mobile / `2rem` desktop.
- Jarak antar-section: `4rem` mobile / `7rem` desktop.
- Grid kartu: 1 kolom mobile, 2 kolom `sm`, 3 kolom `lg`. Gap `1.5rem`.

## Bentuk

| Token | Nilai |
|---|---|
| radius-sm | 6px |
| radius | 12px |
| radius-lg | 20px |
| border | 1px solid `line` |
| shadow | `0 1px 2px rgb(14 27 42 / .06)` — satu-satunya shadow |

## Komponen

**Button primer** — bg `ink`, teks `paper`, radius 12px, padding `0.75rem 1.25rem`,
weight 600, hover bg `#1B2C3F`, fokus ring 2px `amber` offset 2px.

**Button sekunder** — border 1px `line`, bg transparan, teks `ink`, hover bg `paper-2`.

**Card** — bg `paper`, border `line`, radius 12px, shadow saat hover saja
(`0 2px 8px rgb(14 27 42 / .08)`), foto aspect 4:5 untuk kabinet, 16:9 untuk artikel.

**Badge** — bg `amber-soft`, teks `ink`, radius 999px, `micro`, padding `0.25rem 0.625rem`.

**Link dalam teks** — warna `amber`, underline offset 3px, hover underline tebal.

## Motion

- Durasi 150–200ms, easing `cubic-bezier(.2,.7,.3,1)`.
- Hover: transform maksimal `translateY(-2px)` atau opacity.
- Hormati `prefers-reduced-motion`.

## Aturan

- Jangan pakai lebih dari 2 jenis font per layar (display + body sudah cukup).
- Foto selalu punya `alt` bermakna. Foto orang: focal point, aspect tetap.
- Jangan pakai warna `amber` untuk teks paragraf panjang.
- Bahasa UI Indonesia; angka & tanggal format `id-ID`.

## Komponen tambahan (beranda)

**Stat band** — section `border-y border-line bg-paper-2`, grid 2/4 kolom, angka `font-display` semibold besar warna `amber`, label `small` `text-ink-soft`, center. Sumber data: grid `statistik` di globals.

**Portal card** — kartu OSIS/MPK: bg `paper`, border `line`, radius 12px, center; logo 80px bulat (fallback monogram `font-display` di lingkaran `amber-soft`), nama h3 `font-display`, deskripsi `small`, link teks `amber`.

## Token v3 — situs gelap (SEMUA halaman, aktif)

Palet OSIS-MPK (#003049/#D62828/#F77F00/#FCBF49), dasar gelap, aturan 60-30-10:
60% latar navy `#003049`, 30% surface (derive `color-mix` dari bg: card ≈ 86% bg + putih, panel ≈ 72% bg + hitam, line ≈ 68% bg + putih), 10% aksen emas `#FCBF49` (+ oranye `#F77F00` hover, merah `#D62828` aksen kuat/error, dipakai hemat).

| Token CSS | Default | Sumber CP (Globals → Pengaturan) |
|---|---|---|
| `--bg` | `#003049` | `warna_bg` |
| `--fg` | `#F5EFE0` (teks terang) | `warna_ink` |
| `--lime` | `#FCBF49` (aksen utama) | `warna_aksen` |
| `--lime2` | `#F77F00` | `warna_aksen_2` |
| `--red` | `#D62828` | `warna_aksen_3` |
| `--panel`/`--card`/`--line`/`--mut`/`--soft` | derive dari `--bg`/`--fg` | otomatis |

- Token di-inject inline di `<html>` oleh kedua layout (`layout.antlers.html` + `layouts/landing.antlers.html`) → `landing.css` dan `site.css` (Tailwind `@theme` mereferensikan var yang sama) selalu sinkron, tanpa duplikasi.
- Teks di atas aksen emas = warna `--bg` (navy), bukan putih.
- Font display & body: Plus Jakarta Sans Variable saja. Fraunces pensiun dari display.
- `prefers-color-scheme` override dihapus — situs memang gelap by design.
- Header (`.top` + `partial:navigasi`) dan footer dipakai lintas layout — satu sumber.
- Foto: Unsplash (lisensi bebas), disimpan di disk `assets` (`public/assets/img/`); hero via `foto_hero`, cover artikel via field `cover`.

Token v1 (di atas) dan v2 (landing lime terang) sudah TIDAK dipakai — histori saja.
