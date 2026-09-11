<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Jenjang;
use App\Filament\Admin\Resources\ProgramStudiResource\Pages;
use App\Models\ProgramStudi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProgramStudiResource extends Resource
{
    protected static ?string $model = ProgramStudi::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $modelLabel = 'Program Studi';

    protected static ?string $pluralModelLabel = 'Program Studi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('fakultas_id')
                    ->relationship('fakultas', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kode')
                    ->maxLength(255),
                Forms\Components\Select::make('jenjang')
                    ->options(Jenjang::tertiaryLabels())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenjang')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? (Jenjang::tryFrom($state)?->getLabel() ?? $state) : '-')
                    ->color(fn (string $state): string => match ($state) {
                        's1' => 'success',
                        's2' => 'warning',
                        's3' => 'danger',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('fakultas.nama')
                    ->label('Fakultas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fakultas.institusi.nama')
                    ->label('Institusi')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenjang')
                    ->options(Jenjang::tertiaryLabels()),
                Tables\Filters\SelectFilter::make('fakultas_id')
                    ->relationship('fakultas', 'nama')
                    ->label('Fakultas')
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
                fn (ProgramStudi $record): ?string => $record->trashed()
                    ? null
                    : ProgramStudiResource::getUrl('edit', ['record' => $record]),
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
            'index' => Pages\ListProgramStudis::route('/'),
            'create' => Pages\CreateProgramStudi::route('/create'),
            'edit' => Pages\EditProgramStudi::route('/{record}/edit'),
        ];
    }
}
