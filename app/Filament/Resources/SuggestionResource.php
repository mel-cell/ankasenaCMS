<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;

use App\Filament\Resources\SuggestionResource\Pages;
use App\Models\Suggestion;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SuggestionResource extends Resource
{
    protected static ?string $model = Suggestion::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static string | UnitEnum | null $navigationGroup = 'Organisasi';

    protected static ?string $navigationLabel = 'Kotak Saran';

    protected static ?string $modelLabel = 'saran';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([]); // readonly — dikelola dari tabel
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nama')->placeholder('Anonim'),
                Tables\Columns\TextColumn::make('pesan')->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label('Masuk')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->actions([Actions\DeleteAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSuggestions::route('/'),
        ];
    }
}
