<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PotensiDaerahResource\Pages;
use App\Models\PotensiDaerah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PotensiDaerahResource extends Resource
{
    protected static ?string $model = PotensiDaerah::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Wilayah';

    protected static ?string $modelLabel = 'Potensi Daerah';

    protected static ?string $pluralModelLabel = 'Potensi Daerah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sektor_id')
                    ->relationship('sektor', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lokasi_kecamatan')
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('peluang_usaha')
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('kebutuhan_skill')
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
                Tables\Columns\TextColumn::make('sektor.nama')
                    ->label('Sektor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi_kecamatan')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sektor_id')
                    ->relationship('sektor', 'nama')
                    ->label('Sektor'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPotensiDaerahs::route('/'),
            'create' => Pages\CreatePotensiDaerah::route('/create'),
            'edit' => Pages\EditPotensiDaerah::route('/{record}/edit'),
        ];
    }
}
