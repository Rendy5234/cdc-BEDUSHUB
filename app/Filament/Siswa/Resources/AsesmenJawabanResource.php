<?php

namespace App\Filament\Siswa\Resources;

use App\Filament\Siswa\Resources\AsesmenJawabanResource\Pages;
use App\Models\AsesmenJawaban;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
                Forms\Components\Select::make('asesmen_id')
                    ->relationship('asesmen', 'judul')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live(),
                Forms\Components\Select::make('soal_id')
                    ->relationship(
                        'soal',
                        'pertanyaan',
                        fn (Builder $query, Forms\Get $get) => $query->when(
                            $get('asesmen_id'),
                            fn (Builder $q, $id) => $q->where('asesmen_id', $id),
                        ),
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => Str::limit($record->pertanyaan, 80)),
                Forms\Components\Textarea::make('jawaban')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asesmen.judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('soal.pertanyaan')
                    ->label('Soal')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->soal?->pertanyaan),
                Tables\Columns\TextColumn::make('jawaban')
                    ->limit(60),
                Tables\Columns\TextColumn::make('skor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            ->where('user_id', auth()->id());
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
            'create' => Pages\CreateAsesmenJawaban::route('/create'),
            'edit' => Pages\EditAsesmenJawaban::route('/{record}/edit'),
        ];
    }
}
