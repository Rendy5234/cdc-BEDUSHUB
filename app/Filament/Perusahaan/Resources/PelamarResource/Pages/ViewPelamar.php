<?php

namespace App\Filament\Perusahaan\Resources\PelamarResource\Pages;

use App\Enums\Jenjang;
use App\Filament\Perusahaan\Resources\LamaranResource;
use App\Filament\Perusahaan\Resources\PelamarResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ViewPelamar extends ViewRecord
{
    protected static string $resource = PelamarResource::class;

    public function getBreadcrumbs(): array
    {
        $record = $this->getRecord();
        $lowongan = $record->lowongan;

        $breadcrumbs = [
            LamaranResource::getUrl('index') => 'Lamaran Masuk',
        ];

        if ($lowongan) {
            $breadcrumbs[LamaranResource::getUrl('view', ['record' => $lowongan])] = $lowongan->judul;
        }

        $breadcrumbs[] = $this->getRecordTitle();

        return $breadcrumbs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ubahStatus')
                ->label('Ubah Status')
                ->icon('heroicon-o-arrow-path')
                ->form([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'diproses' => 'Diproses',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Ditolak',
                        ])
                        ->default(fn () => $this->record->status)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update(['status' => $data['status']]);

                    Notification::make()
                        ->title('Status diperbarui')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Data Lamaran')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'diterima' => 'success',
                                'ditolak' => 'danger',
                                default => 'warning',
                            }),
                        Infolists\Components\TextEntry::make('lowongan.judul')
                            ->label('Lowongan'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Mendaftar Pada')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('catatan')
                            ->label('Catatan')
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Data Pelamar')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Nama'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('user.profile.jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->formatStateUsing(fn ($state): string => match ($state) {
                                'l' => 'Laki-laki',
                                'p' => 'Perempuan',
                                default => '-',
                            }),
                        Infolists\Components\TextEntry::make('user.profile.tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->date(),
                        Infolists\Components\TextEntry::make('user.profile.domisili_kecamatan')
                            ->label('Domisili')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('user.profile.jenjang')
                            ->label('Jenjang')
                            ->formatStateUsing(fn ($state): string => Jenjang::tryFrom($state)?->getLabel() ?? '-'),
                        Infolists\Components\TextEntry::make('user.profile.institusi.nama')
                            ->label('Institusi')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('user.profile.jurusan.nama')
                            ->label('Jurusan')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('user.profile.prodi.nama')
                            ->label('Program Studi')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('user.profile.tahun_lulus')
                            ->label('Tahun Lulus')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('user.profile.no_telp')
                            ->label('No. Telepon')
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Dokumen Lamaran')
                    ->schema([
                        Infolists\Components\TextEntry::make('cv')
                            ->label('CV')
                            ->formatStateUsing(fn ($state) => $state ? Str::afterLast($state, '/') : null)
                            ->url(fn ($record) => $record->cv ? Storage::disk('public')->url($record->cv) : null)
                            ->openUrlInNewTab()
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('ijazah')
                            ->label('Ijazah')
                            ->formatStateUsing(fn ($state) => $state ? Str::afterLast($state, '/') : null)
                            ->url(fn ($record) => $record->ijazah ? Storage::disk('public')->url($record->ijazah) : null)
                            ->openUrlInNewTab()
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('surat_lamaran')
                            ->label('Surat Lamaran')
                            ->formatStateUsing(fn ($state) => $state ? Str::afterLast($state, '/') : null)
                            ->url(fn ($record) => $record->surat_lamaran ? Storage::disk('public')->url($record->surat_lamaran) : null)
                            ->openUrlInNewTab()
                            ->placeholder('-'),
                        Infolists\Components\ImageEntry::make('pas_foto')
                            ->label('Pas Foto')
                            ->disk('public')
                            ->height(240)
                            ->visible(fn ($record) => filled($record->pas_foto)),
                        Infolists\Components\TextEntry::make('dokumen_pendukung')
                            ->label('Dokumen Pendukung')
                            ->html()
                            ->columnSpanFull()
                            ->formatStateUsing(function ($record): string {
                                $files = collect($record->dokumen_pendukung ?? [])->filter();

                                if ($files->isEmpty()) {
                                    return '-';
                                }

                                return $files
                                    ->map(fn ($path) => '<a href="'.Storage::disk('public')->url($path).'" target="_blank" rel="noopener">'.e(Str::afterLast($path, '/')).'</a>')
                                    ->implode('<br>');
                            }),
                    ])
                    ->columns(2),
            ]);
    }
}
