<?php

namespace App\Filament\Siswa\Resources;

use App\Enums\JenisPelatihan;
use App\Enums\SkillLevel;
use App\Filament\Siswa\Resources\PendaftaranPelatihanResource\Pages;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PendaftaranPelatihanResource extends Resource
{
    protected static ?string $model = PendaftaranPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'Karier & Pelatihan';

    protected static ?string $navigationLabel = 'Pelatihan';

    protected static ?string $modelLabel = 'Pelatihan';

    protected static ?string $pluralModelLabel = 'Pelatihan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
                Forms\Components\Select::make('pelatihan_id')
                    ->relationship('pelatihan', 'judul', fn (Builder $query) => $query->tersediaUntukUser())
                    ->getOptionLabelFromRecordUsing(function (Pelatihan $record): string {
                        $sisa = $record->sisaKuota();

                        return $record->judul
                            .($sisa === null ? ' — Kuota: tanpa batas' : " — Sisa kuota: {$sisa}");
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live(),
                Forms\Components\Section::make('Detail Pelatihan')
                    ->visible(fn (Forms\Get $get): bool => filled($get('pelatihan_id')))
                    ->columns(2)
                    ->schema([
                        Forms\Components\Placeholder::make('detail_topik')
                            ->label('Topik')
                            ->content(fn (Forms\Get $get): ?string => Pelatihan::find($get('pelatihan_id'))?->topik),
                        Forms\Components\Placeholder::make('detail_jenis')
                            ->label('Jenis')
                            ->content(fn (Forms\Get $get): ?string => JenisPelatihan::tryFrom((string) Pelatihan::find($get('pelatihan_id'))?->jenis)?->getLabel()),
                        Forms\Components\Placeholder::make('detail_level')
                            ->label('Level')
                            ->content(fn (Forms\Get $get): ?string => SkillLevel::tryFrom((string) Pelatihan::find($get('pelatihan_id'))?->level)?->getLabel()),
                        Forms\Components\Placeholder::make('detail_instruktur')
                            ->label('Instruktur')
                            ->content(fn (Forms\Get $get): ?string => Pelatihan::find($get('pelatihan_id'))?->instruktur),
                        Forms\Components\Placeholder::make('detail_jadwal')
                            ->label('Jadwal')
                            ->content(function (Forms\Get $get): ?string {
                                $pelatihan = Pelatihan::find($get('pelatihan_id'));

                                if (! $pelatihan) {
                                    return null;
                                }

                                $mulai = $pelatihan->tanggal_mulai?->format('d/m/Y');
                                $selesai = $pelatihan->tanggal_selesai?->format('d/m/Y');
                                $jam = implode(' – ', array_filter([$pelatihan->jam_mulai, $pelatihan->jam_selesai]));

                                return collect([
                                    $mulai && $selesai ? "{$mulai} s/d {$selesai}" : ($mulai ?? $selesai),
                                    $jam,
                                ])->filter()->implode(' · ');
                            }),
                        Forms\Components\Placeholder::make('detail_tempat')
                            ->label('Tempat')
                            ->content(fn (Forms\Get $get): ?string => Pelatihan::find($get('pelatihan_id'))?->tempat),
                        Forms\Components\Placeholder::make('detail_kuota')
                            ->label('Sisa Kuota')
                            ->content(function (Forms\Get $get): ?string {
                                $pelatihan = Pelatihan::find($get('pelatihan_id'));

                                if (! $pelatihan) {
                                    return null;
                                }

                                $sisa = $pelatihan->sisaKuota();

                                return $sisa === null ? 'Tanpa batas' : (string) $sisa;
                            }),
                        Forms\Components\Placeholder::make('detail_deskripsi')
                            ->label('Deskripsi')
                            ->columnSpanFull()
                            ->content(fn (Forms\Get $get): ?string => Pelatihan::find($get('pelatihan_id'))?->deskripsi),
                    ]),
                Forms\Components\Hidden::make('status')
                    ->default('terdaftar'),
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
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        'selesai' => 'info',
                        default => 'warning',
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
            ])
            ->actions([
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
            'index' => Pages\ListPendaftaranPelatihans::route('/'),
            'create' => Pages\CreatePendaftaranPelatihan::route('/create'),
            'edit' => Pages\EditPendaftaranPelatihan::route('/{record}/edit'),
        ];
    }
}
