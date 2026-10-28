<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Candidate;
use App\Models\Division;
use App\Models\Election;
use App\Models\Official;
use App\Models\Suggestion;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;

/**
 * Halaman analitik standalone (/analitik-cp), superadmin only —
 * di-embed iframe di dashboard Filament. Chart dirender Chart.js di blade.
 */
class AnalitikController extends Controller
{
    public function cp()
    {
        $user = auth()->user();
        abort_unless($user && $user->isSuper(), 404);

        $events = Election::published()->orderByDesc('selesai')->get()
            ->map(fn ($e) => $this->eventData($e))->values()->all();

        $users = User::orderBy('email')->get()->map(function ($u) {
            return [
                'nama' => $u->nama_lengkap ?: ($u->name ?: '—'),
                'email' => $u->email,
                'kelas' => (string) $u->kelas,
                'jurusan' => $u->jurusan?->title ?? '',
                'role' => $u->isSuper() ? 'Superadmin' : ucfirst($u->role),
            ];
        })->all();

        return view('cp.analitik', [
            'title' => 'Analitik',
            'dark' => request()->cookie('mode') === 'dark',
            'counts' => [
                ['label' => 'Pengguna', 'n' => User::count()],
                ['label' => 'Berita', 'n' => Article::count()],
                ['label' => 'Pengurus', 'n' => Official::count()],
                ['label' => 'Sekbid & Divisi', 'n' => Division::count()],
                ['label' => 'Saran masuk', 'n' => Suggestion::count()],
            ],
            'events' => $events,
            'users' => $users,
        ]);
    }

    private function eventData(Election $e): array
    {
        $votes = Vote::where('pemilu_id', (string) $e->id)->get();
        $total = $votes->count();

        $kandidat = $e->candidates()->orderBy('no_urut')->get()
            ->map(fn ($k) => [
                'label' => $k->no_urut.'. '.$k->title,
                'suara' => $votes->where('kandidat_id', (string) $k->id)->count(),
            ])->sortByDesc('suara')->values();

        // Donut jurusan: top 5 + "Lainnya".
        $perJurusan = [];
        foreach ($votes as $v) {
            $nama = User::find($v->user_id)?->jurusan?->title ?: 'Profil tidak diisi';
            $perJurusan[$nama] = ($perJurusan[$nama] ?? 0) + 1;
        }
        arsort($perJurusan);
        $top = array_slice($perJurusan, 0, 5, true);
        if (($sisanya = $total - array_sum($top)) > 0) {
            $top['Lainnya'] = $sisanya;
        }

        // Line harian 14 hari.
        $perHari = $votes->groupBy(fn ($v) => $v->created_at?->format('Y-m-d'))->map->count();
        $harian = ['labels' => [], 'suara' => []];
        for ($d = 13; $d >= 0; $d--) {
            $hari = Carbon::today()->subDays($d);
            $harian['labels'][] = $hari->format('j/n');
            $harian['suara'][] = $perHari[$hari->format('Y-m-d')] ?? 0;
        }

        return [
            'judul' => $e->title,
            'tipe_label' => $e->tipe === 'mpk' ? 'Ketua MPK' : 'Ketua OSIS',
            'status' => ['belum' => 'Belum dibuka', 'aktif' => 'Berlangsung', 'selesai' => 'Selesai'][$e->status()],
            'status_on' => $e->status() === 'aktif',
            'mulai_teks' => $e->mulai->locale('id')->translatedFormat('j M Y · H.i'),
            'selesai_teks' => $e->selesai->locale('id')->translatedFormat('j M Y · H.i'),
            'total' => $total,
            'kandidat' => ['labels' => $kandidat->pluck('label')->all(), 'suara' => $kandidat->pluck('suara')->all()],
            'jurusan' => ['labels' => array_keys($top), 'suara' => array_values($top)],
            'harian' => $harian,
        ];
    }
}
