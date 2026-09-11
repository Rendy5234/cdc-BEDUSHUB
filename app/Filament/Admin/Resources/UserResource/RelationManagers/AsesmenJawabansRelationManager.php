<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AsesmenJawabansRelationManager extends RelationManager
{
    protected static string $relationship = 'asesmenJawabans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asesmen_id')
                    ->relationship('asesmen', 'judul')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('soal_id')
                    ->relationship('soal', 'pertanyaan')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => Str::limit($record->pertanyaan, 80)),
                Forms\Components\Textarea::make('jawaban')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('skor')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('jawaban')
            ->columns([
                Tables\Columns\TextColumn::make('asesmen.judul')
                    ->label('Asesmen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('soal.pertanyaan')
                    ->label('Soal')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->soal?->pertanyaan),
                Tables\Columns\TextColumn::make('jawaban')
                    ->limit(50),
                Tables\Columns\TextColumn::make('skor')
                    ->numeric()
                    ->sortable(),
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
