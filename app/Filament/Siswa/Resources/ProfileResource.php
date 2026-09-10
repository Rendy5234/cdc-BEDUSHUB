<?php

namespace App\Filament\Siswa\Resources;

use App\Filament\Siswa\Resources\ProfileResource\Pages;
use App\Filament\Siswa\Resources\ProfileResource\RelationManagers;
use App\Models\Profile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProfileResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Profil';

    protected static ?string $modelLabel = 'Profil';

    protected static ?string $pluralModelLabel = 'Profil';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
                Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'l' => 'Laki-laki',
                        'p' => 'Perempuan',
                    ]),
                Forms\Components\DatePicker::make('tanggal_lahir'),
                Forms\Components\TextInput::make('domisili_kecamatan')
                    ->maxLength(255),
                Forms\Components\Select::make('jenjang')
                    ->options([
                        'smk' => 'SMK',
                        'd3' => 'D3',
                        's1' => 'S1',
                        'alumni' => 'Alumni',
                    ]),
                Forms\Components\Select::make('institusi_id')
                    ->relationship('institusi', 'nama')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('jurusan_id')
                    ->relationship('jurusan', 'nama')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('prodi_id')
                    ->relationship('prodi', 'nama')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('tahun_lulus')
                    ->numeric()
                    ->minValue(1990)
                    ->maxValue(2100),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'lulus' => 'Lulus',
                    ])
                    ->default('aktif'),
                Forms\Components\TextInput::make('no_telp')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('profiles'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->formatStateUsing(fn (?string $state): string => $state === 'l' ? 'Laki-laki' : ($state === 'p' ? 'Perempuan' : '-')),
                Tables\Columns\TextColumn::make('jenjang'),
                Tables\Columns\TextColumn::make('institusi.nama'),
                Tables\Columns\TextColumn::make('jurusan.nama'),
                Tables\Columns\TextColumn::make('prodi.nama'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'lulus' ? 'success' : 'gray'),
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
            RelationManagers\SkillsRelationManager::class,
            RelationManagers\MinatRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfiles::route('/'),
            'create' => Pages\CreateProfile::route('/create'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }
}
