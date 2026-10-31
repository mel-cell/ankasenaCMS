<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;

use App\Models\Setting;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class Twibon extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-sparkles';

    protected static string | UnitEnum | null $navigationGroup = 'Twibon';

    protected static ?string $navigationLabel = 'Twibon';

    protected static ?string $title = 'Twibon';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    protected string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::group('situs'));
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                Section::make('Twibon')->columns(2)->schema([
                    Toggle::make('tampilkan_twibon')->label('Menu Twibon tampil')->helperText('Kalau mati: menu hilang dari navigasi dan halaman /twibon jadi 404.'),
                    Toggle::make('twibon_aktif')->label('Twibon aktif'),
                    Textarea::make('pesan_twibon')->helperText('Tampil saat twibon belum aktif.')->columnSpanFull(),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        // Merge supaya key lain yang tak ada di form tidak hilang.
        Setting::put('situs', array_merge(Setting::group('situs'), $this->form->getState()));

        Notification::make()->title('Pengaturan tersimpan.')->success()->send();
    }
}
