<?php

namespace App\Filament\Perusahaan\Widgets;

use Filament\Widgets\Widget;

class StatusKerjasamaWidget extends Widget
{
    protected int | string | array $columnSpan = 1;

    protected static string $view = 'filament.perusahaan.widgets.status-kerjasama';

    protected function getViewData(): array
    {
        $status = auth()->user()?->perusahaan?->status_kerjasama;

        return [
            'label' => match ($status) {
                'aktif' => 'Aktif',
                'nonaktif' => 'Nonaktif',
                default => 'Belum Ada',
            },
            'color' => match ($status) {
                'aktif' => 'success',
                'nonaktif' => 'danger',
                default => 'gray',
            },
        ];
    }
}
