<?php

namespace App\Filament\Perusahaan\Resources;

use App\Enums\Jenjang;
use App\Filament\Perusahaan\Resources\LowonganResource\Pages;
use App\Filament\Perusahaan\Resources\LowonganResource\RelationManagers;
use App\Models\Lowongan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LowonganResource extends Resource
{
    protected static ?string $model = Lowongan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Karier';

    protected static ?string $modelLabel = 'Lowongan';

    protected static ?string $pluralModelLabel = 'Lowongan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('perusahaan_id')
                    ->default(fn () => auth()->user()?->perusahaan?->id),
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tipe_pekerjaan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('lokasi')
                    ->maxLength(255)
                    ->columnSpanFull(),
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
                            ->multiple()
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
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('kuota')
                                    ->label('Kuota Pendaftar')
                                    ->numeric()
                                    ->minValue(0),
                                Forms\Components\TextInput::make('kuota_diterima')
                                    ->label('Kuota Diterima')
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
                Forms\Components\Section::make('Kelengkapan Dokumen Lamaran')
                    ->schema([
                        Forms\Components\Toggle::make('butuh_surat_lamaran')
                            ->label('Mewajibkan Surat Lamaran')
                            ->live(),
                        Forms\Components\Toggle::make('butuh_pas_foto')
                            ->label('Mewajibkan Pas Foto')
                            ->live(),
                        Forms\Components\Select::make('rasio_pas_foto')
                            ->label('Rasio Pas Foto')
                            ->options([
                                '3x4' => '3x4',
                                '4x6' => '4x6',
                            ])
                            ->visible(fn (Forms\Get $get): bool => (bool) $get('butuh_pas_foto')),
                    ])
                    ->columns(3),
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
                Tables\Columns\TextColumn::make('tipe_pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kuota')
                    ->alignCenter()
                    // ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('kuota_diterima')
                    ->alignCenter()
                    ->label('Diterima')
                    // ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'aktif' ? 'success' : 'danger'),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('perusahaan', fn (Builder $query) => $query->where('user_id', auth()->id()));
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LowonganSkillsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLowongans::route('/'),
            'create' => Pages\CreateLowongan::route('/create'),
            'edit' => Pages\EditLowongan::route('/{record}/edit'),
        ];
    }
}
