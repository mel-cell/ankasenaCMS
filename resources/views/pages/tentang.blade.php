@extends('layouts.site')
@php($s = \App\Support\Situs::class)
@section('title', $title)
@section('content')
<section class="w pagehead">
    <p class="kicker">Tentang</p>
    <h1>{{ $title }}</h1>
    @if ($intro)<p class="lead">{{ $intro }}</p>@endif
</section>

@if ($s::get('visi'))
<section class="w" style="padding-top:clamp(8px,2vw,24px)">
    <p class="kicker">Visi</p>
    <p class="big" style="font-size:clamp(24px,3.4vw,34px);max-width:26ch;margin-top:16px">{{ $s::get('visi') }}</p>
</section>
@endif

@if ($s::get('misi'))
<section class="w" style="padding-top:clamp(56px,8vw,96px);padding-bottom:clamp(24px,4vw,48px)">
    <p class="kicker">Misi</p>
    <ol class="misi">
        @foreach ($s::get('misi') as $i => $m)
        @if (!empty($m['poin']))<li><b>{{ $i + 1 }}</b><p>{{ $m['poin'] }}</p></li>@endif
        @endforeach
    </ol>
</section>
@endif
@endsection
