<?php

namespace App\Filament\Perusahaan\Resources;

use App\Filament\Perusahaan\Resources\LamaranResource\Pages;
use App\Filament\Perusahaan\Resources\LamaranResource\RelationManagers;
use App\Models\Lowongan;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LamaranResource extends Resource
{
    protected static ?string $model = Lowongan::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Karier';

    protected static ?string $modelLabel = 'Lamaran Masuk';

    protected static ?string $pluralModelLabel = 'Lamaran Masuk';

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(fn (Lowongan $record): string => static::getUrl('view', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Lowongan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kuota')
                    ->label('Kuota Pendaftar')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kuota_diterima')
                    ->label('Kuota Diterima')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'aktif' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->default('aktif'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('perusahaan', fn (Builder $query) => $query->where('user_id', auth()->id()));
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LamaransRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLamarans::route('/'),
            'view' => Pages\ViewLowongan::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
