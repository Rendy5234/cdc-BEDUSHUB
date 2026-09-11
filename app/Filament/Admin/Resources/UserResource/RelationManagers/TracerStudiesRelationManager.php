<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TracerStudiesRelationManager extends RelationManager
{
    protected static string $relationship = 'tracerStudies';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status_pekerjaan')
                    ->options([
                        'bekerja' => 'Bekerja',
                        'wiraswasta' => 'Wiraswasta',
                        'melanjutkan_studi' => 'Melanjutkan Studi',
                        'belum_bekerja' => 'Belum Bekerja',
                    ]),
                Forms\Components\TextInput::make('nama_perusahaan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('posisi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('bidang_pekerjaan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('penghasilan')
                    ->maxLength(255),
                Forms\Components\Select::make('relevansi_pekerjaan')
                    ->label('Relevansi Pekerjaan (1-5)')
                    ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5']),
                Forms\Components\Select::make('kepuasan_pendidikan')
                    ->label('Kepuasan Pendidikan (1-5)')
                    ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5']),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_perusahaan')
            ->columns([
                Tables\Columns\TextColumn::make('status_pekerjaan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bekerja' => 'success',
                        'wiraswasta' => 'info',
                        'melanjutkan_studi' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('posisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penghasilan'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pekerjaan')
                    ->options([
                        'bekerja' => 'Bekerja',
                        'wiraswasta' => 'Wiraswasta',
                        'melanjutkan_studi' => 'Melanjutkan Studi',
                        'belum_bekerja' => 'Belum Bekerja',
                    ]),
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
