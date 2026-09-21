<?php

namespace App\Filament\Siswa\Resources;

use App\Enums\Jenjang;
use App\Filament\Siswa\Resources\LamaranResource\Pages;
use App\Models\Lamaran;
use App\Models\Lowongan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LamaranResource extends Resource
{
    protected static ?string $model = Lamaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Karier & Pelatihan';

    protected static ?string $modelLabel = 'Lamaran';

    protected static ?string $pluralModelLabel = 'Lamaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
                Forms\Components\Select::make('lowongan_id')
                    ->relationship('lowongan', 'judul', fn (Builder $query) => $query->tersediaUntukUser())
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->rules([
                        fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail): void {
                            $lowongan = Lowongan::find($value);

                            if (! $lowongan || blank($lowongan->pendidikan)) {
                                return;
                            }

                            $requiredList = (array) $lowongan->pendidikan;
                            $userJenjang = auth()->user()?->profile?->jenjang;

                            if ($userJenjang === null) {
                                $fail('Anda belum mengisi jenjang pendidikan pada profil. Lengkapi profil terlebih dahulu.');

                                return;
                            }

                            if (! in_array($userJenjang, $requiredList, true)) {
                                $labelUser = Jenjang::tryFrom($userJenjang)?->getLabel() ?? $userJenjang;
                                $labelRequired = collect($requiredList)
                                    ->map(fn ($j) => Jenjang::tryFrom($j)?->getLabel() ?? $j)
                                    ->implode(', ');

                                $fail("Jenjang pendidikan Anda ({$labelUser}) tidak sesuai dengan persyaratan lowongan ini ({$labelRequired}).");
                            }
                        },
                    ]),
                Forms\Components\Section::make('Detail Lowongan')
                    ->visible(fn (Forms\Get $get): bool => filled($get('lowongan_id')))
                    ->schema([
                        Forms\Components\Placeholder::make('detail_perusahaan')
                            ->label('Perusahaan')
                            ->content(fn (Forms\Get $get): ?string => Lowongan::find($get('lowongan_id'))?->perusahaan?->nama),
                        Forms\Components\Placeholder::make('detail_tipe_pekerjaan')
                            ->label('Tipe Pekerjaan')
                            ->content(fn (Forms\Get $get): ?string => Lowongan::find($get('lowongan_id'))?->tipe_pekerjaan),
                        Forms\Components\Placeholder::make('detail_lokasi')
                            ->label('Lokasi')
                            ->content(fn (Forms\Get $get): ?string => Lowongan::find($get('lowongan_id'))?->lokasi),
                        Forms\Components\Placeholder::make('detail_gaji')
                            ->label('Gaji')
                            ->content(function (Forms\Get $get): string {
                                $l = Lowongan::find($get('lowongan_id'));

                                if (! $l || (! $l->gaji_min && ! $l->gaji_max)) {
                                    return '-';
                                }

                                $min = $l->gaji_min ? 'Rp '.number_format($l->gaji_min, 0, ',', '.') : '-';
                                $max = $l->gaji_max ? 'Rp '.number_format($l->gaji_max, 0, ',', '.') : '-';

                                return $min.' - '.$max;
                            }),
                        Forms\Components\Placeholder::make('detail_pendidikan')
                            ->label('Pendidikan')
                            ->content(function (Forms\Get $get): ?string {
                                $pendidikan = Lowongan::find($get('lowongan_id'))?->pendidikan;

                                if (blank($pendidikan)) {
                                    return null;
                                }

                                return collect((array) $pendidikan)
                                    ->map(fn ($j) => Jenjang::tryFrom($j)?->getLabel() ?? $j)
                                    ->filter()
                                    ->implode(', ');
                            }),
                        Forms\Components\Placeholder::make('detail_kuota')
                            ->label('Sisa Kuota')
                            ->content(function (Forms\Get $get): string {
                                $l = Lowongan::find($get('lowongan_id'));

                                if (! $l) {
                                    return '-';
                                }

                                $sisa = $l->sisaKuota();

                                return $sisa === null ? 'Tanpa batas' : $sisa.' dari '.$l->kuota;
                            }),
                        Forms\Components\Placeholder::make('detail_periode')
                            ->label('Periode')
                            ->columnSpanFull()
                            ->content(function (Forms\Get $get): string {
                                $l = Lowongan::find($get('lowongan_id'));

                                if (! $l) {
                                    return '-';
                                }

                                $mulai = $l->tanggal_mulai?->format('d M Y');
                                $akhir = $l->tanggal_berakhir?->format('d M Y');

                                return trim(($mulai ?? '').'  s/d  '.($akhir ?? ''));
                            }),
                        Forms\Components\Placeholder::make('detail_deskripsi')
                            ->label('Deskripsi')
                            ->columnSpanFull()
                            ->content(fn (Forms\Get $get): ?string => ($d = Lowongan::find($get('lowongan_id'))?->deskripsi) ? nl2br(e($d)) : '-'),
                        Forms\Components\Placeholder::make('detail_kualifikasi')
                            ->label('Kualifikasi')
                            ->columnSpanFull()
                            ->content(fn (Forms\Get $get): ?string => ($k = Lowongan::find($get('lowongan_id'))?->kualifikasi) ? nl2br(e($k)) : '-'),
                        Forms\Components\Placeholder::make('detail_benefit')
                            ->label('Benefit')
                            ->columnSpanFull()
                            ->content(fn (Forms\Get $get): ?string => ($b = Lowongan::find($get('lowongan_id'))?->benefit) ? nl2br(e($b)) : '-'),
                    ])
                    ->columns(2),
                Forms\Components\Hidden::make('status')
                    ->default('diproses'),
                Forms\Components\FileUpload::make('cv')
                    ->label('CV')
                    ->directory('lamaran/cv')
                    ->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'])
                    ->maxSize(5120)
                    ->required(),
                Forms\Components\FileUpload::make('ijazah')
                    ->label('Ijazah')
                    ->directory('lamaran/ijazah')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->maxSize(5120)
                    ->required(),
                Forms\Components\FileUpload::make('surat_lamaran')
                    ->label('Surat Lamaran')
                    ->directory('lamaran/surat-lamaran')
                    ->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'])
                    ->maxSize(5120)
                    ->visible(fn (Forms\Get $get): bool => (bool) (Lowongan::find($get('lowongan_id'))?->butuh_surat_lamaran))
                    ->required(fn (Forms\Get $get): bool => (bool) (Lowongan::find($get('lowongan_id'))?->butuh_surat_lamaran)),
                Forms\Components\FileUpload::make('pas_foto')
                    ->label('Pas Foto')
                    ->directory('lamaran/pas-foto')
                    ->image()
                    ->maxSize(5120)
                    ->visible(fn (Forms\Get $get): bool => (bool) (Lowongan::find($get('lowongan_id'))?->butuh_pas_foto))
                    ->required(fn (Forms\Get $get): bool => (bool) (Lowongan::find($get('lowongan_id'))?->butuh_pas_foto))
                    ->helperText(fn (Forms\Get $get): ?string => ($rasio = Lowongan::find($get('lowongan_id'))?->rasio_pas_foto) ? 'Rasio yang diminta: '.$rasio : null),
                Forms\Components\FileUpload::make('dokumen_pendukung')
                    ->label('Dokumen Pendukung')
                    ->directory('lamaran/dokumen-pendukung')
                    ->multiple()
                    ->maxSize(5120),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lowongan.judul')
                    ->label('Lowongan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lowongan.perusahaan.nama')
                    ->label('Perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'diproses' => 'Diproses',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]),
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
            'index' => Pages\ListLamarans::route('/'),
            'create' => Pages\CreateLamaran::route('/create'),
            'edit' => Pages\EditLamaran::route('/{record}/edit'),
        ];
    }
}
