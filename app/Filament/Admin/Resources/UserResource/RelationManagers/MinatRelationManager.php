<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MinatRelationManager extends RelationManager
{
    protected static string $relationship = 'userMinat';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kategori_minat_id')
            ->columns([
                Tables\Columns\TextColumn::make('kategoriMinat.nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategoriMinat.deskripsi')
                    ->limit(60),
            ])
            ->filters([
                //
            ]);
    }
}
