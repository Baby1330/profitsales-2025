<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class BranchEarningsStat extends Widget
{
    protected static string $view = 'filament.widgets.branch-earnings-stat';
    protected static ?string $heading = '💰 Total PO per Cabang';

    public array $branches = [];
    protected int|string|array $columnSpan = 2;
    protected static ?int $sort = 1;

    public function mount(): void
    {
        $user = auth()->user();

        $query = DB::table('branches')
            ->join('employees', 'employees.branch_id', '=', 'branches.id')
            ->join('sales', 'sales.employee_id', '=', 'employees.id')
            ->join('orders', 'orders.sales_id', '=', 'sales.id')
            ->where('orders.category', 'PO')
            ->select(
                'branches.name as branch_name',
                DB::raw('SUM(orders.total - orders.sales_profit) as po_net_income')
            )
            ->groupBy('branches.name')
            ->orderBy('branches.name');

        // 🔐 Filter khusus kalau user adalah sales
        if ($user->hasRole('sales')) {
            $branchId = $user->employee->branch_id ?? null;

            if ($branchId) {
                $query->where('branches.id', $branchId);
            } else {
                $this->branches = []; // fallback kosong kalau tidak punya employee
                return;
            }
        }

        $this->branches = $query
            ->get()
            ->map(fn ($row) => [
                'name' => $row->branch_name,
                'total' => number_format($row->po_net_income, 0, ',', '.'),
            ])
            ->toArray();
    }
}
