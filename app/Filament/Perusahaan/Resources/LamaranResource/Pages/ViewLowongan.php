<?php

namespace App\Filament\Perusahaan\Resources\LamaranResource\Pages;

use App\Filament\Perusahaan\Resources\LamaranResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewLowongan extends ViewRecord
{
    protected static string $resource = LamaranResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('judul')
                    ->label('Lowongan'),
                Infolists\Components\TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'aktif' ? 'success' : 'danger'),
                // Infolists\Components\TextEntry::make('kuota')
                //     ->label('Kuota Pendaftar'),
                // Infolists\Components\TextEntry::make('kuota_diterima')
                //     ->label('Kuota Diterima'),
                Infolists\Components\TextEntry::make('tanggal_berakhir')
                    ->date(),
            ])
            ->columns(2);
    }
}