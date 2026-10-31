@extends('layouts.site')

@section('title', 'Halaman tidak ditemukan')
@section('meta_description', 'Halaman tidak ditemukan')

@section('content')
<section class="w pagehead">
    <p class="kicker">404</p>
    <h1>Halaman tidak ditemukan</h1>
    <p class="lead">Alamat yang kamu buka tidak ada atau sudah dipindahkan.</p>
</section>

<section class="w pagefoot" style="text-align:center">
    <p style="font:800 clamp(64px,14vw,120px)/1 system-ui;color:var(--panel);letter-spacing:-.04em;margin:0 0 18px;user-select:none">404</p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a class="btn btn-primary" href="/">Kembali ke beranda</a>
        <a class="btn btn-secondary" href="/blog">Lihat berita</a>
    </div>
</section>
@endsection
