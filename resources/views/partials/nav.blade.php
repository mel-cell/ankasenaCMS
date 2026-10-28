<script>
(function(){var d=document.documentElement;d.dataset.mode=localStorage.getItem('mode')||'light';d.dataset.lang=localStorage.getItem('lang')||'id';})();
</script>
<svg width="0" height="0" style="position:absolute" aria-hidden="true"><symbol id="m" viewBox="0 0 20 20"><path d="M10 0v20M0 10h20M2.5 2.5l15 15M17.5 2.5l-15 15"/></symbol></svg>
@php($s = \App\Support\Situs::class)
@php($divisions = \App\Models\Division::query()->published()->orderBy('title')->get())
<nav>
    <a class="brand" href="/">@if ($logo = $s::img($s::get('logo')))<img src="{{ $logo }}" alt="{{ $s::get('nama_organisasi') }}" style="height:28px;width:auto">@else<svg width="20" height="20" fill="none" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><use href="#m"/></svg>@endif {{ $s::get('nama_organisasi') }}</a>
    <div class="menu">
        <a href="/tentang"{{ request()->is('tentang') ? ' class="on"' : '' }} data-i18n="nav.tentang">Tentang</a>
        <div class="dd">
            <a href="/kabinet" class="dd-t{{ request()->is('kabinet', 'kabinet/*') ? ' on' : '' }}">Sekbid &amp; Divisi <svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" aria-hidden="true"><path d="M2 3.5l3 3 3-3"/></svg></a>
            <div class="dd-m">
                @foreach (['Sekbid' => 'Sekbid OSIS', 'Divisi' => 'Divisi MPK'] as $jenis => $judul)
                    <div class="dd-c">
                        <p class="dd-h">{{ $judul }}</p>
                        @foreach ($divisions->where('jenis', $jenis) as $d)
                            <a class="dd-i" href="/divisi/{{ $d->slug }}">
                                <span class="ic">@if ($d->ikon_path)<img src="{{ $s::img($d->ikon_path) }}" alt="">@else<svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M7 1v12M1 7h12M3 3l8 8M11 3l-8 8"/></svg>@endif</span>
                                <span><i>{{ $d->title }}</i>@if ($d->deskripsi)<em>{{ $d->deskripsi }}</em>@endif</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        <a href="/blog"{{ request()->is('blog') ? ' class="on"' : '' }} data-i18n="nav.berita">Berita</a>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        <div class="dd dd-lang">
            <button class="tgm" type="button" aria-haspopup="true" aria-label="Bahasa"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.7 3.8 9S14.5 18.4 12 21c-2.5-2.6-3.8-5.7-3.8-9S9.5 5.6 12 3z"/></svg></button>
            <div class="dd-m">
                <button class="dd-i dd-lang-i" type="button" data-lang="id">Bahasa Indonesia</button>
                <button class="dd-i dd-lang-i" type="button" data-lang="en">English</button>
            </div>
        </div>
        <button class="tgm" id="tg-mode" type="button" aria-label="Ganti mode terang/gelap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
        </button>
        <a class="pill" href="/kontak" data-i18n="nav.kontak">Hubungi kami <span class="arr"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9L9 3M9 3H4.5M9 3v4.5"/></svg></span></a>
    </div>
</nav>
<script>
(function(){var t=document.querySelector('.top');if(!t)return;var u=function(){t.classList.toggle('on',window.scrollY>8)};window.addEventListener('scroll',u,{passive:true});u()})();
/* dropdown di mobile: tap trigger = buka/tutup (desktop tetap hover) */
(function(){var m=matchMedia('(max-width:760px)');
document.querySelectorAll('.dd').forEach(function(dd){
    var t=dd.querySelector('.dd-t');if(!t)return;
    t.setAttribute('aria-expanded','false');
    t.addEventListener('click',function(e){if(!m.matches)return;e.preventDefault();
        var o=dd.classList.toggle('open');t.setAttribute('aria-expanded',o?'true':'false')});
    document.addEventListener('click',function(e){if(m.matches&&!dd.contains(e.target))dd.classList.remove('open')});
})})();
</script>
