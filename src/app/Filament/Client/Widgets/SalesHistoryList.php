<?php

namespace App\Filament\Client\Widgets;

use App\Models\Order;
use Filament\Widgets\TableWidget;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class SalesHistoryList extends TableWidget
{
    protected static ?string $heading = 'Sales History';
    protected function getTableQuery(): Builder|null
    {
        $user = auth()->user();
    
        return \App\Models\Order::query()
            ->where('client_id', $user->client?->id)
            ->where('category', 'PO')
            ->orderByDesc('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('order_number')->label('Order Number')->sortable(),
            Tables\Columns\TextColumn::make('sales.employee.user.name')->label('Sales Name')->sortable(),
            Tables\Columns\TextColumn::make('category')->label('Category'),
            Tables\Columns\TextColumn::make('status')->label('Status')->sortable(),
            Tables\Columns\TextColumn::make('total')->label('Total')->money('IDR'),
            Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime('d M Y H:i')->sortable(),
        ];
    }
}
