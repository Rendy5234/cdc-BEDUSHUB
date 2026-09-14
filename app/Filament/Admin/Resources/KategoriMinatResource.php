<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KategoriMinatResource\Pages;
use App\Filament\Admin\Resources\KategoriMinatResource\RelationManagers;
use App\Models\KategoriMinat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KategoriMinatResource extends Resource
{
    protected static ?string $model = KategoriMinat::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Kategori Minat';

    protected static ?string $pluralModelLabel = 'Kategori Minat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
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
                Tables\Columns\TextColumn::make('deskripsi')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                fn (KategoriMinat $record): ?string => $record->trashed()
                    ? null
                    : KategoriMinatResource::getUrl('edit', ['record' => $record]),
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
            RelationManagers\SkillsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoriMinats::route('/'),
            'create' => Pages\CreateKategoriMinat::route('/create'),
            'edit' => Pages\EditKategoriMinat::route('/{record}/edit'),
        ];
    }
}
