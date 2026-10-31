<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Media extends Page
{
    use WithFileUploads;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    protected static string | UnitEnum | null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Media';

    protected static ?int $navigationSort = 2;

    public $file;

    public array $filters = ['q' => '', 'tipe' => 'semua'];

    protected string $view = 'filament.pages.media';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    /** @return array<int, array{path:string,url:string,ext:string,size:int,modified:int}> */
    public function files(): array
    {
        $disk = Storage::disk('public');
        $q = mb_strtolower(trim($this->filters['q']));
        $tipe = $this->filters['tipe'];

        // Hanya isi uploads/ — jangan scan root disk (dotfile, dsb.).
        return collect($disk->allFiles('uploads'))
            ->filter(fn (string $p) => str_contains(mb_strtolower($p), $q))
            ->map(fn (string $p) => [
                'path' => $p,
                'url' => $disk->url($p),
                'ext' => strtolower(pathinfo($p, PATHINFO_EXTENSION)),
                'size' => $disk->size($p),
                'modified' => $disk->lastModified($p),
            ])
            ->filter(function (array $f) use ($tipe) {
                if ($tipe === 'semua') return true;
                return $tipe === 'gambar' ? in_array($f['ext'], ['png','jpg','jpeg','gif','webp','svg','avif']) : ! in_array($f['ext'], ['png','jpg','jpeg','gif','webp','svg','avif']);
            })
            ->sortByDesc('modified')
            ->values()
            ->all();
    }

    public function updatedFile(): void
    {
        $this->validate(['file' => 'file|max:10240']);

        $nama = $this->file->getClientOriginalName();
        $this->file->storeAs('uploads', $nama, 'public');
        $this->reset('file');

        Notification::make()->title('Berkas terunggah: '.$nama)->success()->send();
    }

    public function hapus(string $path): void
    {
        Storage::disk('public')->delete($path);
        Notification::make()->title('Berkas dihapus.')->success()->send();
    }
}
