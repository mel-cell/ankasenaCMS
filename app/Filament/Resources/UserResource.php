<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'pengguna';

    protected static ?string $recordTitleAttribute = 'email';

    // Hanya superadmin mengelola pengguna/role.
    public static function canViewAny(): bool
    {
        return auth()->user()?->isSuper() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Pengguna')->columns(2)->schema([
                TextInput::make('name')->label('Nama'),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('password')->password()->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context) => $context === 'create')
                    ->helperText('Boleh kosong untuk pengguna login Google.'),
                Select::make('role')->options(array_combine(User::ROLES, ['Superadmin', 'OSIS', 'MPK', 'User']))->default('user')->required(),
                TextInput::make('nama_lengkap')->label('Nama lengkap'),
                TextInput::make('kelas')->placeholder('mis. XI RPL 1'),
                Select::make('jurusan_id')->label('Jurusan')->relationship('jurusan', 'title'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('email')
            ->columns([
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama')->placeholder('—'),
                Tables\Columns\TextColumn::make('kelas')->placeholder('—'),
                Tables\Columns\TextColumn::make('jurusan.title')->label('Jurusan')->placeholder('—'),
                Tables\Columns\TextColumn::make('role')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'superadmin' => 'danger',
                        'osis' => 'warning',
                        'mpk' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([Tables\Filters\SelectFilter::make('role')->options(array_combine(User::ROLES, User::ROLES))])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
