<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TracerStudiesRelationManager extends RelationManager
{
    protected static string $relationship = 'tracerStudies';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_perusahaan')
            ->columns([
                Tables\Columns\TextColumn::make('status_pekerjaan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bekerja' => 'success',
                        'wiraswasta' => 'info',
                        'melanjutkan_studi' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('posisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penghasilan'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pekerjaan')
                    ->options([
                        'bekerja' => 'Bekerja',
                        'wiraswasta' => 'Wiraswasta',
                        'melanjutkan_studi' => 'Melanjutkan Studi',
                        'belum_bekerja' => 'Belum Bekerja',
                    ]),
            ]);
    }
}
