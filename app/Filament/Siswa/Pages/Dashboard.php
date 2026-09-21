<?php

namespace App\Filament\Siswa\Pages;

use App\Filament\Siswa\Widgets\AccountWidget;
use App\Filament\Siswa\Widgets\RekomendasiWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

class Dashboard extends BaseDashboard
{
    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            RekomendasiWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }
}
