<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Asesmen;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\Pelatihan;
use App\Models\Perusahaan;
use App\Models\RekomendasiLog;
use App\Models\Skill;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $peserta = User::query()
            ->whereIn('role', [User::ROLE_SISWA, User::ROLE_MAHASISWA, User::ROLE_ALUMNI])
            ->count();

        return [
            Stat::make('Peserta', $peserta)
                ->description('Siswa, mahasiswa & alumni')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Perusahaan', Perusahaan::query()->count())
                ->description('Mitra perusahaan')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success'),

            Stat::make('Lowongan Aktif', Lowongan::query()->where('status', 'aktif')->count())
                ->description('Lowongan yang sedang dibuka')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning'),

            Stat::make('Pelatihan Publish', Pelatihan::query()->where('status', 'published')->count())
                ->description('Pelatihan yang terbit')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),

            Stat::make('Lamaran Diproses', Lamaran::query()->where('status', 'diproses')->count())
                ->description('Lamaran menunggu tindak lanjut')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('danger'),

            Stat::make('Total Asesmen', Asesmen::query()->count())
                ->description('Paket asesmen tersedia')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('gray'),

            Stat::make('Skill Terdaftar', Skill::query()->count())
                ->description('Skill untuk pemetaan rekomendasi')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('violet'),

            Stat::make('Rekomendasi Diberikan', RekomendasiLog::query()->count())
                ->description('Total log rekomendasi tersimpan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('fuchsia'),
        ];
    }
}
