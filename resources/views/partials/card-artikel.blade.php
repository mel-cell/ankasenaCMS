@php($s = \App\Support\Situs::class)
<a href="{{ url('/blog/'.$article->slug) }}" class="post">
    <div class="im">
        @if ($cover = $s::img($article->cover_path))
            <img src="{{ $cover }}" alt="{{ $article->title }}" loading="lazy">
        @endif
        <i>{{ $article->kategori ?: 'Info' }}</i>
    </div>
    <small>{{ $article->date?->format('j/n/Y') }}</small>
    <h3>{{ $article->title }}</h3>
    @if ($article->excerpt)<p>{{ $article->excerpt }}</p>@endif
</a>
