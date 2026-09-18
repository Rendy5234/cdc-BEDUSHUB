<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Jenjang;
use App\Filament\Admin\Resources\LowonganResource\Pages;
use App\Filament\Admin\Resources\LowonganResource\RelationManagers;
use App\Models\Lowongan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LowonganResource extends Resource
{
    protected static ?string $model = Lowongan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Karier & Pelatihan';

    protected static ?string $modelLabel = 'Lowongan';

    protected static ?string $pluralModelLabel = 'Lowongan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('perusahaan_id')
                    ->relationship('perusahaan', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tipe_pekerjaan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('lokasi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('gaji_min')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp'),
                Forms\Components\TextInput::make('gaji_max')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp'),
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_mulai'),
                        Forms\Components\DatePicker::make('tanggal_berakhir'),
                        Forms\Components\Select::make('pendidikan')
                            ->label('Pendidikan (Jenjang)')
                            ->options(Jenjang::labels())
                            ->searchable(),
                    ]),
                Forms\Components\Select::make('minat')
                    ->relationship('minat', 'nama')
                    ->multiple()
                    ->preload()
                    ->label('Kategori Minat')
                    ->columnSpanFull(),
                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kuota')
                                    ->numeric()
                                    ->minValue(0),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'nonaktif' => 'Nonaktif',
                                    ])
                                    ->default('aktif')
                                    ->required(),
                            ])
                            ->columnSpan(2),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->image()
                            ->directory('lowongan')
                            ->columnSpan(2),
                    ]),
                // Forms\Components\Select::make('status')
                //     ->options([
                //         'aktif' => 'Aktif',
                //         'nonaktif' => 'Nonaktif',
                //     ])
                //     ->default('aktif')
                //     ->required(),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('kualifikasi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('benefit')
                    ->label('Benefit')
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
                Tables\Columns\TextColumn::make('perusahaan.nama')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => $state === 'aktif' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ]),
                Tables\Filters\SelectFilter::make('perusahaan_id')
                    ->relationship('perusahaan', 'nama')
                    ->label('Perusahaan')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LowonganSkillsRelationManager::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canForceDelete(Model $record): bool
    {
        return false;
    }

    public static function canForceDeleteAny(): bool
    {
        return false;
    }

    public static function canRestore(Model $record): bool
    {
        return false;
    }

    public static function canRestoreAny(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLowongans::route('/'),
            'view' => Pages\ViewLowongan::route('/{record}'),
        ];
    }
}
