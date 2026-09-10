<?php

namespace App\Filament\Siswa\Resources;

use App\Filament\Siswa\Resources\AsesmenResource\Pages;
use App\Models\Asesmen;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AsesmenResource extends Resource
{
    protected static ?string $model = Asesmen::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Asesmen';

    protected static ?string $modelLabel = 'Asesmen';

    protected static ?string $pluralModelLabel = 'Asesmen';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'minat' => 'info',
                        'bakat' => 'success',
                        'skill' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->limit(60)
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesmen_soals_count')
                    ->label('Jumlah Soal')
                    ->counts('asesmenSoals')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'minat' => 'Minat',
                        'bakat' => 'Bakat',
                        'skill' => 'Skill',
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
            'index' => Pages\ListAsesmens::route('/'),
        ];
    }
}
