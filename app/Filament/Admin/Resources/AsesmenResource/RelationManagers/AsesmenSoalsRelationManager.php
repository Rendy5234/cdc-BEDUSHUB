<?php

namespace App\Filament\Admin\Resources\AsesmenResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AsesmenSoalsRelationManager extends RelationManager
{
    protected static string $relationship = 'asesmenSoals';

    protected static ?string $title = 'Asesmen Soal';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('pertanyaan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('tipe_jawaban')
                    ->options([
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'skala' => 'Skala',
                        'teks' => 'Teks',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\KeyValue::make('opsi')
                    ->visible(fn (Forms\Get $get): bool => $get('tipe_jawaban') === 'pilihan_ganda')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pertanyaan')
            ->columns([
                Tables\Columns\TextColumn::make('pertanyaan')
                    ->limit(60)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipe_jawaban')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pilihan_ganda' => 'info',
                        'skala' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
