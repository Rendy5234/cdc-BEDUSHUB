<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AsesmenJawabanResource\Pages;
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
                Forms\Components\Select::make('asesmen_id')
                    ->relationship('asesmen', 'judul', fn ($query) => $query->withoutTrashed())
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('soal_id')
                    ->relationship('soal', 'pertanyaan', fn ($query) => $query->withoutTrashed())
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => Str::limit($record->pertanyaan, 80)),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('jawaban')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('asesmen')
                    ->relationship('asesmen', 'judul', fn ($query) => $query->withoutTrashed())
                    ->searchable()
                    ->preload()
                    ->modifyFormFieldUsing(fn (Forms\Components\Select $field) => $field->live()),
                Tables\Filters\SelectFilter::make('soal')
                    ->relationship('soal', 'pertanyaan', fn ($query) => $query->withoutTrashed())
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => Str::limit($record->pertanyaan, 80))
                    ->modifyFormFieldUsing(function (Forms\Components\Select $field) {
                        return $field->relationship(
                            'soal',
                            'pertanyaan',
                            modifyQueryUsing: fn (Builder $query, Forms\Get $get) => $query->where('asesmen_id', $get('../asesmen.value'))->withoutTrashed(),
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        ];
    }
}
