@php($s = \App\Support\Situs::class)
<div>
    <div style="aspect-ratio:4/5;background:var(--card);border-radius:14px;overflow:hidden">
        @if ($foto = $s::img($official->foto_path))
            <img src="{{ $foto }}" alt="Foto {{ $official->nama }}" loading="lazy"
                 style="width:100%;height:100%;object-fit:cover">
        @endif
    </div>
    <p style="margin-top:10px;font-size:14px;font-weight:600">{{ $official->nama }}</p>
    <p style="margin-top:2px;font-size:12px;color:var(--mut)">{{ $official->jabatan }}@if ($official->kelas) · {{ $official->kelas }}@endif</p>
</div>
