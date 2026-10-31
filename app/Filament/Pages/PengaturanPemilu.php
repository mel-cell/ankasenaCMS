<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;

use App\Models\Setting;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class PengaturanPemilu extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string | UnitEnum | null $navigationGroup = 'Pemilu';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?int $navigationSort = 9;

    protected static ?string $title = 'Pengaturan Pemilu';

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
                Section::make('Pemilu')->columns(2)->schema([
                    Toggle::make('tampilkan_pemilu')->label('Menu Pemilu tampil')->helperText('Kalau mati: menu hilang dari navigasi dan halaman /vote jadi 404.'),
                    Toggle::make('voting_open')->label('Voting dibuka')->helperText('Master switch halaman /vote.'),
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
