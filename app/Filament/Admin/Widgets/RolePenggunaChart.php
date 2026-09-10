<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class RolePenggunaChart extends ChartWidget
{
    protected static ?string $heading = 'Pengguna per Role';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $roles = [
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_PERUSAHAAN => 'Perusahaan',
            User::ROLE_SISWA => 'Siswa',
            User::ROLE_MAHASISWA => 'Mahasiswa',
            User::ROLE_ALUMNI => 'Alumni',
        ];

        $counts = User::query()
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $labels = [];
        $data = [];

        foreach ($roles as $value => $label) {
            $labels[] = $label;
            $data[] = $counts[$value] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengguna',
                    'data' => $data,
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
