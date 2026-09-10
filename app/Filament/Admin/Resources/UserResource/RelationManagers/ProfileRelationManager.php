<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProfileRelationManager extends RelationManager
{
    protected static string $relationship = 'profile';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'l' => 'Laki-laki',
                        'p' => 'Perempuan',
                    ]),
                Forms\Components\DatePicker::make('tanggal_lahir'),
                Forms\Components\TextInput::make('domisili_kecamatan')
                    ->maxLength(255),
                Forms\Components\Select::make('jenjang')
                    ->options([
                        'smk' => 'SMK',
                        'd3' => 'D3',
                        's1' => 'S1',
                        'alumni' => 'Alumni',
                    ]),
                Forms\Components\Select::make('institusi_id')
                    ->relationship('institusi', 'nama')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('jurusan_id')
                    ->relationship('jurusan', 'nama')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('prodi_id')
                    ->relationship('prodi', 'nama')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('tahun_lulus')
                    ->numeric()
                    ->minValue(1990)
                    ->maxValue(2100),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'lulus' => 'Lulus',
                    ])
                    ->default('aktif'),
                Forms\Components\TextInput::make('no_telp')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('profiles'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->columns([
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->formatStateUsing(fn (?string $state): string => $state === 'l' ? 'Laki-laki' : ($state === 'p' ? 'Perempuan' : '-')),
                Tables\Columns\TextColumn::make('jenjang'),
                Tables\Columns\TextColumn::make('institusi.nama'),
                Tables\Columns\TextColumn::make('jurusan.nama'),
                Tables\Columns\TextColumn::make('prodi.nama'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'lulus' ? 'success' : 'gray'),
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
