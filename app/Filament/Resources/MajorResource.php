<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;

use App\Filament\Resources\MajorResource\Pages;
use App\Models\Major;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MajorResource extends Resource
{
    protected static ?string $model = Major::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string | UnitEnum | null $navigationGroup = 'Organisasi';

    protected static ?string $navigationLabel = 'Jurusan';

    protected static ?string $modelLabel = 'jurusan';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Jurusan')->columns(2)->schema([
                TextInput::make('title')->label('Nama jurusan')->required(),
                TextInput::make('singkatan')->label('Singkatan')->placeholder('mis. RPL, TKR, DKV'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Nama jurusan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('singkatan')->badge()->color('gray')->placeholder('—'),
                Tables\Columns\TextColumn::make('candidates_count')->counts('candidates')->label('Kandidat'),
            ])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMajors::route('/'),
        ];
    }
}
