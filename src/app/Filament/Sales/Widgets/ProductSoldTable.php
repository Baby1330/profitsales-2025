<?php

namespace App\Filament\Sales\Widgets;

use App\Models\OrderDetail;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProductSoldTable extends BaseWidget
{
    // protected array|string|int $columnSpan = [
    //     'sm' => 12,
    //     'md' => 6,
    //     'lg' => 4,
    // ];
    protected int|string|array $columnSpan = 2;
    protected static ?int $sort = 2;
    protected static ?string $heading = 'List Product yang Terjual';

    public function table(Table $table): Table
    {
        $user = auth()->user();
        $userId = $user->id;
        $userRole = $user->getRoleNames()->first();

        $clientId = null;
        if ($userRole === 'client') {
            $clientId = $user->client?->id;
        }

        return $table
            ->query(
                OrderDetail::query()
                    ->whereHas('order', function (Builder $query) use ($userId, $userRole, $clientId) {
                        if ($userRole === 'sales') {
                            $query->whereHas('sales.employee', fn(Builder $q) => $q->where('user_id', $userId));
                        } elseif ($userRole === 'client') {
                            $query->where('client_id', $clientId);
                        }

                        $query->where('category', 'PO');
                    })
                    ->with('product')
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('quantity')->label('Sold'),
                Tables\Columns\TextColumn::make('product.stock')->label('Avail'),
            ])
            ->defaultSort('quantity', 'desc');
    }
}
