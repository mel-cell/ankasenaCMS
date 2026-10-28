@extends('layouts.site')
@php($s = \App\Support\Situs::class)
@section('title', $division->title)
@section('meta_description', $division->deskripsi)
@section('content')
<section class="w pagehead">
    <p class="kicker">{{ $division->jenis }}</p>
    <h1>@if ($ikon = $s::img($division->ikon_path))<img src="{{ $ikon }}" alt="" style="height:.85em;width:auto;vertical-align:-.08em;margin-right:12px">@endif {{ $division->title }}</h1>
    @if ($division->deskripsi)<p class="lead">{{ $division->deskripsi }}</p>@endif
</section>

<section class="w pagefoot">
    @if ($division->konten)<div class="prose-article">{!! \App\Support\Markdown::toHtml($division->konten) !!}</div>@endif
    <p style="margin-top:clamp(40px,6vw,64px)"><a class="btn alt" href="/kabinet">Semua sekbid &amp; divisi <span class="arr arr-r"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 6h8M7 3l3 3-3 3"/></svg></span></a></p>
</section>
@endsection
