<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'userSkills';

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
                Tables\Columns\TextColumn::make('level')
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
