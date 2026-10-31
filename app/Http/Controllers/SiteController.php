<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Division;
use App\Models\Official;
use App\Models\Setting;
use App\Models\Suggestion;
use App\Support\Situs;

class SiteController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'articles' => Article::published()->orderByDesc('date')->limit(3)->get(),
        ]);
    }

    public function twibon()
    {
        $situs = Setting::group('situs');

        abort_unless($situs['tampilkan_twibon'] ?? true, 404);

        return view('pages.twibon', [
            'aktif' => (bool) ($situs['twibon_aktif'] ?? false),
            'pesan' => $situs['pesan_twibon'] ?? 'Twibon sedang disiapkan. Nanti kembali lagi ya!',
            'nama_organisasi' => $situs['nama_organisasi'] ?? 'Ankasena',
            'periode' => $situs['periode'] ?? '',
        ]);
    }

    public function tentang()
    {
        return view('pages.tentang', [
            'title' => 'Tentang',
            'intro' => 'Kenali OSIS MPK — siapa kami, apa yang kami kerjakan, dan untuk siapa.',
        ]);
    }

    public function kontak()
    {
        return view('pages.kontak', [
            'title' => 'Kontak',
            'intro' => 'Ada pertanyaan, usulan, atau mau kerja sama? Hubungi kami.',
        ]);
    }

    public function kabinet()
    {
        return view('pages.kabinet', [
            'title' => 'Sekbid & Divisi',
            'intro' => 'Orang-orang di balik layar program OSIS MPK periode ini.',
            'groups' => Official::where('aktif', true)->orderBy('urutan')->orderBy('nama')
                ->get()->groupBy(fn ($o) => $o->divisi ?: 'Tanpa divisi'),
        ]);
    }

    public function divisi(string $slug)
    {
        $division = Division::published()->where('slug', $slug)->firstOrFail();

        return view('pages.divisi', ['division' => $division]);
    }

    public function blog()
    {
        $filter = request('kategori');

        return view('pages.blog', [
            'title' => 'Berita & kegiatan',
            'filter' => $filter,
            'articles' => Article::published()
                ->when($filter, fn ($q) => $q->where('kategori', $filter))
                ->orderByDesc('date')->get(),
        ]);
    }

    public function artikel(string $slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        return view('pages.artikel', ['article' => $article]);
    }

    public function saran()
    {
        // Honeypot: bot isi field 'description' → anggap sukses, buang diam-diam.
        if (request('description')) {
            return redirect('/#saran');
        }

        $data = request()->validate([
            'nama' => 'nullable|max:100',
            'pesan' => 'required|max:2000',
        ]);

        Suggestion::create($data);

        return redirect('/#saran')->with('saran_ok', true);
    }
}
