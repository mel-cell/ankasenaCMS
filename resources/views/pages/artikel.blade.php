@extends('layouts.site')
@php($cover = \App\Support\Situs::img($article->cover_path))
@php($s = \App\Support\Situs::class)
@section('title', $article->meta_title ?: $article->title)
@section('meta_description', $article->meta_description ?: $article->excerpt)
@section('content')
<section class="w pagehead">
    <p class="kicker">{{ $article->kategori ?: 'Berita' }} · {{ $article->date?->format('j M Y') }}</p>
    <h1>{{ $article->title }}</h1>
    @if ($article->excerpt)<p class="lead">{{ $article->excerpt }}</p>@endif
    @if ($article->penulis)<p class="kicker" style="margin-top:14px">{{ $article->penulis }}</p>@endif
</section>

<section class="w pagefoot">
    @if ($cover = $s::img($article->cover_path))
        <img src="{{ $cover }}" alt="{{ $article->title }}" style="width:100%;border-radius:18px;margin-bottom:clamp(28px,5vw,48px)">
    @endif
    <div class="prose-article">{!! \App\Support\Markdown::toHtml($article->konten) !!}</div>
    <p style="margin-top:clamp(40px,6vw,64px)"><a class="btn alt" href="/blog">Semua berita <span class="arr arr-r"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 6h8M7 3l3 3-3 3"/></svg></span></a></p>
</section>
@endsection
