<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AsesmenSoalResource\Pages;
use App\Models\AsesmenSoal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AsesmenSoalResource extends Resource
{
    protected static ?string $model = AsesmenSoal::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Asesmen';

    protected static ?string $modelLabel = 'Soal Asesmen';

    protected static ?string $pluralModelLabel = 'Soal Asesmen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asesmen_id')
                    ->relationship('asesmen', 'judul', fn ($query) => $query->withoutTrashed())
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('pertanyaan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('skill_id')
                    ->label('Skill')
                    ->relationship('skill', 'nama', fn ($query) => $query->withoutTrashed())
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('kategori_minat_id')
                    ->label('Kategori Minat')
                    ->relationship('kategoriMinat', 'nama', fn ($query) => $query->withoutTrashed())
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('tipe_jawaban')
                    ->options([
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'skala' => 'Skala',
                        'teks' => 'Teks',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\KeyValue::make('opsi')
                    ->visible(fn (Forms\Get $get): bool => $get('tipe_jawaban') === 'pilihan_ganda')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pertanyaan')
                    ->limit(60)
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesmen.judul')
                    ->label('Asesmen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_jawaban')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pilihan_ganda' => 'info',
                        'skala' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('skill.nama')
                    ->label('Skill')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('kategoriMinat.nama')
                    ->label('Minat')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('asesmen_id')
                    ->relationship('asesmen', 'judul', fn ($query) => $query->withoutTrashed())
                    ->label('Asesmen')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('tipe_jawaban')
                    ->options([
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'skala' => 'Skala',
                        'teks' => 'Teks',
                    ]),
                Tables\Filters\SelectFilter::make('skill_id')
                    ->relationship('skill', 'nama', fn ($query) => $query->withoutTrashed())
                    ->label('Skill')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('kategori_minat_id')
                    ->relationship('kategoriMinat', 'nama', fn ($query) => $query->withoutTrashed())
                    ->label('Kategori Minat')
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
                fn (AsesmenSoal $record): ?string => $record->trashed()
                    ? null
                    : AsesmenSoalResource::getUrl('edit', ['record' => $record]),
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
            'index' => Pages\ListAsesmenSoals::route('/'),
            'create' => Pages\CreateAsesmenSoal::route('/create'),
            'edit' => Pages\EditAsesmenSoal::route('/{record}/edit'),
        ];
    }
}
