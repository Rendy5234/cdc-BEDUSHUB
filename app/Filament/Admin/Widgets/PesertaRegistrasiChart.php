<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PesertaRegistrasiChart extends ChartWidget
{
    protected static ?string $heading = 'Registrasi Peserta (12 Bulan Terakhir)';

    protected static ?string $maxHeight = '250px';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $start = Carbon::now()->subMonths(6)->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $roles = [User::ROLE_SISWA, User::ROLE_MAHASISWA, User::ROLE_ALUMNI];

        $rows = User::query()
            ->whereIn('role', $roles)
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy(fn (User $user) => $user->created_at->format('Y-m'));

        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $key = $month->format('Y-m');

            $labels[] = $month->format('M Y');
            $data[] = $rows->get($key)?->count() ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Peserta',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
