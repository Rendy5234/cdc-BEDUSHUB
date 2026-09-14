<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Enums\Jenjang;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProfileRelationManager extends RelationManager
{
    protected static string $relationship = 'profile';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->columns([
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->formatStateUsing(fn (?string $state): string => $state === 'l' ? 'Laki-laki' : ($state === 'p' ? 'Perempuan' : '-')),
                Tables\Columns\TextColumn::make('jenjang')
                    ->formatStateUsing(fn (?string $state): string => $state ? (Jenjang::tryFrom($state)?->getLabel() ?? $state) : '-'),
                Tables\Columns\TextColumn::make('institusi.nama'),
                Tables\Columns\TextColumn::make('jurusan.nama'),
                Tables\Columns\TextColumn::make('prodi.nama'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'lulus' ? 'success' : 'gray'),
            ])
            ->filters([
                //
            ]);
    }
}
