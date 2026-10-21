{{--
    Halaman pratinjau desain (dev-only, /design).
    Tujuan: tim bisa lihat & pilih kepribadian visual tanpa menebak.
    Setelah dipilih, nilainya dipindah ke @theme di resources/css/site.css.
--}}
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pratinjau Desain — Ankasena</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    @vite(['resources/css/site.css', 'resources/js/site.js'])
</head>
<body class="min-h-dvh">

@php
$variants = [
    [
        'kode' => 'A',
        'nama' => 'Berani & urban',
        'desc' => 'Kontras tinggi, judul condensed, aksen merah. Kesan energik — cocok kalau OSIS mau terlihat muda dan berani.',
        'vars' => "--color-ink:#0B0B0C;--color-ink-soft:#3B3B3F;--color-muted:#7A7A80;--color-paper:#F5F1EA;--color-paper-2:#EDE7DC;--color-line:#D8D0C2;--color-amber:#E23A2E;--color-amber-soft:#F7D9D4;--font-display:'Anton',sans-serif;",
        'judul' => 'Suara siswa. Aksi nyata.',
        'swatch' => ['#0B0B0C', '#E23A2E', '#F5F1EA', '#EDE7DC', '#7A7A80'],
    ],
    [
        'kode' => 'B',
        'nama' => 'Hangat & ramah',
        'desc' => 'Krem, terakota, serif. Kesan editorial dan akrab — cocok kalau mau terasa dekat dengan murid, bukan institusi.',
        'vars' => "--color-ink:#2A211C;--color-ink-soft:#5B4B40;--color-muted:#8C7A6B;--color-paper:#FBF3E7;--color-paper-2:#F4E7D5;--color-line:#E4D3BE;--color-amber:#C4653C;--color-amber-soft:#F6DFD1;--font-display:'Fraunces Variable',ui-serif,Georgia,serif;",
        'judul' => 'Tempat cerita kita dimulai.',
        'swatch' => ['#2A211C', '#C4653C', '#FBF3E7', '#F4E7D5', '#8C7A6B'],
    ],
    [
        'kode' => 'C',
        'nama' => 'Rapi & profesional',
        'desc' => 'Putih bersih, biru, sans-serif tegas. Kesan tertata dan kredibel — paling netral, paling gampang diisi konten bento.',
        'vars' => "--color-ink:#0F172A;--color-ink-soft:#475569;--color-muted:#64748B;--color-paper:#FFFFFF;--color-paper-2:#F1F5F9;--color-line:#E2E8F0;--color-amber:#2563EB;--color-amber-soft:#DBEAFE;--font-display:'Plus Jakarta Sans Variable',ui-sans-serif,system-ui,sans-serif;",
        'judul' => 'Satu tempat untuk semua kegiatan sekolah.',
        'swatch' => ['#0F172A', '#2563EB', '#FFFFFF', '#F1F5F9', '#64748B'],
    ],
];
@endphp

{{-- ============ HEADER ============ --}}
<div class="border-b border-line bg-paper-2">
    <div class="wrap flex flex-wrap items-center justify-between gap-4 py-4 text-sm">
        <p class="font-semibold">Pratinjau Desain · Ankasena</p>
        <p class="text-muted">Dev-only · <a href="/" class="underline underline-offset-4">kembali ke situs</a></p>
    </div>
</div>

<div class="wrap py-12 sm:py-16">
    <h1 class="max-w-2xl text-[clamp(1.75rem,4vw,2.75rem)] leading-[1.08]">Pilih kepribadian visual</h1>
    <p class="mt-4 max-w-xl leading-relaxed text-ink-soft">
        Struktur yang dipakai sama di ketiganya: <strong>bento grid</strong> + banyak whitespace +
        foto asli sebagai fokus. Yang beda cuma warna dan tipografi. Pilih satu, nanti gue terapkan ke seluruh situs.
    </p>

    {{-- ============ VARIAN ============ --}}
    <div class="mt-10 grid gap-6 lg:grid-cols-3">
        @foreach ($variants as $v)
            <article class="overflow-hidden rounded-[20px] border border-line" style="{{ $v['vars'] }}">
                <div class="bg-paper p-6">
                    <div class="flex items-baseline justify-between gap-3">
                        <p class="text-[0.75rem] uppercase tracking-[0.08em] text-muted">Pilihan {{ $v['kode'] }}</p>
                        <div class="flex gap-1">
                            @foreach ($v['swatch'] as $hex)
                                <span class="h-5 w-5 rounded-full border border-line" style="background:{{ $hex }}"></span>
                            @endforeach
                        </div>
                    </div>

                    <p class="mt-1 font-semibold">{{ $v['nama'] }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-muted">{{ $v['desc'] }}</p>

                    <div class="mt-6 border-t border-line pt-6">
                        <h2 class="font-display text-[clamp(1.5rem,2.6vw,2rem)] leading-[1.05]">{{ $v['judul'] }}</h2>
                        <p class="mt-3 text-sm leading-relaxed text-ink-soft">
                            Kami merancang program yang dekat dengan keseharian murid: dari kegiatan kelas sampai panggung sekolah.
                        </p>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="btn btn-primary text-sm">Lihat program</span>
                            <span class="btn btn-secondary text-sm">Kenali kabinet</span>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="badge">Kegiatan</span>
                            <span class="badge">Prestasi</span>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>

{{-- ============ BENTO ============ --}}
<div class="border-y border-line bg-paper-2">
    <div class="wrap py-14">
        <p class="text-[0.75rem] uppercase tracking-[0.08em] text-muted">Struktur</p>
        <h2 class="mt-3 text-[clamp(1.5rem,3vw,2.25rem)]">Bento grid</h2>
        <p class="mt-3 max-w-lg text-sm leading-relaxed text-ink-soft">
            Kartu beda ukuran, sudut besar, gap lega. Isi gampang diganti: foto kegiatan, program, statistik, atau pengurus.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-4">
            <div class="card flex min-h-[220px] flex-col justify-end bg-ink p-6 text-paper sm:col-span-2 sm:row-span-2">
                <p class="text-[0.75rem] uppercase tracking-[0.08em] opacity-60">Foto utama</p>
                <p class="mt-2 font-display text-2xl leading-tight">Satu momen, satu cerita.</p>
                <p class="mt-2 text-sm opacity-70">Ganti dengan foto kegiatan asli. Ini yang bikin orang berhenti scroll.</p>
            </div>

            <div class="card flex flex-col justify-between p-6">
                <p class="text-[0.75rem] uppercase tracking-[0.08em] text-muted">Kegiatan</p>
                <p class="font-display text-4xl leading-none">40+</p>
                <p class="text-sm text-muted">sepanjang periode</p>
            </div>

            <div class="card flex flex-col justify-between p-6">
                <p class="text-[0.75rem] uppercase tracking-[0.08em] text-muted">Pengurus</p>
                <p class="font-display text-4xl leading-none">12</p>
                <p class="text-sm text-muted">di 5 divisi</p>
            </div>

            <div class="card p-6 sm:col-span-2">
                <span class="badge">Program</span>
                <p class="mt-3 font-semibold">Ankasena Berbagi</p>
                <p class="mt-1 text-sm leading-relaxed text-muted">Penggalangan dan bakti sosial lintas kelas.</p>
            </div>
        </div>
    </div>
</div>

{{-- ============ KOMPONEN ============ --}}
<div class="wrap py-14">
    <p class="text-[0.75rem] uppercase tracking-[0.08em] text-muted">Inventaris</p>
    <h2 class="mt-3 text-[clamp(1.5rem,3vw,2.25rem)]">Komponen</h2>

    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl border border-line p-6">
            <p class="text-[0.75rem] uppercase tracking-[0.08em] text-muted">Tombol</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="btn btn-primary text-sm">Primer</span>
                <span class="btn btn-secondary text-sm">Sekunder</span>
            </div>
        </div>

        <div class="rounded-xl border border-line p-6">
            <p class="text-[0.75rem] uppercase tracking-[0.08em] text-muted">Label</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="badge">Kategori</span>
                <span class="badge">Status</span>
            </div>
        </div>

        <div class="rounded-xl border border-line p-6">
            <p class="text-[0.75rem] uppercase tracking-[0.08em] text-muted">Skala judul</p>
            <p class="mt-3 font-display text-2xl leading-tight">H2 · Judul section</p>
            <p class="font-display text-lg leading-tight">H3 · Sub-judul</p>
        </div>

        <div class="card sm:col-span-2 lg:col-span-2">
            <div class="aspect-[16/9] bg-paper-2"></div>
            <div class="p-5">
                <span class="badge">Kegiatan</span>
                <p class="mt-3 font-display text-xl">Judul artikel contoh</p>
                <p class="mt-2 text-sm leading-relaxed text-muted">Ringkasan singkat artikel, maksimal dua baris, biar kartu tetap ringkas.</p>
            </div>
        </div>

        <div class="card">
            <div class="aspect-[4/5] bg-paper-2"></div>
            <div class="p-4">
                <p class="text-sm font-semibold">Nama Pengurus</p>
                <p class="text-xs text-muted">Ketua · Kepemimpinan</p>
            </div>
        </div>
    </div>

    <p class="mt-12 max-w-lg text-sm leading-relaxed text-muted">
        Catatan: teks, foto, dan urutan semuanya diatur dari <strong>/cp</strong>. Halaman ini cuma buat memilih
        warna &amp; tipografi — bukan tempat edit konten.
    </p>
</div>

</body>
</html>
