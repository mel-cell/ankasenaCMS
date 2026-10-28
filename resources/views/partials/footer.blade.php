@php($s = \App\Support\Situs::class)
<footer class="w">
    <div class="cols">
        <p>OSIS &amp; MPK sebagai wadah siswa untuk berkarya, berinovasi, dan memimpin.</p>
        <div><b data-i18n="f.menu">Menu</b><a href="/tentang" data-i18n="nav.tentang">Tentang</a><br><a href="/kabinet" data-i18n="nav.divisi">Sekbid &amp; Divisi</a><br><a href="/blog" data-i18n="nav.berita">Berita</a><br><a href="/vote" data-i18n="nav.vote">Pemilihan</a></div>
        <div><b data-i18n="f.lembaga">Lembaga</b>OSIS<br>MPK<br>Sekbid &amp; divisi</div>
        <div><b data-i18n="f.kontak">Kontak</b>@if ($s::get('email')){{ $s::get('email') }}@endif
            @if ($s::get('instagram') || $s::get('youtube') || $s::get('tiktok'))
            <div class="soc">
                @if ($s::get('instagram'))<a href="https://instagram.com/{{ $s::get('instagram') }}" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" stroke="none"/></svg></a>@endif
                @if ($s::get('youtube'))<a href="https://youtube.com/@{{ $s::get('youtube') }}" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21.6 7.2a2.8 2.8 0 0 0-2-2C17.9 4.8 12 4.8 12 4.8s-5.9 0-7.6.4a2.8 2.8 0 0 0-2 2A29 29 0 0 0 2 12a29 29 0 0 0 .4 4.8 2.8 2.8 0 0 0 2 2c1.7.4 7.6.4 7.6.4s5.9 0 7.6-.4a2.8 2.8 0 0 0 2-2A29 29 0 0 0 22 12a29 29 0 0 0-.4-4.8zM10 15.2V8.8L15.5 12z"/></svg></a>@endif
                @if ($s::get('tiktok'))<a href="https://tiktok.com/@{{ $s::get('tiktok') }}" rel="noopener" aria-label="TikTok"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 1 1-1.79-2.46V9.77a5.71 5.71 0 1 0 4.88 5.66V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3a4.28 4.28 0 0 1-3.24-1.48z"/></svg></a>@endif
            </div>
            @endif
        </div>
    </div>
    <div class="word" aria-hidden="true">ANKASENA</div>
    <div class="copy"><span>© {{ now()->year }} Ankasena OSIS &amp; MPK</span><span>Dibuat oleh siswa, untuk siswa</span></div>
</footer>
<script>
(function(){
var T={id:{},en:{"nav.tentang":"About","nav.divisi":"Divisions","nav.berita":"News","nav.vote":"Election","nav.kontak":"Contact us","f.menu":"Links","f.lembaga":"Organizations","f.kontak":"Contact"}};
var els=document.querySelectorAll('[data-i18n]');
els.forEach(function(el){el.dataset.orig=el.textContent});
function apply(l){els.forEach(function(el){var t=T[l]&&T[l][el.dataset.i18n];if(t)el.textContent=t;else if(el.dataset.orig)el.textContent=el.dataset.orig})}
apply(document.documentElement.dataset.lang);
var lg=document.querySelector('.dd-lang'),lb=lg&&lg.querySelector('.tgm');
if(lb){lb.addEventListener('click',function(e){e.stopPropagation();lg.classList.toggle('open')});
document.addEventListener('click',function(){lg.classList.remove('open')});}
document.querySelectorAll('.dd-lang [data-lang]').forEach(function(b){
    b.classList.toggle('on',b.dataset.lang===document.documentElement.dataset.lang);
    b.addEventListener('click',function(e){
        e.stopPropagation();
        document.documentElement.dataset.lang=b.dataset.lang;
        localStorage.setItem('lang',b.dataset.lang);
        document.querySelectorAll('.dd-lang [data-lang]').forEach(function(x){x.classList.toggle('on',x===b)});
        apply(b.dataset.lang);
        lg&&lg.classList.remove('open');
    });
});
var m=document.getElementById('tg-mode');
if(m)m.addEventListener('click',function(){
    var d=document.documentElement,n=d.dataset.mode==='dark'?'light':'dark';
    d.dataset.mode=n;localStorage.setItem('mode',n);
    document.cookie='mode='+n+';path=/;max-age=31536000;samesite=lax';
});
if(!matchMedia('(prefers-reduced-motion: reduce)').matches&&'IntersectionObserver'in window){
    var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{threshold:.12});
    document.querySelectorAll('.about>*,.stat,.focus>*,.post,.saran>*,.sec,.cta h2,.cta .btns,.pagehead>*,.grp,.misi li,.flatgrid>div,.info,.photo').forEach(function(el){el.classList.add('rz');io.observe(el)});
}
})();
</script>
