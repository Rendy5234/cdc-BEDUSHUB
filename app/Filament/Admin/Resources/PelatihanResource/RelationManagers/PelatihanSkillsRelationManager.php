<?php

namespace App\Filament\Admin\Resources\PelatihanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PelatihanSkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'pelatihanSkills';

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
