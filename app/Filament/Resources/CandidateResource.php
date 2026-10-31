<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;

use App\Filament\Resources\CandidateResource\Pages;
use App\Models\Candidate;
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

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user-circle';

    protected static string | UnitEnum | null $navigationGroup = 'Pemilu';

    protected static ?string $navigationLabel = 'Kandidat';

    protected static ?string $modelLabel = 'kandidat';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kandidat')->columns(2)->schema([
                Select::make('election_id')->label('Pemilihan')->relationship('election', 'title')->required()->columnSpanFull(),
                TextInput::make('title')->label('Nama')->required(),
                TextInput::make('kelas')->label('Kelas')->placeholder('mis. XI RPL 1')->required(),
                Select::make('jurusan_id')->label('Jurusan')->relationship('jurusan', 'title')->required(),
                TextInput::make('no_urut')->label('Nomor urut')->numeric()->default(1)->required(),
                Toggle::make('published')->label('Tampil')->default(true),
                FileUpload::make('foto_path')->label('Foto')->disk('public')->directory('uploads')->image()->imageEditor()->columnSpanFull(),
                Textarea::make('visi')->columnSpanFull(),
                Textarea::make('misi')->helperText('Satu poin per baris.')->columnSpanFull(),
                MarkdownEditor::make('content')->label('Profil lengkap (opsional)')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('no_urut')
            ->columns([
                Tables\Columns\ImageColumn::make('foto_path')->label('Foto')->disk('public')->circular()->defaultImageUrl(fn () => null),
                Tables\Columns\TextColumn::make('no_urut')->label('No.')->sortable(),
                Tables\Columns\TextColumn::make('title')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('kelas'),
                Tables\Columns\TextColumn::make('jurusan.title')->label('Jurusan')->placeholder('—'),
                Tables\Columns\TextColumn::make('election.title')->label('Pemilihan')->wrap(),
                Tables\Columns\ToggleColumn::make('published')->label('Tampil'),
            ])
            ->filters([Tables\Filters\SelectFilter::make('election')->relationship('election', 'title')->label('Pemilihan')])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidate::route('/create'),
            'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }
}
