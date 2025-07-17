<?php

namespace App\Filament\Client\Widgets;

use Filament\Widgets\Widget;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatusOrder extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getCards(): array
    {
        $userId = Auth::id();

        // Hitung Total SO (Sales Order)
        $totalSO = Order::where('category', 'SO')
            ->whereHas('client', fn($q) => $q->where('user_id', $userId))
            ->count();

        // Hitung Total PO (Purchase Order)
        $totalPO = Order::where('category', 'PO')
            ->whereHas('client', fn($q) => $q->where('user_id', $userId))
            ->count();

        // Hitung Rejected SO
        $rejectedSO = Order::where('category', 'SO')
            ->where('status', 'Reject')
            ->whereHas('client', fn($q) => $q->where('user_id', $userId))
            ->count();

        // Hitung Pending SO
        $pendingSO = Order::where('category', 'SO')
            ->where('status', 'Pending')
            ->whereHas('client', fn($q) => $q->where('user_id', $userId))
            ->count();

        return [
            Card::make('Total Sales Orders', $totalSO . ' Orders')
                ->color('success')
                ->description('All submitted sales orders'),

            Card::make('Total Purchase Orders', $totalPO . ' Orders')
                ->color('warning')
                ->description('Orders that have been converted to PO'),

            Card::make('Rejected Orders', $rejectedSO . ' Orders')
                ->color('danger')
                ->description('Sales orders rejected by approver'),

            Card::make('Pending Approvals', $pendingSO . ' Orders')
                ->color('primary')
                ->description('Sales orders waiting for approval'),
        ];
    }
}