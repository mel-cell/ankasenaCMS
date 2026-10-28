<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MajorResource\Pages;
use App\Models\Major;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MajorResource extends Resource
{
    protected static ?string $model = Major::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Jurusan';

    protected static ?string $modelLabel = 'jurusan';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
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
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMajors::route('/'),
        ];
    }
}
