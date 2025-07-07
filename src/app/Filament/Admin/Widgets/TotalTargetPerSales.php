<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\SalesTarget;
use App\Models\Order;
use App\Models\Branch;

class CustomTargetStats extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $month = now()->month;
        $year = now()->year;

        $targets = SalesTarget::where('month', $month)->where('year', $year)->get();

        $achieved = $targets->filter(function ($target) use ($month, $year) {
            $count = Order::where('sales_id', $target->sales_id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->whereIn('status', ['PO', 'CO'])
                ->count();

            return $count >= $target->targetprod;
        })->count();

        $notAchieved = $targets->count() - $achieved;

        $totalTarget = $targets->sum('targetprod');

        $branchTotal = Branch::with(['employees.sales.targets' => function ($q) use ($month, $year) {
            $q->where('month', $month)->where('year', $year);
        }])->get()->sum(function ($branch) {
            return $branch->employees->sum(function ($employee) {
                return $employee->sales?->targets->sum('targetprod') ?? 0;
            });
        });

        return [
            Card::make('Total Target Bulan Ini', $totalTarget)->icon('heroicon-o-chart-bar')->color('primary'),
            Card::make('Sales Capai Target', $achieved)->icon('heroicon-o-check-circle')->color('success'),
            Card::make('Belum Capai Target', $notAchieved)->icon('heroicon-o-x-circle')->color('danger'),
            Card::make('Total Target per Branch', $branchTotal)->icon('heroicon-o-building-office')->color('info'),
        ];
    }
}
