<!doctype html>
<html lang="id" style="--lime: {{ \App\Support\Situs::get('warna_aksen', '#FCBF49') }}; --lime2: {{ \App\Support\Situs::get('warna_aksen_2', '#F77F00') }}; --red: {{ \App\Support\Situs::get('warna_aksen_3', '#D62828') }}; --fg: {{ \App\Support\Situs::get('warna_ink', '#F5EFE0') }}; --bg: {{ \App\Support\Situs::get('warna_bg', '#003049') }};@if ($photo = \App\Support\Situs::img(\App\Support\Situs::get('foto_hero'))) --photo: url('{{ $photo }}');@endif">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', \App\Support\Situs::get('nama_organisasi')) — {{ \App\Support\Situs::get('nama_organisasi') }}</title>
    <meta name="description" content="@yield('meta_description', \App\Support\Situs::get('deskripsi'))">

    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', \App\Support\Situs::get('nama_organisasi'))">
    <meta property="og:description" content="@yield('meta_description', \App\Support\Situs::get('deskripsi'))">
    @if ($ogImage = \App\Support\Situs::img($cover ?? null))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif

    @if ($favicon = \App\Support\Situs::img(\App\Support\Situs::get('favicon')))
        <link rel="icon" href="{{ $favicon }}">
    @else
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    @endif

    @vite(['resources/js/site.js', 'resources/css/site.css', 'resources/css/landing.css'])
</head>
<body class="min-h-dvh flex flex-col">
    @include('partials.header')

    <main id="konten" class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
