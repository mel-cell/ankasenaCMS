<?php

namespace App\Filament\Resources\ElectionResource\RelationManagers;

use Filament\Actions;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SuaraMasukRelationManager extends RelationManager
{
    protected static string $relationship = 'votes';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]); // catatan saja — tak bisa diedit
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->heading('Pemberi suara')
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.nama_lengkap')
                    ->label('Nama')
                    ->formatStateUsing(fn ($record) => $record->user?->nama_lengkap ?: ($record->user?->name ?? '—'))
                    ->searchable(query: fn ($query, $search) => $query->whereHas('user', fn ($q) => $q->where('nama_lengkap', 'ilike', "%{$search}%")->orWhere('name', 'ilike', "%{$search}%")))
                    ->description(fn ($record) => $record->user?->email),
                Tables\Columns\TextColumn::make('user.kelas')->label('Kelas')->placeholder('—')
                    ->description(fn ($record) => $record->user?->jurusan?->singkatan ?: $record->user?->jurusan?->title),
                Tables\Columns\TextColumn::make('kandidat')
                    ->label('Memilih')
                    ->formatStateUsing(fn ($record) => $record->kandidat ? $record->kandidat->no_urut.'. '.$record->kandidat->title : '—')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu memilih')->dateTime('d/m/Y H:i:s')->sortable(),
            ])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }
}
