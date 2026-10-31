<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;

use App\Filament\Resources\DivisionResource\Pages;
use App\Models\Division;
use Filament\Forms\Components\FileUpload;
use Filament\Actions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DivisionResource extends Resource
{
    protected static ?string $model = Division::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string | UnitEnum | null $navigationGroup = 'Organisasi';

    protected static ?string $navigationLabel = 'Sekbid & Divisi';

    protected static ?string $modelLabel = 'sekbid/divisi';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Sekbid / Divisi')->columns(2)->schema([
                TextInput::make('title')->label('Nama')->required(),
                Select::make('jenis')->options(['Sekbid' => 'Sekbid (OSIS)', 'Divisi' => 'Divisi (MPK)'])->default('Sekbid')->required(),
                Textarea::make('deskripsi')->maxLength(160)->helperText('Tampil di dropdown navbar dan kartu.')->columnSpanFull(),
                FileUpload::make('ikon_path')->label('Ikon / logo')->disk('public')->directory('uploads')->image()->columnSpanFull(),
                MarkdownEditor::make('konten')->label('Halaman detail')->columnSpanFull(),
                Toggle::make('published')->label('Tampil')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jenis')->badge()
                    ->color(fn (string $state) => $state === 'Sekbid' ? 'warning' : 'info'),
                Tables\Columns\TextColumn::make('deskripsi')->limit(60)->placeholder('—'),
                Tables\Columns\ToggleColumn::make('published')->label('Tampil'),
            ])
            ->filters([Tables\Filters\SelectFilter::make('jenis')])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDivisions::route('/'),
            'create' => Pages\CreateDivision::route('/create'),
            'edit' => Pages\EditDivision::route('/{record}/edit'),
        ];
    }
}
