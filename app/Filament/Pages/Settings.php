<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::group('situs'));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas')->columns(2)->schema([
                    TextInput::make('nama_organisasi')->required(),
                    TextInput::make('sekolah'),
                    TextInput::make('periode')->default('2025/2026'),
                    TextInput::make('tagline')->helperText('Satu kalimat pendek untuk hero beranda.'),
                    Textarea::make('deskripsi')->maxLength(240)->columnSpanFull(),
                    TextInput::make('deskripsi_singkat')->label('Deskripsi singkat (panel periode)')->maxLength(160)->columnSpanFull(),
                    FileUpload::make('logo')->disk('public')->directory('uploads')->image(),
                    FileUpload::make('favicon')->disk('public')->directory('uploads')->image(),
                    FileUpload::make('foto_hero')->label('Foto panel periode')->disk('public')->directory('uploads')->image()->imageEditor(),
                    FileUpload::make('logo_osis')->disk('public')->directory('uploads')->image(),
                    FileUpload::make('logo_mpk')->disk('public')->directory('uploads')->image(),
                ]),

                Section::make('Profil organisasi')->columns(2)->schema([
                    Textarea::make('visi')->columnSpanFull(),
                    Repeater::make('misi')->schema([TextInput::make('poin')])->addActionLabel('Tambah misi')->itemLabel(fn (array $state) => $state['poin'] ?? null)->columnSpanFull(),
                    Textarea::make('deskripsi_osis')->maxLength(200),
                    Textarea::make('deskripsi_mpk')->maxLength(200),
                    Repeater::make('statistik')->schema([
                        TextInput::make('angka'), TextInput::make('label'), TextInput::make('deskripsi'),
                    ])->columns(3)->addActionLabel('Tambah statistik')->columnSpanFull(),
                    Repeater::make('nilai')->label('Nilai fokus')->schema([
                        TextInput::make('judul'), TextInput::make('deskripsi'),
                    ])->columns(2)->addActionLabel('Tambah nilai')->columnSpanFull(),
                ]),

                Section::make('Kontak & sosial')->columns(2)->schema([
                    TextInput::make('email')->email(),
                    TextInput::make('whatsapp')->helperText('Format internasional tanpa +, mis. 628123456789.'),
                    TextInput::make('instagram')->helperText('Tanpa @'),
                    TextInput::make('tiktok'),
                    TextInput::make('youtube'),
                    Textarea::make('alamat')->columnSpanFull(),
                ]),

                Section::make('Pemilu & twibon')->columns(2)->schema([
                    Toggle::make('voting_open')->label('Voting dibuka')->helperText('Master switch halaman /vote.'),
                    Toggle::make('twibon_aktif')->label('Twibon aktif'),
                    Textarea::make('pesan_twibon')->helperText('Tampil saat twibon belum aktif.')->columnSpanFull(),
                ]),

                Section::make('Warna & CTA')->columns(2)->schema([
                    TextInput::make('warna_aksen')->helperText('Emas — tombol/chip. Contoh: #FCBF49'),
                    TextInput::make('warna_aksen_2')->helperText('Oranye — hover/dekorasi. Contoh: #F77F00'),
                    TextInput::make('warna_aksen_3')->helperText('Merah — hemat. Contoh: #D62828'),
                    TextInput::make('warna_ink')->helperText('Teks terang di latar gelap. Contoh: #F5EFE0'),
                    TextInput::make('warna_bg')->helperText('Navy brand. Contoh: #003049'),
                    TextInput::make('cta_judul')->label('Judul CTA beranda'),
                    Textarea::make('cta_deskripsi')->maxLength(200)->columnSpanFull(),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        // Merge supaya key lama (mis. path legacy) yang tak ada di form tidak hilang.
        Setting::put('situs', array_merge(Setting::group('situs'), $this->form->getState()));

        Notification::make()->title('Pengaturan tersimpan.')->success()->send();
    }
}
