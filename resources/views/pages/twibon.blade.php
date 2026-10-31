@extends('layouts.site')

@section('title', 'Twibon')
@section('meta_description', 'Buat twibon Ankasena')

@section('content')
<section class="w pagehead">
    <h1>Twibon</h1>
    <p>Unggah fotomu, geser sesuai posisi, lalu unduh dan jadikan foto profil.</p>
</section>

<section class="w pagefoot">
    @unless ($aktif)
        <div class="card" style="padding:2rem;text-align:center">
            <p>{{ $pesan }}</p>
        </div>
    @else
        <div style="display:grid;gap:1.25rem;justify-items:center;max-width:34rem;margin-inline:auto">
            <canvas id="twibon-canvas" data-nama="{{ $nama_organisasi }}" data-periode="{{ $periode }}"
                    style="width:100%;max-width:540px;border-radius:var(--radius);touch-action:none;cursor:grab"></canvas>

            <label class="btn btn-secondary" style="cursor:pointer">
                Pilih foto
                <input type="file" id="twibon-foto" accept="image/*" hidden>
            </label>

            <label style="display:grid;gap:.4rem;width:100%;font-weight:600">
                Zoom
                <input type="range" id="twibon-zoom" min="1" max="3" step="0.05" value="1">
            </label>

            <button type="button" id="twibon-unduh" class="btn btn-primary">Unduh twibon</button>
        </div>
    @endunless
</section>

@vite('resources/js/twibon.js')
@endsection
