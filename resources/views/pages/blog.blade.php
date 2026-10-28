@extends('layouts.site')
@section('title', 'Berita')
@section('content')
<section class="w pagehead">
    <p class="kicker">Berita</p>
    <h1>{{ $title }}</h1>
</section>

<section class="w pagefoot">
    @if ($filter)
        <p style="font-size:13px;color:var(--mut);margin-bottom:28px">Filter:
            <i style="font-style:normal;background:var(--lime);color:var(--bg);padding:3px 10px;border-radius:99px;font-size:11px;font-weight:500">{{ $filter }}</i>
            <a href="/blog" style="text-decoration:underline;text-underline-offset:3px">Hapus filter</a>
        </p>
    @endif

    @if ($articles->isEmpty())
        <p class="lead">Belum ada artikel di sini.</p>
    @else
        <div class="grid3">
            @foreach ($articles as $article)
                @include('partials.card-artikel', ['article' => $article])
            @endforeach
        </div>
    @endif
</section>
@endsection
