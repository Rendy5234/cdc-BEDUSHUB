<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AsesmenResource\Pages;
use App\Filament\Admin\Resources\AsesmenResource\RelationManagers;
use App\Models\Asesmen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AsesmenResource extends Resource
{
    protected static ?string $model = Asesmen::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Asesmen';

    protected static ?string $modelLabel = 'Asesmen';

    protected static ?string $pluralModelLabel = 'Asesmen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('tipe')
                    ->options([
                        'minat' => 'Minat',
                        'bakat' => 'Bakat',
                        'skill' => 'Skill',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
            ]);
    }

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
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesmen_soals_count')
                    ->label('Jumlah Soal')
                    ->counts('asesmenSoals')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'minat' => 'Minat',
                        'bakat' => 'Bakat',
                        'skill' => 'Skill',
                    ]),
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
            RelationManagers\AsesmenSoalsRelationManager::class,
            RelationManagers\AsesmenJawabansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsesmens::route('/'),
            'create' => Pages\CreateAsesmen::route('/create'),
            'edit' => Pages\EditAsesmen::route('/{record}/edit'),
        ];
    }
}
