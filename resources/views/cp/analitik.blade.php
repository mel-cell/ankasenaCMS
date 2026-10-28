{{--
Halaman analitik standalone (di-embed via iframe di widget dasbor CP, atau dibuka tab sendiri).
Pakai token desain situs + Chart.js. Superadmin only (dijaga controller).
--}}
<!DOCTYPE html>
<html lang="id" class="{{ $dark ? 'dark' : '' }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Analitik · {{ config('statamic.cp.custom_cms_name', 'Ankasena') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--bg:#F4F7F9;--card:#FFFFFF;--ink:#0B2939;--mut:#5A6B77;--line:#DCE5EC;--amber:#F77F00;--soft:#EDF2F6}
html.dark{--bg:#06202F;--card:#0B2939;--ink:#EAF2F7;--mut:#9FB4C2;--line:#12374B;--amber:#F77F00;--soft:#0E3040}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--mut);font:14px/1.55 'Plus Jakarta Sans',system-ui,sans-serif}
.w{max-width:1160px;margin:0 auto;padding:0 22px}
.kick{margin:28px 0 6px;font-size:11.5px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--amber)}
h1{font-family:Fraunces,Georgia,serif;font-size:clamp(24px,3.4vw,32px);color:var(--ink);margin:0 0 4px}
.sub{margin:0 0 20px;font-size:13px}
h2{font-family:Fraunces,Georgia,serif;color:var(--ink);font-size:21px;margin:0;font-weight:600}
h3{color:var(--ink);font-size:14px;margin:0 0 2px;font-weight:600}
.card{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:18px 20px}
.card .cs{margin:2px 0 14px;font-size:12.5px}
.tiles{display:grid;grid-template-columns:repeat(auto-fit,minmax(158px,1fr));gap:12px;margin-bottom:12px}
.tile b{display:block;font-family:Fraunces,Georgia,serif;font-size:28px;line-height:1.2;color:var(--ink)}
.tile span{font-size:12px}
.ev{margin-top:26px}
.evhead{display:flex;flex-wrap:wrap;align-items:baseline;gap:10px;margin-bottom:12px}
.chip{font-size:11px;font-weight:700;border-radius:99px;padding:2px 10px;white-space:nowrap}
.chip.on{background:var(--amber);color:#fff}
.chip.off{border:1px solid var(--line);color:var(--mut)}
.meta{font-size:12.5px}
.meta b{color:var(--ink)}
.charts{display:grid;grid-template-columns:1.3fr 1fr;gap:12px}
@media(max-width:860px){.charts{grid-template-columns:1fr}}
.hbox{height:225px;position:relative}
.dbox{height:225px;max-width:300px;margin:0 auto;position:relative}
.lbox{height:205px;position:relative;margin-top:6px}
table{width:100%;border-collapse:collapse;margin-top:8px}
th,td{text-align:left;padding:6px 8px;border-bottom:1px solid var(--line);font-size:12.5px;font-weight:400}
th{font-size:10.5px;text-transform:uppercase;letter-spacing:.05em;color:var(--mut);opacity:.85}
td:first-child{color:var(--ink)}
.tflex{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px}
details{margin-top:10px}
summary{cursor:pointer;font-size:12.5px;color:var(--amber);font-weight:600}
.empty{padding:26px 0;text-align:center;opacity:.7;font-size:13px}
footer{padding:26px 0 10px;font-size:11.5px;opacity:.6}
</style>
</head>
<body>
<div class="w">
    <p class="kick">Superadmin · Angkasena</p>
    <h1>Analitik</h1>
    <p class="sub">Ringkasan isi situs &amp; pemilihan. Pilihan tiap pemilih dirahasiakan.</p>

    <div class="tiles">
        @foreach ($counts as $c)
            <div class="card tile"><b>{{ $c['n'] }}</b><span>{{ $c['label'] }}</span></div>
        @endforeach
    </div>

    @forelse ($events as $i => $e)
        <div class="ev">
            <div class="evhead">
                <h2>{{ $e['judul'] }}</h2>
                <span class="chip {{ $e['status_on'] ? 'on' : 'off' }}">{{ $e['status'] }}</span>
                <span class="meta">{{ $e['tipe_label'] }} · {{ $e['mulai_teks'] }} — {{ $e['selesai_teks'] }} · <b>{{ $e['total'] }} suara</b></span>
            </div>

            <div class="charts">
                <div class="card">
                    <h3>Suara per kandidat</h3>
                    <p class="cs">Hover bar untuk nilai persis</p>
                    @if ($e['total'])
                        <div class="hbox"><canvas id="bar-{{ $i }}"></canvas></div>
                    @else
                        <p class="empty">Belum ada suara masuk.</p>
                    @endif
                </div>
                <div class="card">
                    <h3>Persebaran per jurusan</h3>
                    <p class="cs">5 teratas + lainnya</p>
                    @if ($e['total'])
                        <div class="dbox"><canvas id="don-{{ $i }}"></canvas></div>
                    @else
                        <p class="empty">Belum ada suara masuk.</p>
                    @endif
                </div>
            </div>

            <div class="card" style="margin-top:12px">
                <h3>Suara masuk per hari</h3>
                <p class="cs">14 hari terakhir</p>
                @if ($e['total'])
                    <div class="lbox"><canvas id="lin-{{ $i }}"></canvas></div>
                @else
                    <p class="empty">Belum ada suara masuk.</p>
                @endif

                @if ($e['total'])
                    <details>
                        <summary>Tabel data</summary>
                        <div class="tflex">
                            <table><tr><th>Kandidat</th><th>Suara</th></tr>
                                @foreach (array_combine($e['kandidat']['labels'], $e['kandidat']['suara']) as $k => $n)
                                    <tr><td>{{ $k }}</td><td>{{ $n }}</td></tr>
                                @endforeach
                            </table>
                            <table><tr><th>Jurusan</th><th>Suara</th></tr>
                                @foreach (array_combine($e['jurusan']['labels'], $e['jurusan']['suara']) as $k => $n)
                                    <tr><td>{{ $k }}</td><td>{{ $n }}</td></tr>
                                @endforeach
                            </table>
                        </div>
                    </details>
                @endif
            </div>
        </div>
    @empty
        <div class="card ev"><p class="empty">Belum ada event Pemilu — buat lewat CP → Koleksi → Pemilu.</p></div>
    @endforelse

    <div class="ev card">
        <h3>Pengguna terdaftar</h3>
        <p class="cs">Profil pemilih — tanpa pilihan kandidat (suara rahasia).</p>
        <table>
            <tr><th>Nama</th><th>Email</th><th>Kelas</th><th>Jurusan</th><th>Role</th></tr>
            @foreach ($users as $u)
                <tr>
                    <td>{{ $u['nama'] }}</td>
                    <td>{{ $u['email'] }}</td>
                    <td>{{ $u['kelas'] ?: '—' }}</td>
                    <td>{{ $u['jurusan'] ?: '—' }}</td>
                    <td>{{ $u['role'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <footer>Angkasena · diperbarui setiap halaman dibuka</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
<script>
(function () {
    var cs = getComputedStyle(document.documentElement);
    function c(n) { return cs.getPropertyValue(n).trim(); }

    Chart.defaults.color = c('--mut');
    Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";

    var C = {
        series: [c('--s1'), c('--s2'), c('--s3'), c('--s4'), c('--s5')],
        other: c('--s0') || '#898781',
        bar: c('--amber'),
        line: c('--s1'),
        fill: c('--s1') + '22',
        grid: c('--line'),
        border: c('--card')
    };

    @json($events).forEach(function (e, i) {
        if (!e.total) return;

        new Chart('bar-' + i, {
            type: 'bar',
            data: { labels: e.kandidat.labels, datasets: [{ data: e.kandidat.suara, backgroundColor: C.bar, borderRadius: 6, maxBarThickness: 42 }] },
            options: {
                indexAxis: 'y', maintainAspectRatio: false, responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: C.grid } }, y: { grid: { display: false } } }
            }
        });

        var colors = e.jurusan.labels.map(function (l, j) { return (j >= 5 || l === 'Lainnya') ? C.other : C.series[j]; });
        new Chart('don-' + i, {
            type: 'doughnut',
            data: { labels: e.jurusan.labels, datasets: [{ data: e.jurusan.suara, backgroundColor: colors, borderColor: C.border, borderWidth: 2 }] },
            options: {
                maintainAspectRatio: false, responsive: true, cutout: '62%',
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 11, boxHeight: 11, padding: 10 } } }
            }
        });

        new Chart('lin-' + i, {
            type: 'line',
            data: { labels: e.harian.labels, datasets: [{ data: e.harian.suara, borderColor: C.line, backgroundColor: C.fill, fill: true, tension: .35, pointRadius: 2.5, pointHoverRadius: 5, borderWidth: 2 }] },
            options: {
                maintainAspectRatio: false, responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { ticks: { maxTicksLimit: 7 }, grid: { display: false } }, y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: C.grid } } }
            }
        });
    });
})();
</script>
</body>
</html>
