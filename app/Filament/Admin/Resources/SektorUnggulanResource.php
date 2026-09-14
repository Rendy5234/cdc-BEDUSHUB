<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SektorUnggulanResource\Pages;
use App\Filament\Admin\Resources\SektorUnggulanResource\RelationManagers;
use App\Models\SektorUnggulan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SektorUnggulanResource extends Resource
{
    protected static ?string $model = SektorUnggulan::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Wilayah';

    protected static ?string $modelLabel = 'Sektor Unggulan';

    protected static ?string $pluralModelLabel = 'Sektor Unggulan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kategori')
                    ->maxLength(255),
                Forms\Components\TextInput::make('kecamatan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ikon')
                    ->helperText('Nama icon Heroicon, contoh: heroicon-o-briefcase')
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('kecamatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('potensi_daerahs_count')
                    ->label('Potensi')
                    ->counts('potensiDaerahs')
                    ->sortable(),
            ])
            ->filters([
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
                fn (SektorUnggulan $record): ?string => $record->trashed()
                    ? null
                    : SektorUnggulanResource::getUrl('edit', ['record' => $record]),
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
            RelationManagers\PotensiDaerahsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSektorUnggulans::route('/'),
            'create' => Pages\CreateSektorUnggulan::route('/create'),
            'edit' => Pages\EditSektorUnggulan::route('/{record}/edit'),
        ];
    }
}
