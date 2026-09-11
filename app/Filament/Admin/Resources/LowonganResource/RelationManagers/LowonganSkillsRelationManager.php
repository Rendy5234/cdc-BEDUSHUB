<?php

namespace App\Filament\Admin\Resources\LowonganResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LowonganSkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'lowonganSkills';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('skill_id')
            ->columns([
                Tables\Columns\TextColumn::make('skill.nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('skill.kategori')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('level_min')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pemula' => 'info',
                        'menengah' => 'warning',
                        'mahir' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ]);
    }
}
