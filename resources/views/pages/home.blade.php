@extends('layouts.site')
@php($s = \App\Support\Situs::class)
@section('content')
<header class="hero w">
  <p class="hbadge">OSIS &amp; MPK · {{ $s::get('sekolah') }}</p>
  <h1>{{ $s::get('tagline') }}</h1>
  <svg class="mark" aria-hidden="true"><use href="#m"/></svg>
</header>

<section class="event">
  <div class="info w">
    <div class="f">{{ $s::get('periode') }}</div><small>Masa bakti kepengurusan</small>
    <p>{{ $s::get('deskripsi_singkat') }}</p>
    <div class="btns"><a class="btn go" href="#kontak">Gabung sekarang</a><a class="btn alt" href="/kabinet">Lihat program <span class="arr arr-r"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 6h8M7 3l3 3-3 3"/></svg></span></a></div>
  </div>
  <div class="photo">
    @if ($hero = $s::img($s::get('foto_hero')))
      <img src="{{ $hero }}" alt="Siswa berdiskusi dalam forum organisasi" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
    @endif
    <a class="badge" href="/tentang" aria-label="Kenali Ankasena"><svg class="r" viewBox="0 0 100 100" aria-hidden="true"><defs><path id="c" d="M50 50m-38 0a38 38 0 1 1 76 0a38 38 0 1 1 -76 0"/></defs><text font-size="8.5" letter-spacing="2.2" style="fill:var(--bg)" font-family="Plus Jakarta Sans,sans-serif"><textPath href="#c">OSIS · MPK · ANKASENA · 2026 ·</textPath></text></svg><span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 2v10M3 8l4 4 4-4"/></svg></span></a>
  </div>
</section>

<section class="about w" id="tentang">
  <div>
    <h2 class="big">{{ $s::get('deskripsi') }}</h2>
    <a class="btn" href="/tentang">Kenali Ankasena <span class="arr arr-r"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 6h8M7 3l3 3-3 3"/></svg></span></a>
  </div>
  <div class="orgs">
    <div class="org"><div class="logo">OSIS</div><span><b>OSIS</b>{{ $s::get('deskripsi_osis', 'Organisasi Siswa Intra Sekolah') }}</span></div>
    <div class="org"><div class="logo">MPK</div><span><b>MPK</b>{{ $s::get('deskripsi_mpk', 'Majelis Perwakilan Kelas') }}</span></div>
  </div>
</section>

<section class="stats" id="struktur">
    @foreach (($s::get('statistik') ?? []) as $st)
    <div class="stat"><div class="num">{{ $st['angka'] }}</div><b>{{ $st['label'] }}</b><p>{{ $st['deskripsi'] }}</p></div>
    @endforeach
</section>

<section class="focus w">
    <h2 class="big">{{ $s::get('visi') }}</h2>
    <div class="rows">
      @foreach (($s::get('nilai') ?? []) as $n)
      <div class="row"><h3>{{ $n['judul'] }}</h3><p>{{ $n['deskripsi'] }}</p></div>
      @endforeach
    </div>
</section>

<section class="news w" id="berita">
  <div class="sec"><h2>Berita terbaru</h2><a class="btn alt" href="/blog">Semua berita <span class="arr arr-r"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 6h8M7 3l3 3-3 3"/></svg></span></a></div>
  <div class="grid3">
    @foreach ($articles as $article)
        @include('partials.card-artikel', ['article' => $article])
    @endforeach
  </div>
</section>

<section class="saran w" id="saran">
  <div><h2>Kotak saran</h2><p>Punya ide, kritik, atau masukan untuk sekolah? Tulis di sini. Nama boleh dikosongkan.</p></div>
  @if (session('saran_ok'))
    <div id="ok" role="status">Terima kasih, saran kamu sudah kami terima.</div>
  @else
    @if ($errors->any())<div id="ok" role="alert"><p>{{ $errors->first() }}</p></div>@endif
    <form id="f" method="post" action="{{ url('/saran') }}" aria-label="Kotak saran">
        @csrf
        <input type="text" name="nama" placeholder="Nama (opsional)" aria-label="Nama" value="{{ old('nama') }}">
        <textarea name="pesan" placeholder="Tulis saran kamu" aria-label="Saran" required>{{ old('pesan') }}</textarea>
        <input type="text" name="description" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
        <div><button class="btn dk">Kirim saran</button></div>
    </form>
  @endif
</section>

<section class="cta w" id="kontak">
  <h2>{{ $s::get('cta_judul') }}</h2>
  <div class="btns"><a class="btn dk" href="mailto:{{ $s::get('email') }}">Daftar sekarang</a><a class="btn alt" href="/blog">Lihat kegiatan <span class="arr arr-r"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 6h8M7 3l3 3-3 3"/></svg></span></a></div>
  <svg class="mark" aria-hidden="true"><use href="#m"/></svg>
</section>
@endsection
