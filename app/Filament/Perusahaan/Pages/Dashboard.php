<?php

namespace App\Filament\Perusahaan\Pages;

use App\Filament\Perusahaan\Widgets\AccountWidget;
use App\Filament\Perusahaan\Widgets\LamaranPerLowonganChart;
use App\Filament\Perusahaan\Widgets\StatsOverview;
use App\Filament\Perusahaan\Widgets\StatusKerjasamaWidget;
use App\Filament\Perusahaan\Widgets\StatusLamaranChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            StatusKerjasamaWidget::class,
            StatsOverview::class,
            StatusLamaranChart::class,
            LamaranPerLowonganChart::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 3;
    }
}