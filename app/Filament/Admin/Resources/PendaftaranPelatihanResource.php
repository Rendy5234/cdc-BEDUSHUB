<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PendaftaranPelatihanResource\Pages;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class PendaftaranPelatihanResource extends Resource
{
    protected static ?string $model = PendaftaranPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'Karier & Pelatihan';

    protected static ?string $modelLabel = 'Pendaftaran Pelatihan';

    protected static ?string $pluralModelLabel = 'Pendaftaran Pelatihan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pelatihan_id')
                    ->relationship(
                        'pelatihan',
                        'judul',
                        fn (Builder $query, string $operation) => $query
                            ->withoutTrashed()
                            ->when($operation === 'create', function (Builder $query) {
                                $query
                                    ->where('status', 'published')
                                    ->whereDate('tanggal_mulai', '>=', now()->toDateString());
                            }),
                    )
                    ->getOptionLabelFromRecordUsing(function (Pelatihan $record): string {
                        $sisa = $record->sisaKuota();

                        return $record->judul
                            . ($sisa === null ? ' — Kuota: tanpa batas' : " — Sisa kuota: {$sisa}");
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit')
                    ->helperText('Input manual untuk pendaftaran offline (walk-in).')
                    ->rules(fn (string $operation): array => $operation !== 'create'
                        ? []
                        : [
                            function (string $attribute, mixed $value, Closure $fail): void {
                                $pelatihan = Pelatihan::find($value);

                                if ($pelatihan?->sisaKuota() === 0) {
                                    $fail('Kuota pelatihan ini sudah penuh.');
                                }
                            },
                        ]),
                Forms\Components\Select::make('user_id')
                    ->relationship(
                        'user',
                        'name',
                        fn (Builder $query, string $operation) => $query
                            ->when($operation === 'create', fn (Builder $query) => $query
                                ->whereIn('role', [
                                    User::ROLE_SISWA,
                                    User::ROLE_MAHASISWA,
                                    User::ROLE_ALUMNI,
                                ])),
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit'),
                Forms\Components\Select::make('status')
                    ->options([
                        'terdaftar' => 'Terdaftar',
                        'diterima' => 'Diterima',
                    ])
                    ->default('terdaftar')
                    ->required()
                    ->disabled(fn (?PendaftaranPelatihan $record): bool => $record?->trashed() ?? false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pelatihan.judul')
                    ->label('Pelatihan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peserta')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'terdaftar' => 'Terdaftar',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ])
                    ->disabled(fn (PendaftaranPelatihan $record): bool => $record->trashed())
                    ->updateStateUsing(function (PendaftaranPelatihan $record, mixed $state): mixed {
                        if ($record->trashed()) {
                            return $record->getAttribute('status');
                        }

                        $record->update(['status' => $state]);

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'terdaftar' => 'Terdaftar',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ]),
                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->relationship('pelatihan', 'judul', fn ($query) => $query->withoutTrashed())
                    ->label('Pelatihan'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (PendaftaranPelatihan $record): bool => ! $record->trashed()),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn (PendaftaranPelatihan $record): bool => ! $record->trashed())
                    ->modalHeading('Konfirmasi hapus')
                    ->modalDescription('Masukkan password akun Anda untuk menghapus data pendaftaran ini.')
                    ->modalSubmitActionLabel('Hapus')
                    ->form([static::passwordField()])
                    ->action(fn (PendaftaranPelatihan $record): mixed => $record->delete()),
                Tables\Actions\Action::make('forceDelete')
                    ->label('Hapus permanen')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn (PendaftaranPelatihan $record): bool => $record->trashed())
                    ->modalHeading('Konfirmasi hapus permanen')
                    ->modalDescription('Masukkan password akun Anda untuk menghapus permanen data pendaftaran ini.')
                    ->modalSubmitActionLabel('Hapus permanen')
                    ->form([static::passwordField()])
                    ->action(fn (PendaftaranPelatihan $record): mixed => $record->forceDelete()),
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
                                fn (PendaftaranPelatihan $record) => $record->delete(),
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
                                fn (PendaftaranPelatihan $record) => $record->forceDelete(),
                            );
                        }),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    protected static function passwordField(): Forms\Components\TextInput
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftaranPelatihans::route('/'),
            'create' => Pages\CreatePendaftaranPelatihan::route('/create'),
            'edit' => Pages\EditPendaftaranPelatihan::route('/{record}/edit'),
        ];
    }
}
