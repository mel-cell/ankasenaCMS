<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;

use App\Filament\Resources\ElectionResource\Pages;
use App\Filament\Resources\ElectionResource\RelationManagers;
use App\Models\Election;
use Filament\Forms\Components\DateTimePicker;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ElectionResource extends Resource
{
    protected static ?string $model = Election::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-check-badge';

    protected static string | UnitEnum | null $navigationGroup = 'Pemilu';

    protected static ?string $navigationLabel = 'Pemilu';

    protected static ?string $modelLabel = 'pemilihan';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pemilihan')->columns(2)->schema([
                TextInput::make('title')->label('Judul pemilihan')->required()->columnSpanFull(),
                Select::make('tipe')->options(['osis' => 'Ketua OSIS', 'mpk' => 'Ketua MPK'])->default('osis')->required(),
                Toggle::make('published')->label('Tampil')->default(true),
                DateTimePicker::make('mulai')->label('Waktu mulai')->required()->seconds(false),
                DateTimePicker::make('selesai')->label('Waktu selesai')->required()->seconds(false),
                FileUpload::make('banner_path')->label('Banner')->disk('public')->directory('uploads')->image()->imageEditor()->columnSpanFull(),
                Textarea::make('keterangan')->helperText('Aturan singkat yang tampil di halaman /vote.')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('mulai', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('tipe')->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'mpk' ? 'Ketua MPK' : 'Ketua OSIS'),
                Tables\Columns\TextColumn::make('mulai')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('selesai')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('status_label')->label('Status')->badge()
                    ->formatStateUsing(fn ($record) => ['belum' => 'Belum dibuka', 'aktif' => 'Berlangsung', 'selesai' => 'Selesai'][$record->status()])
                    ->color(fn ($record) => ['belum' => 'gray', 'aktif' => 'success', 'selesai' => 'danger'][$record->status()]),
                Tables\Columns\TextColumn::make('candidates_count')->counts('candidates')->label('Kandidat'),
            ])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SuaraMasukRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListElections::route('/'),
            'create' => Pages\CreateElection::route('/create'),
            'edit' => Pages\EditElection::route('/{record}/edit'),
        ];
    }
}
