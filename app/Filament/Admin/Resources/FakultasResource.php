<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FakultasResource\Pages;
use App\Models\Fakultas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FakultasResource extends Resource
{
    protected static ?string $model = Fakultas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Fakultas';

    protected static ?string $pluralModelLabel = 'Fakultas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('institusi_id')
                    ->relationship('institusi', 'nama', fn ($query) => $query->withoutTrashed())
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('institusi.nama')
                    ->label('Institusi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program_studis_count')
                    ->label('Jumlah Prodi')
                    ->counts('programStudis')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('institusi_id')
                    ->relationship('institusi', 'nama', fn ($query) => $query->withoutTrashed())
                    ->label('Institusi')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => ! $record->trashed()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => ! $record->trashed()),
                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Hapus Permanen'),
            ])
            ->recordUrl(
                fn (Fakultas $record): ?string => $record->trashed()
                    ? null
                    : FakultasResource::getUrl('edit', ['record' => $record]),
            )
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
            'index' => Pages\ListFakultas::route('/'),
            'create' => Pages\CreateFakultas::route('/create'),
            'edit' => Pages\EditFakultas::route('/{record}/edit'),
        ];
    }
}
