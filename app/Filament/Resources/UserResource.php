<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-identification';

    protected static string | UnitEnum | null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'pengguna';

    protected static ?string $recordTitleAttribute = 'email';

    // Hanya superadmin mengelola pengguna/role.
    public static function canViewAny(): bool
    {
        return auth()->user()?->isSuper() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
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
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
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
