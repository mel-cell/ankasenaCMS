@extends('layouts.site')
@section('title', 'Hasil Pemilihan')
@section('content')
<style>
.h-bar{height:10px;border-radius:99px;background:var(--panel);overflow:hidden}
.h-bar i{display:block;height:100%;background:var(--lime2)}
.h-row{max-width:720px}
</style>

<section class="w pagehead">
    <p class="kicker">Hasil</p>
    <h1>Hasil Pemilihan</h1>
    <p class="lead">Hasil pemilihan yang sudah berakhir. Data suara anonim dan tersimpan permanen.</p>
</section>

@if (empty($events))
<section class="w"><p class="lead">Belum ada hasil — halaman ini terisi setelah masa pemilihan berakhir.</p></section>
@endif

@foreach ($events as $event)
<section class="w" style="margin-top:clamp(40px,7vw,72px)">
    <p class="kicker">{{ $event['tipe_label'] }}@if ($event['status_live']) · berlangsung@endif</p>
    <h2>{{ $event['title'] }}</h2>
    <p class="lead">Total suara: {{ $event['total'] }}</p>

    <div style="display:flex;flex-direction:column;gap:20px;margin-top:26px">
        @foreach ($event['per_kandidat'] as $k)
        <div class="h-row">
            <div style="display:flex;justify-content:space-between;gap:16px;font-size:15px">
                <b>{{ $k['no_urut'] }}. {{ $k['title'] }}</b>
                <span>{{ $k['suara'] }} suara · {{ $k['persen'] }}%</span>
            </div>
            <div class="h-bar" style="margin-top:8px"><i style="width:{{ $k['persen'] }}%"></i></div>
        </div>
        @endforeach
    </div>

    @if ($event['per_jurusan'])
    <p class="kicker" style="margin-top:38px">Persebaran per jurusan</p>
    <ul class="misi">
        @foreach ($event['per_jurusan'] as $nama => $n)
        <li>{{ $nama }} — {{ $n }} suara</li>
        @endforeach
    </ul>
    @endif
</section>
@endforeach
@endsection
