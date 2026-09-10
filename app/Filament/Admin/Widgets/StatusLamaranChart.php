<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Lamaran;
use Filament\Widgets\ChartWidget;

class StatusLamaranChart extends ChartWidget
{
    protected static ?string $heading = 'Status Lamaran';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $statuses = [
            'diproses' => 'Diproses',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
        ];

        $counts = Lamaran::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = [];
        $data = [];

        foreach ($statuses as $value => $label) {
            $labels[] = $label;
            $data[] = $counts[$value] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Lamaran',
                    'data' => $data,
                    'backgroundColor' => ['#f59e0b', '#10b981', '#ef4444'],
                ],
            ],
            'labels' => $labels,
        ];
    }
}
