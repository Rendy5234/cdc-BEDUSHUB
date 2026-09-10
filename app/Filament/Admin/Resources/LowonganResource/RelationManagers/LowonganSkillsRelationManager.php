<?php

namespace App\Filament\Admin\Resources\LowonganResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LowonganSkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'lowonganSkills';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('skill_id')
                    ->relationship('skill', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->distinct(),
                Forms\Components\Select::make('level_min')
                    ->options([
                        'pemula' => 'Pemula',
                        'menengah' => 'Menengah',
                        'mahir' => 'Mahir',
                    ]),
            ]);
    }

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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
