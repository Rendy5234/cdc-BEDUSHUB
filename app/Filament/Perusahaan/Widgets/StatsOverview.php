<?php

namespace App\Filament\Perusahaan\Widgets;

use App\Models\Lamaran;
use App\Models\Lowongan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $perusahaanId = auth()->user()?->perusahaan?->id;

        $lowonganQuery = Lowongan::query()
            ->where('perusahaan_id', $perusahaanId);

        $lamaranQuery = Lamaran::query()
            ->whereHas('lowongan', fn ($q) => $q->where('perusahaan_id', $perusahaanId));

        return [
            Stat::make('Total Lowongan', $lowonganQuery->count())
                ->description('Semua lowongan perusahaan')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),

            Stat::make('Lowongan Aktif', (clone $lowonganQuery)->where('status', 'aktif')->count())
                ->description('Sedang dibuka')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Total Pelamar', $lamaranQuery->count())
                ->description('Seluruh pelamar masuk')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Pelamar Diproses', (clone $lamaranQuery)->where('status', 'diproses')->count())
                ->description('Menunggu tindak lanjut')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('warning'),

            Stat::make('Pelamar Diterima', (clone $lamaranQuery)->where('status', 'diterima')->count())
                ->description('Lamaran diterima')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
