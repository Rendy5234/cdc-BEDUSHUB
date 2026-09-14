<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AsesmenJawabansRelationManager extends RelationManager
{
    protected static string $relationship = 'asesmenJawabans';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('jawaban')
            ->columns([
                Tables\Columns\TextColumn::make('asesmen.judul')
                    ->label('Asesmen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('soal.pertanyaan')
                    ->label('Soal')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->soal?->pertanyaan),
                Tables\Columns\TextColumn::make('jawaban')
                    ->limit(50),
                Tables\Columns\TextColumn::make('skor')
                    ->numeric()
                    ->sortable(),
            ]);
    }
}
