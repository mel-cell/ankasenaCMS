@extends('layouts.site')
@php($s = \App\Support\Situs::class)
@section('title', $title)
@section('content')
<section class="w pagehead">
    <p class="kicker">Kontak</p>
    <h1>{{ $title }}</h1>
    @if ($intro)<p class="lead">{{ $intro }}</p>@endif
</section>

<section class="w pagefoot">
    <div class="flatgrid">
        @if ($s::get('email'))
            <div><p class="kicker">Email</p><a href="mailto:{{ $s::get('email') }}">{{ $s::get('email') }}</a></div>
        @endif
        @if ($s::get('whatsapp'))
            <div><p class="kicker">WhatsApp</p><a href="https://wa.me/{{ $s::get('whatsapp') }}" rel="noopener">+{{ $s::get('whatsapp') }}</a></div>
        @endif
        @if ($s::get('instagram'))
            <div><p class="kicker">Instagram</p><a href="https://instagram.com/{{ $s::get('instagram') }}" rel="noopener">@{{ $s::get('instagram') }}</a></div>
        @endif
        @if ($s::get('alamat'))
            <div><p class="kicker">Alamat</p><p style="font-size:clamp(17px,2.4vw,22px);font-weight:500;line-height:1.5;margin-top:4px">{{ $s::get('alamat') }}</p></div>
        @endif
    </div>
</section>
@endsection
