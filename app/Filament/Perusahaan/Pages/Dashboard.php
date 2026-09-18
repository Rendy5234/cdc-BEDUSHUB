<?php

namespace App\Filament\Perusahaan\Pages;

use App\Filament\Perusahaan\Widgets\AccountWidget;
use App\Filament\Perusahaan\Widgets\StatusKerjasamaWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            StatusKerjasamaWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 3;
    }
}