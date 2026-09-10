<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AsesmenJawabanResource\Pages;
use App\Models\AsesmenJawaban;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AsesmenJawabanResource extends Resource
{
    protected static ?string $model = AsesmenJawaban::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Asesmen';

    protected static ?string $modelLabel = 'Jawaban Asesmen';

    protected static ?string $pluralModelLabel = 'Jawaban Asesmen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asesmen_id')
                    ->relationship('asesmen', 'judul')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('soal_id')
                    ->relationship('soal', 'pertanyaan')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => Str::limit($record->pertanyaan, 80)),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('jawaban')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('skor')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peserta')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asesmen.judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('soal.pertanyaan')
                    ->label('Soal')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->soal?->pertanyaan),
                Tables\Columns\TextColumn::make('jawaban')
                    ->limit(50),
                Tables\Columns\TextColumn::make('skor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('asesmen')
                    ->relationship('asesmen', 'judul'),
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
            'index' => Pages\ListAsesmenJawabans::route('/'),
            'edit' => Pages\EditAsesmenJawaban::route('/{record}/edit'),
        ];
    }
}
