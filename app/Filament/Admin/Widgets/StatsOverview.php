<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $totalJobs = DB::table('jobs_clean')->count();

        $totalCompanies = DB::table('jobs_clean')
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->distinct('company')
            ->count('company');

        $avgSalary = DB::table('jobs_clean')
            ->whereNotNull('salary_min')
            ->where('salary_min', '>', 0)
            ->avg('salary_min');

        $topSkill = DB::table('jobs_clean')
            ->select('keyword', DB::raw('COUNT(*) as total'))
            ->whereNotNull('keyword')
            ->groupBy('keyword')
            ->orderByDesc('total')
            ->value('keyword') ?? 'Python';

        $avgFormatted = $avgSalary
            ? 'Rp ' . number_format($avgSalary / 1_000_000, 1) . 'M'
            : 'N/A';

        return [
            Stat::make('Total Lowongan IT', number_format($totalJobs))
                ->description('dari platform nasional')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger')
                ->chart([4, 6, 5, 8, 7, 9, 12]),

            Stat::make('Perusahaan Aktif', number_format($totalCompanies))
                ->description('posting lowongan IT')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary')
                ->chart([3, 5, 4, 6, 5, 7, 8]),

            Stat::make('Rata-rata Gaji', $avgFormatted)
                ->description('per bulan, semua level')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([5, 4, 6, 5, 7, 6, 8]),

            Stat::make('Skill #1', $topSkill)
                ->description('paling banyak dicari')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}