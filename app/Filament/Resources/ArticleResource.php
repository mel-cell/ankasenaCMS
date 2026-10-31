<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms\Components\DatePicker;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';

    protected static string | UnitEnum | null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Berita';

    protected static ?string $modelLabel = 'berita';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Berita')->columns(2)->schema([
                TextInput::make('title')->label('Judul')->required()->maxLength(150)->columnSpanFull(),
                Textarea::make('excerpt')->label('Ringkasan')->required()->maxLength(200)->helperText('Tampil di kartu blog dan meta description.')->columnSpanFull(),
                FileUpload::make('cover_path')->label('Foto cover')->disk('public')->directory('uploads')->image()->imageEditor()->required()->columnSpanFull(),
                TextInput::make('kategori')->label('Kategori')->placeholder('mis. workshop, lomba, pengumuman'),
                TextInput::make('penulis')->label('Penulis'),
                DatePicker::make('date')->label('Tanggal')->default(now())->required(),
                MarkdownEditor::make('konten')->label('Isi artikel')->required()->columnSpanFull(),
                Toggle::make('unggulan')->label('Tampilkan di beranda')->default(false)->columnSpan(1),
                Toggle::make('published')->label('Terbit')->default(true)->columnSpan(1),
                TextInput::make('meta_title')->label('SEO: judul')->helperText('Kosongkan untuk pakai judul artikel.')->columnSpan(1),
                Textarea::make('meta_description')->label('SEO: deskripsi')->maxLength(160)->columnSpan(1),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('kategori')->label('Kategori')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('date')->label('Tanggal')->date('d/m/Y')->sortable(),
                Tables\Columns\IconColumn::make('unggulan')->label('Beranda')->boolean(),
                Tables\Columns\ToggleColumn::make('published')->label('Terbit'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')->options(fn () => Article::distinct()->pluck('kategori', 'kategori')->filter()),
            ])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
