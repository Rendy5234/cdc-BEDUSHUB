<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MinatRelationManager extends RelationManager
{
    protected static string $relationship = 'userMinat';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kategori_minat_id')
                    ->relationship('kategoriMinat', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->distinct(),
            ]);
    }

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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
