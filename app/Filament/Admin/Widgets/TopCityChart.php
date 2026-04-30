<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopCityChart extends ChartWidget
{
    protected static ?string $heading = 'Top Lokasi Lowongan';

    protected static ?string $description = 'Daerah dengan jumlah lowongan IT terbanyak';

    // ukuran widget (biar bisa disandingkan)
    protected int | string | array $columnSpan = 6;

    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->select('city', DB::raw('COUNT(*) as total'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Lowongan',
                    'data' => $data->pluck('total')->toArray(),

                    // 🔥 biar lebih keren
                    'backgroundColor' => collect(range(0, $data->count() - 1))
                        ->map(fn ($i) => "rgba(59,130,246," . (0.9 - $i * 0.07) . ")")
                        ->toArray(),
                ],
            ],
            'labels' => $data->pluck('city')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}