@extends('layouts.site')
@section('title', 'Pemilihan')
@section('content')
<style>
.note{border-radius:14px;padding:14px 18px;border:1.5px solid;margin:0 0 10px;font-weight:500}
.note.err{border-color:var(--red);color:var(--red);background:color-mix(in srgb,var(--red) 8%,transparent)}
.note.ok{border-color:#2E7D32;color:#2E7D32;background:color-mix(in srgb,#2E7D32 8%,transparent)}
.vform{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;max-width:760px;margin-top:22px}
.vform label{display:flex;flex-direction:column;gap:7px;font-size:13px;color:var(--mut);font-weight:600}
.vform input,.vform select{padding:11px 13px;border:1.5px solid var(--line);border-radius:10px;background:var(--panel);color:inherit;font:inherit;font-weight:400}
.vgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:22px;margin-top:26px}
.kand{position:relative;border:1.5px solid var(--line);border-radius:20px;overflow:hidden;background:var(--card);cursor:pointer;transition:transform .2s,border-color .2s,box-shadow .2s;display:block}
.kand input{position:absolute;opacity:0;pointer-events:none}
.kand .im{aspect-ratio:4/3;overflow:hidden;background:var(--panel);display:block}
.kand .im img{width:100%;height:100%;object-fit:cover;display:block}
.kand .no{position:absolute;top:12px;left:12px;background:var(--bg);color:var(--fg);font-weight:800;border-radius:99px;min-width:30px;height:30px;display:grid;place-items:center;font-size:13px;padding:0 8px}
.kand .bd{padding:16px 18px;display:flex;flex-direction:column;gap:5px}
.kand .bd small{color:var(--mut)}
.kand .bd p{font-size:14px;color:var(--mut);display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;margin:0}
.kand:hover{transform:translateY(-4px);border-color:var(--lime2)}
.kand.pilih{border-color:var(--lime2);box-shadow:0 0 0 2px var(--lime2)}
</style>

<section class="w pagehead">
    <p class="kicker">Pemilihan</p>
    <h1>Pemilihan Ketua OSIS &amp; MPK</h1>
    <p class="lead">Satu akun, satu suara per pemilihan. Pilihanmu rahasia dan tidak bisa diganti.</p>
</section>

@if (session('vote_error'))<section class="w" style="padding-top:18px"><p class="note err">{{ session('vote_error') }}</p></section>@endif
@if (session('vote_sukses'))<section class="w" style="padding-top:18px"><p class="note ok">{{ session('vote_sukses') }}</p></section>@endif

@if (! $voting_open)
<section class="w"><p class="lead">Masa pemilihan sedang ditutup. Pantau pengumuman selanjutnya di sini.</p></section>
@elseif ($guest)
<section class="w">
    <p class="lead">Login pakai Google untuk ikut memilih.</p>
    <p style="margin-top:20px"><a class="btn" href="{{ url('/auth/google') }}">Login dengan Google</a></p>
</section>
@else

@if (! $profil_lengkap)
<section class="w" style="margin-top:10px">
    <p class="kicker">Lengkapi profil dulu</p>
    <p class="lead">Isi data diri sekolahmu sekali saja sebelum memilih.</p>
    @if ($jurusan_list->isNotEmpty())
    <form method="post" action="{{ url('/vote') }}" class="vform">
        @csrf
        <label>Nama lengkap<input type="text" name="nama_lengkap" value="{{ $profil->nama_lengkap }}" required></label>
        <label>Kelas<input type="text" name="kelas" placeholder="mis. XI RPL 1" value="{{ $profil->kelas }}" required></label>
        <label>Jurusan
            <select name="jurusan" required>
                <option value="">Pilih jurusan…</option>
                @foreach ($jurusan_list as $j)
                    <option value="{{ $j->id }}"{{ $profil->jurusan_id == $j->id ? ' selected' : '' }}>{{ $j->title }}</option>
                @endforeach
            </select>
        </label>
        <button class="btn" type="submit" style="align-self:end">Simpan profil</button>
    </form>
    @else
    <p class="note err">Admin belum mengisi daftar jurusan (Admin → Jurusan).</p>
    @endif
</section>
@endif

@if (empty($events))
<section class="w" style="margin-top:24px"><p class="lead">Belum ada pemilihan. <a href="/vote/hasil">Lihat hasil sebelumnya</a>.</p></section>
@endif

@foreach ($events as $event)
<section class="w" style="margin-top:clamp(40px,7vw,72px)">
    <p class="kicker">{{ $event['tipe_label'] }}</p>
    <h2>{{ $event['title'] }}</h2>
    @if ($event['keterangan'])<p class="lead" style="max-width:60ch">{{ $event['keterangan'] }}</p>@endif

    @if ($event['status'] === 'belum')
    <p class="lead" style="margin-top:18px">Dibuka {{ $event['mulai_teks'] }}.</p>
    @elseif ($event['status'] === 'selesai')
    <p class="lead" style="margin-top:18px">Pemilihan sudah berakhir — <a href="/vote/hasil">lihat hasil</a>.</p>
    @elseif ($event['voted'])
    <p class="lead" style="margin-top:18px">Kamu sudah memilih di pemilihan ini. Terima kasih!</p>
    @elseif (! $profil_lengkap)
    <p class="lead" style="margin-top:18px">Lengkapi profil di atas untuk membuka pemilihan.</p>
    @else
    <form method="post" action="{{ url('/vote') }}">
        @csrf
        <input type="hidden" name="pemilu_id" value="{{ $event['id'] }}">
        <div class="vgrid">
            @foreach ($event['kandidat'] as $k)
            <label class="kand">
                <input type="radio" name="kandidat_id" value="{{ $k['id'] }}" required>
                <span class="no">{{ $k['no_urut'] }}</span>
                <span class="im">@if ($k['foto'])<img src="{{ $k['foto'] }}" alt="{{ $k['title'] }}" loading="lazy">@endif</span>
                <span class="bd">
                    <h3 style="font-size:18px;margin:0">{{ $k['title'] }}</h3>
                    <small>{{ $k['kelas'] }}@if ($k['jurusan']) · {{ $k['jurusan'] }}@endif</small>
                    @if ($k['visi'])<p>{{ $k['visi'] }}</p>@endif
                </span>
            </label>
            @endforeach
        </div>
        <p style="margin-top:28px"><button class="btn" type="submit">Kirim pilihan</button></p>
    </form>
    @endif
</section>
@endforeach

@endif

<script>
document.querySelectorAll('.kand input[type=radio]').forEach(function(r){
    r.addEventListener('change',function(){
        document.querySelectorAll('.kand.pilih').forEach(function(k){k.classList.remove('pilih')});
        r.closest('.kand').classList.add('pilih');
        r.closest('.kand').scrollIntoView({behavior:'smooth',block:'nearest'});
    });
});
</script>
@endsection
