<?php

namespace App\Filament\Perusahaan\Widgets;

use App\Models\Lowongan;
use Filament\Widgets\ChartWidget;

class LamaranPerLowonganChart extends ChartWidget
{
    protected static ?string $heading = 'Pelamar per Lowongan';

    protected int | string | array $columnSpan = 2;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $perusahaanId = auth()->user()?->perusahaan?->id;

        $lowongans = Lowongan::query()
            ->where('perusahaan_id', $perusahaanId)
            ->withCount('lamarans')
            ->orderByDesc('lamarans_count')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pelamar',
                    'data' => $lowongans->pluck('lamarans_count')->toArray(),
                    'backgroundColor' => '#6366f1',
                ],
            ],
            'labels' => $lowongans->pluck('judul')->toArray(),
        ];
    }
}
