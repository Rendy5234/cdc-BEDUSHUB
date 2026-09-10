<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\AccountWidget;
use App\Filament\Admin\Widgets\PesertaRegistrasiChart;
use App\Filament\Admin\Widgets\RolePenggunaChart;
use App\Filament\Admin\Widgets\StatsOverview;
use App\Filament\Admin\Widgets\StatusLamaranChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * @return array<class-string<\Filament\Widgets\Widget> | \Filament\Widgets\WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            StatsOverview::class,
            PesertaRegistrasiChart::class,
            StatusLamaranChart::class,
            RolePenggunaChart::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }
}
