<?php

namespace App\Filament\Perusahaan\Resources;

use App\Filament\Perusahaan\Resources\LamaranResource\Pages;
use App\Models\Lamaran;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LamaranResource extends Resource
{
    protected static ?string $model = Lamaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Karier';

    protected static ?string $modelLabel = 'Lamaran Masuk';

    protected static ?string $pluralModelLabel = 'Lamaran Masuk';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lowongan.judul')
                    ->label('Lowongan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelamar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'diproses' => 'Diproses',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]),
                Tables\Columns\TextColumn::make('catatan')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->catatan),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'diproses' => 'Diproses',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]),
                Tables\Filters\SelectFilter::make('lowongan_id')
                    ->relationship('lowongan', 'judul')
                    ->label('Lowongan'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('lowongan', fn (Builder $query) => $query
                ->whereHas('perusahaan', fn (Builder $q) => $q->where('user_id', auth()->id())));
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
            'index' => Pages\ListLamarans::route('/'),
        ];
    }
}
