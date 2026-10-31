<script>
(function(){var d=document.documentElement,f=localStorage.getItem('filament:theme:mode');d.dataset.mode=localStorage.getItem('mode')||(f==='dark'?'dark':'light');d.dataset.lang=localStorage.getItem('lang')||'id';})();
</script>
<svg width="0" height="0" style="position:absolute" aria-hidden="true"><symbol id="m" viewBox="0 0 20 20"><path d="M10 0v20M0 10h20M2.5 2.5l15 15M17.5 2.5l-15 15"/></symbol></svg>
@php($s = \App\Support\Situs::class)
@php($divisions = \App\Models\Division::query()->published()->orderBy('title')->get())
@php($berita = \App\Models\Article::published()->orderByDesc('date')->limit(2)->get(['title', 'slug', 'excerpt']))
<nav>
    <a class="brand" href="/">@if ($logo = $s::img($s::get('logo')))<img src="{{ $logo }}" alt="{{ $s::get('nama_organisasi') }}" style="height:28px;width:auto">@else<svg width="20" height="20" fill="none" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><use href="#m"/></svg>@endif {{ $s::get('nama_organisasi') }}</a>
    <div class="menu">
        <a href="/tentang"{{ request()->is('tentang') ? ' class="on"' : '' }} data-i18n="nav.tentang">Tentang</a>
        <div class="dd dd-mega">
            <a href="/kabinet" class="dd-t{{ request()->is('kabinet', 'kabinet/*') ? ' on' : '' }}">Sekbid &amp; Divisi <svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" aria-hidden="true"><path d="M2 3.5l3 3 3-3"/></svg></a>
            <div class="dd-m mega">
                <div class="dd-c">
                    <p class="dd-h">Sekbid OSIS</p>
                    <div class="dd-cols">
                        @foreach ($divisions->where('jenis', 'Sekbid') as $d)
                            <a class="dd-i" href="/divisi/{{ $d->slug }}">
                                <span class="ic">@if ($d->ikon_path)<img src="{{ $s::img($d->ikon_path) }}" alt="">@else<svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M7 1v12M1 7h12M3 3l8 8M11 3l-8 8"/></svg>@endif</span>
                                <i>{{ $d->title }}</i>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="dd-c">
                    <p class="dd-h">Divisi MPK</p>
                    @foreach ($divisions->where('jenis', 'Divisi') as $d)
                        <a class="dd-i" href="/divisi/{{ $d->slug }}">
                            <span class="ic">@if ($d->ikon_path)<img src="{{ $s::img($d->ikon_path) }}" alt="">@else<svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M7 1v12M1 7h12M3 3l8 8M11 3l-8 8"/></svg>@endif</span>
                            <i>{{ $d->title }}</i>
                        </a>
                    @endforeach
                </div>
                <div class="dd-side">
                    <p class="dd-h">Berita terbaru</p>
                    @foreach ($berita as $b)
                        <a class="dd-p" href="/blog/{{ $b->slug }}">
                            <i>{{ $b->title }}</i>
                            @if ($b->excerpt)<em>{{ $b->excerpt }}</em>@endif
                        </a>
                    @endforeach
                    <a class="dd-more" href="/blog">Semua berita <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9L9 3M9 3H4.5M9 3v4.5"/></svg></a>
                </div>
            </div>
        </div>
        @if ($s::get('tampilkan_pemilu', true))
            <a href="/vote"{{ request()->is('vote', 'vote/*') ? ' class="on"' : '' }}>Pemilu</a>
        @endif
        @if ($s::get('tampilkan_twibon', true))
            <a href="/twibon"{{ request()->is('twibon') ? ' class="on"' : '' }}>Twibon</a>
        @endif
        <a href="/blog"{{ request()->is('blog') ? ' class="on"' : '' }} data-i18n="nav.berita">Berita</a>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        <div class="dd dd-lang">
            <button class="tgm" type="button" aria-haspopup="true" aria-label="Bahasa"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.7 3.8 9S14.5 18.4 12 21c-2.5-2.6-3.8-5.7-3.8-9S9.5 5.6 12 3z"/></svg></button>
            <div class="dd-m">
                <button class="dd-i dd-lang-i" type="button" data-lang="id">🇮🇩 ID</button>
                <button class="dd-i dd-lang-i" type="button" data-lang="en">🇬🇧 EN</button>
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
/* dropdown: desktop buka via hover, tutup dengan jeda 0.5s (mouse sempat nyasar);
   mobile: tap trigger = buka/tutup */
(function(){
    var m=matchMedia('(max-width:760px)'), h=matchMedia('(hover:hover) and (pointer:fine)');
    document.querySelectorAll('.dd').forEach(function(dd){
        var t=dd.querySelector('.dd-t'), to;
        if(t)t.setAttribute('aria-expanded','false');
        var buka=function(){clearTimeout(to);dd.classList.add('open');t&&t.setAttribute('aria-expanded','true')};
        var tutup=function(){clearTimeout(to);to=setTimeout(function(){dd.classList.remove('open');t&&t.setAttribute('aria-expanded','false')},500)};
        dd.addEventListener('mouseenter',function(){h.matches&&buka()});
        dd.addEventListener('mouseleave',function(){h.matches&&tutup()});
        dd.addEventListener('focusin',function(){buka()});
        dd.addEventListener('focusout',function(){h.matches?tutup():dd.classList.remove('open')});
        if(t)t.addEventListener('click',function(e){
            if(!m.matches)return;e.preventDefault();e.stopPropagation();
            dd.classList.contains('open')?(dd.classList.remove('open'),t.setAttribute('aria-expanded','false')):buka();
        });
    });
    document.addEventListener('click',function(e){
        document.querySelectorAll('.dd.open').forEach(function(d){if(!d.contains(e.target)){d.classList.remove('open');
            var t=d.querySelector('.dd-t');t&&t.setAttribute('aria-expanded','false')}});
    });
})();
/* Esc: tutup semua dropdown */
document.addEventListener('keydown',function(e){
    if(e.key!=='Escape')return;
    document.querySelectorAll('.dd.open').forEach(function(d){d.classList.remove('open');
        var t=d.querySelector('.dd-t');t&&t.setAttribute('aria-expanded','false')});
    var a=document.activeElement;a&&a.blur&&a.blur();
});
</script>
