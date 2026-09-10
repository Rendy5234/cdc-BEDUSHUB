<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TracerStudyResource\Pages;
use App\Models\TracerStudy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TracerStudyResource extends Resource
{
    protected static ?string $model = TracerStudy::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Monitoring';

    protected static ?string $modelLabel = 'Tracer Study';

    protected static ?string $pluralModelLabel = 'Tracer Study';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
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
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Alumni')
                    ->searchable()
                    ->sortable(),
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
            ->filters([
                Tables\Filters\SelectFilter::make('status_pekerjaan')
                    ->options([
                        'bekerja' => 'Bekerja',
                        'wiraswasta' => 'Wiraswasta',
                        'melanjutkan_studi' => 'Melanjutkan Studi',
                        'belum_bekerja' => 'Belum Bekerja',
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
