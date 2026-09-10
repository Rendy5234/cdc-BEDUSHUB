<?php

namespace App\Filament\Siswa\Resources;

use App\Filament\Siswa\Resources\TracerStudyResource\Pages;
use App\Models\TracerStudy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TracerStudyResource extends Resource
{
    protected static ?string $model = TracerStudy::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Karier & Pelatihan';

    protected static ?string $modelLabel = 'Tracer Study';

    protected static ?string $pluralModelLabel = 'Tracer Study';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
                Forms\Components\Select::make('status_pekerjaan')
                    ->options([
                        'bekerja' => 'Bekerja',
                        'wiraswasta' => 'Wiraswasta',
                        'melanjutkan_studi' => 'Melanjutkan Studi',
                        'belum_bekerja' => 'Belum Bekerja',
                    ]),
                Forms\Components\TextInput::make('nama_perusahaan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('posisi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('bidang_pekerjaan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('penghasilan')
                    ->maxLength(255),
                Forms\Components\Select::make('relevansi_pekerjaan')
                    ->label('Relevansi Pekerjaan (1-5)')
                    ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5']),
                Forms\Components\Select::make('kepuasan_pendidikan')
                    ->label('Kepuasan Pendidikan (1-5)')
                    ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status_pekerjaan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bekerja' => 'success',
                        'wiraswasta' => 'info',
                        'melanjutkan_studi' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('posisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penghasilan'),
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
            'index' => Pages\ListTracerStudies::route('/'),
            'create' => Pages\CreateTracerStudy::route('/create'),
            'edit' => Pages\EditTracerStudy::route('/{record}/edit'),
        ];
    }
}
