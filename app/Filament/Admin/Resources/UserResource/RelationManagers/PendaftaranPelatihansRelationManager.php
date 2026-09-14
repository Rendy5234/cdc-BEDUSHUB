<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PendaftaranPelatihansRelationManager extends RelationManager
{
    protected static string $relationship = 'pendaftaranPelatihan';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pelatihan_id')
            ->columns([
                Tables\Columns\TextColumn::make('pelatihan.judul')
                    ->label('Pelatihan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        'selesai' => 'info',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'terdaftar' => 'Terdaftar',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ]),
            ]);
    }
}
