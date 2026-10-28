<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Major;
use App\Models\Setting;
use App\Models\Vote;

class VoteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $votingOpen = (bool) Setting::group('situs')['voting_open'] ?? false;

        $events = Election::published()->orderBy('mulai')->get()->map(function ($e) use ($user) {
            return [
                'id' => $e->id,
                'title' => $e->title,
                'tipe_label' => $e->tipe === 'mpk' ? 'Ketua MPK' : 'Ketua OSIS',
                'keterangan' => $e->keterangan,
                'status' => $e->status(),
                'mulai_teks' => $e->mulai->locale('id')->translatedFormat('j M Y · H.i'),
                'voted' => $user && Vote::where('pemilu_id', (string) $e->id)->where('user_id', (string) $user->id)->exists(),
                'kandidat' => $e->candidates()->published()->orderBy('no_urut')->get()->map(fn ($k) => [
                    'id' => $k->id,
                    'title' => $k->title,
                    'kelas' => $k->kelas,
                    'jurusan' => $k->jurusan?->title,
                    'no_urut' => $k->no_urut,
                    'visi' => $k->visi,
                    'foto' => \App\Support\Situs::img($k->foto_path),
                ])->all(),
            ];
        })->all();

        return view('pages.vote', [
            'voting_open' => $votingOpen,
            'guest' => ! $user,
            'profil_lengkap' => $user && $user->nama_lengkap && $user->kelas && $user->jurusan_id,
            'profil' => $user,
            'jurusan_list' => Major::orderBy('title')->get(['id', 'title']),
            'events' => $events,
        ]);
    }

    public function store()
    {
        $user = auth()->user();
        abort_unless($user, 403);

        // Mode profil: tanpa pemilu_id.
        if (! request('pemilu_id')) {
            $data = request()->validate([
                'nama_lengkap' => 'required|max:150',
                'kelas' => 'required|max:50',
                'jurusan' => 'required|exists:majors,id',
            ]);
            $user->update(['nama_lengkap' => $data['nama_lengkap'], 'kelas' => $data['kelas'], 'jurusan_id' => (int) $data['jurusan']]);

            return redirect('/vote')->with('vote_sukses', 'Profil tersimpan. Sekarang pilih kandidatmu.');
        }

        // Mode suara: 1 email 1 suara per pemilihan (dijamin unique DB).
        $data = request()->validate([
            'pemilu_id' => 'required|integer',
            'kandidat_id' => 'required|integer',
        ]);

        $event = Election::published()->findOrFail($data['pemilu_id']);
        $kandidat = Candidate::published()->where('election_id', $event->id)->findOrFail($data['kandidat_id']);

        if (now()->lt($event->mulai) || now()->gt($event->selesai)) {
            return redirect('/vote')->with('vote_error', 'Masa pemilihan belum/udah berakhir.');
        }

        if (! ((bool) Setting::group('situs')['voting_open'] ?? false)) {
            return redirect('/vote')->with('vote_error', 'Masa pemilihan sedang ditutup.');
        }

        if (Vote::where('pemilu_id', (string) $event->id)->where('user_id', (string) $user->id)->exists()) {
            return redirect('/vote')->with('vote_error', 'Kamu sudah memilih di pemilihan ini.');
        }

        try {
            Vote::create([
                'pemilu_id' => (string) $event->id,
                'user_id' => (string) $user->id,
                'kandidat_id' => (string) $kandidat->id,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if (($e->errorInfo[0] ?? null) === '23505') {
                return redirect('/vote')->with('vote_error', 'Kamu sudah memilih di pemilihan ini.');
            }
            throw $e;
        }

        return redirect('/vote')->with('vote_sukses', 'Suaramu tercatat. Terima kasih!');
    }

    public function hasil()
    {
        $events = Election::published()->orderByDesc('selesai')->get()->map(function ($e) {
            $votes = Vote::where('pemilu_id', (string) $e->id)->get();
            $total = $votes->count();

            $perKandidat = $e->candidates()->orderBy('no_urut')->get()->map(function ($k) use ($votes, $total) {
                $suara = $votes->where('kandidat_id', (string) $k->id)->count();

                return [
                    'no_urut' => $k->no_urut,
                    'title' => $k->title,
                    'suara' => $suara,
                    'persen' => $total ? round($suara / $total * 100) : 0,
                ];
            })->sortByDesc('suara')->values();

            $perJurusan = [];
            foreach ($votes as $v) {
                $nama = \App\Models\User::find($v->user_id)?->jurusan?->title ?: 'Profil tidak diisi';
                $perJurusan[$nama] = ($perJurusan[$nama] ?? 0) + 1;
            }
            arsort($perJurusan);

            return [
                'title' => $e->title,
                'tipe_label' => $e->tipe === 'mpk' ? 'Ketua MPK' : 'Ketua OSIS',
                'status_live' => $e->status() === 'aktif',
                'total' => $total,
                'per_kandidat' => $perKandidat,
                'per_jurusan' => $perJurusan,
            ];
        })->all();

        return view('pages.hasil', ['events' => $events]);
    }
}
