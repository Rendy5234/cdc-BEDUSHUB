<?php

namespace App\Filament\Admin\Resources\SektorUnggulanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PotensiDaerahsRelationManager extends RelationManager
{
    protected static string $relationship = 'potensiDaerahs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lokasi_kecamatan')
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('peluang_usaha')
                    ->columnSpanFull(),
                Forms\Components\Select::make('skills')
                    ->relationship('skills', 'nama')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('Skill Dibutuhkan')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi_kecamatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->limit(40),
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
