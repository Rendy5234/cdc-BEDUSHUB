<?php

namespace App\Filament\Perusahaan\Resources\LamaranResource\RelationManagers;

use App\Filament\Perusahaan\Resources\PelamarResource;
use App\Models\Lamaran;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiterimaRelationManager extends RelationManager
{
    protected static string $relationship = 'lamarans';

    protected static ?string $title = 'Diterima';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'diterima'))
            ->recordUrl(fn (Lamaran $record): string => PelamarResource::getUrl('view', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelamar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('catatan')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->catatan),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar Pada')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}