<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;

use App\Filament\Resources\OfficialResource\Pages;
use App\Models\Official;
use Filament\Forms\Components\FileUpload;
use Filament\Actions;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OfficialResource extends Resource
{
    protected static ?string $model = Official::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static string | UnitEnum | null $navigationGroup = 'Organisasi';

    protected static ?string $navigationLabel = 'Kabinet';

    protected static ?string $modelLabel = 'pengurus';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pengurus')->columns(2)->schema([
                TextInput::make('nama')->required(),
                Select::make('jabatan')->options([
                    'Ketua', 'Wakil Ketua', 'Sekretaris I', 'Sekretaris II',
                    'Bendahara I', 'Bendahara II', 'Koordinator Divisi', 'Anggota',
                ])->required(),
                Select::make('divisi')->options([
                    'Kepemimpinan', 'Sekretariat', 'Keuangan', 'Acara & Event', 'Humas & Media',
                    'Kreatif & Desain', 'Olahraga', 'Keagamaan', 'Sosial & Lingkungan',
                ]),
                TextInput::make('kelas')->placeholder('mis. XI RPL 1'),
                TextInput::make('angkatan'),
                FileUpload::make('foto_path')->label('Foto')->disk('public')->directory('uploads')->image()->imageEditor()->columnSpanFull(),
                Textarea::make('bio')->maxLength(300)->columnSpanFull(),
                TextInput::make('instagram')->label('Instagram (tanpa @)'),
                TextInput::make('urutan')->label('Urutan')->numeric()->default(100)->helperText('Angka kecil tampil lebih dulu.'),
                TextInput::make('periode')->default('2025/2026'),
                Toggle::make('aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('urutan')
            ->columns([
                Tables\Columns\ImageColumn::make('foto_path')->label('Foto')->disk('public')->circular(),
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jabatan'),
                Tables\Columns\TextColumn::make('divisi')->badge()->color('gray')->placeholder('—'),
                Tables\Columns\TextColumn::make('kelas')->placeholder('—'),
                Tables\Columns\ToggleColumn::make('aktif')->label('Aktif'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jabatan'),
                Tables\Filters\SelectFilter::make('divisi'),
            ])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOfficials::route('/'),
            'create' => Pages\CreateOfficial::route('/create'),
            'edit' => Pages\EditOfficial::route('/{record}/edit'),
        ];
    }
}
