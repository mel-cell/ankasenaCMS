@extends('layouts.site')
@section('title', $title)
@section('content')
<section class="w pagehead">
    <p class="kicker">Sekbid &amp; Divisi</p>
    <h1>{{ $title }}</h1>
    @if ($intro)<p class="lead">{{ $intro }}</p>@endif
</section>

<section class="w pagefoot">
    @if ($groups->isEmpty())
        <p class="lead">Belum ada data pengurus. Tambahkan lewat Admin → Kabinet.</p>
    @endif
    @foreach ($groups as $divisi => $officials)
        <div class="grp">
            <p class="kicker">{{ $divisi }}</p>
            <div class="grid3" style="margin-top:24px">
                @foreach ($officials as $official)
                    @include('partials.kartu-kabinet', ['official' => $official])
                @endforeach
            </div>
        </div>
    @endforeach
</section>
@endsection
