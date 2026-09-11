<?php

namespace App\Filament\Admin\Resources;

use App\Enums\SkillLevel;
use App\Filament\Admin\Resources\PelatihanResource\Pages;
use App\Filament\Admin\Resources\PelatihanResource\RelationManagers;
use App\Models\Pelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PelatihanResource extends Resource
{
    protected static ?string $model = Pelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationGroup = 'Karier & Pelatihan';

    protected static ?string $modelLabel = 'Pelatihan';

    protected static ?string $pluralModelLabel = 'Pelatihan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('topik')
                    ->maxLength(255),
                Forms\Components\Select::make('level')
                    ->label('Level')
                    ->options(SkillLevel::labels())
                    ->default(SkillLevel::PEMULA->value)
                    ->required(),
                Forms\Components\TextInput::make('instruktur')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_mulai'),
                Forms\Components\DatePicker::make('tanggal_selesai'),
                Forms\Components\TextInput::make('kuota')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\Select::make('minat')
                    ->relationship('minat', 'nama')
                    ->multiple()
                    ->preload()
                    ->label('Kategori Minat'),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft')
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
                Tables\Columns\TextColumn::make('topik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pemula' => 'info',
                        'menengah' => 'warning',
                        'mahir' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state
                        ? (SkillLevel::tryFrom($state)?->getLabel() ?? $state)
                        : '-'),
                Tables\Columns\TextColumn::make('instruktur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kuota')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'published' ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
                Tables\Filters\SelectFilter::make('level')
                    ->options(SkillLevel::labels()),
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
            RelationManagers\PelatihanSkillsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelatihans::route('/'),
            'create' => Pages\CreatePelatihan::route('/create'),
            'edit' => Pages\EditPelatihan::route('/{record}/edit'),
        ];
    }
}
