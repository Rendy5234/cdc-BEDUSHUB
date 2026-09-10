<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RekomendasiLogResource\Pages;
use App\Models\RekomendasiLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RekomendasiLogResource extends Resource
{
    protected static ?string $model = RekomendasiLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationGroup = 'Monitoring';

    protected static ?string $modelLabel = 'Log Rekomendasi';

    protected static ?string $pluralModelLabel = 'Log Rekomendasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('tipe_item')
                    ->options([
                        'lowongan' => 'Lowongan',
                        'pelatihan' => 'Pelatihan',
                        'prodi' => 'Program Studi',
                        'potensi' => 'Potensi Daerah',
                    ]),
                Forms\Components\TextInput::make('item_id')
                    ->numeric(),
                Forms\Components\TextInput::make('skor')
                    ->numeric(),
                Forms\Components\Textarea::make('alasan')
                    ->columnSpanFull(),
                Forms\Components\Select::make('aksi')
                    ->options([
                        'view' => 'View',
                        'apply' => 'Apply',
                        'daftar' => 'Daftar',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_item')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lowongan' => 'info',
                        'pelatihan' => 'success',
                        'prodi' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('item_id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('skor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'apply' => 'info',
                        'daftar' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('alasan')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->alasan),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe_item')
                    ->options([
                        'lowongan' => 'Lowongan',
                        'pelatihan' => 'Pelatihan',
                        'prodi' => 'Program Studi',
                        'potensi' => 'Potensi Daerah',
                    ]),
                Tables\Filters\SelectFilter::make('aksi')
                    ->options([
                        'view' => 'View',
                        'apply' => 'Apply',
                        'daftar' => 'Daftar',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekomendasiLogs::route('/'),
        ];
    }
}
