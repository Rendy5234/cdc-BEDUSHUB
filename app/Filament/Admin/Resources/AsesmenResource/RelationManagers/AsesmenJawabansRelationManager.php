<?php

namespace App\Filament\Admin\Resources\AsesmenResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AsesmenJawabansRelationManager extends RelationManager
{
    protected static string $relationship = 'asesmenJawabans';

    protected static ?string $title = 'Asesmen Jawaban';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('jawaban')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('soal.pertanyaan')
                    ->limit(50),
                Tables\Columns\TextColumn::make('jawaban')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
