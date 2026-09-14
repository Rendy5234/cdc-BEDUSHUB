<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->options([
                        User::ROLE_ADMIN => 'Admin',
                        User::ROLE_PERUSAHAAN => 'Perusahaan',
                        User::ROLE_SISWA => 'Siswa',
                        User::ROLE_MAHASISWA => 'Mahasiswa',
                        User::ROLE_ALUMNI => 'Alumni',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                Forms\Components\TextInput::make('confirm_password')
                    ->label('Password Admin')
                    ->helperText('Masukkan password akun Anda untuk menyimpan perubahan.')
                    ->password()
                    ->autocomplete('current-password')
                    ->dehydrated(false)
                    ->visibleOn('edit')
                    ->required()
                    ->rules([
                        fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                            if (! Hash::check($value, auth()->user()->password)) {
                                $fail('Password yang Anda masukkan salah.');
                            }
                        },
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'perusahaan' => 'info',
                        'siswa' => 'success',
                        'mahasiswa' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'perusahaan' => 'Perusahaan',
                        'siswa' => 'Siswa',
                        'mahasiswa' => 'Mahasiswa',
                        'alumni' => 'Alumni',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (User $record): bool => ! $record->trashed()),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn (User $record): bool => ! $record->trashed())
                    ->modalHeading('Konfirmasi hapus')
                    ->modalDescription('Masukkan password akun Anda untuk menghapus data pengguna ini.')
                    ->modalSubmitActionLabel('Hapus')
                    ->form([static::passwordField()])
                    ->action(fn (User $record): mixed => $record->delete()),
                Tables\Actions\Action::make('forceDelete')
                    ->label('Hapus permanen')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn (User $record): bool => $record->trashed())
                    ->modalHeading('Konfirmasi hapus permanen')
                    ->modalDescription('Masukkan password akun Anda untuk menghapus permanen data pengguna ini.')
                    ->modalSubmitActionLabel('Hapus permanen')
                    ->form([static::passwordField()])
                    ->action(fn (User $record): mixed => $record->forceDelete()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('delete')
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->modalHeading('Konfirmasi hapus')
                        ->modalDescription('Masukkan password akun Anda untuk menghapus data terpilih.')
                        ->modalSubmitActionLabel('Hapus')
                        ->form([static::passwordField()])
                        ->action(function (Collection $records): void {
                            $records->each(
                                fn (User $record) => $record->delete(),
                            );
                        }),
                    Tables\Actions\BulkAction::make('forceDelete')
                        ->label('Hapus permanen')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->modalHeading('Konfirmasi hapus permanen')
                        ->modalDescription('Masukkan password akun Anda untuk menghapus permanen data terpilih.')
                        ->modalSubmitActionLabel('Hapus permanen')
                        ->form([static::passwordField()])
                        ->action(function (Collection $records): void {
                            $records->each(
                                fn (User $record) => $record->forceDelete(),
                            );
                        }),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function passwordField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('password')
            ->label('Password')
            ->password()
            ->required()
            ->autocomplete('current-password')
            ->rules([
                fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                    if (! Hash::check($value, auth()->user()->password)) {
                        $fail('Password yang Anda masukkan salah.');
                    }
                },
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProfileRelationManager::class,
            RelationManagers\SkillsRelationManager::class,
            RelationManagers\MinatRelationManager::class,
            RelationManagers\LamaransRelationManager::class,
            RelationManagers\PendaftaranPelatihansRelationManager::class,
            RelationManagers\TracerStudiesRelationManager::class,
            RelationManagers\AsesmenJawabansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
