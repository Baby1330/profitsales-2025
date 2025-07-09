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
    protected array|string|int $columnSpan = [
        'sm' => 12,
        'md' => 6,
        'lg' => 4,
    ];
    // protected int|string|array $columnSpan = 2;
    protected static ?int $sort = 2;
    protected static ?string $heading = 'List Product yang Terjual';

    public function table(Table $table): Table
    {
        $userId = Auth::id();

        return $table
            ->query(
                OrderDetail::query()
                ->whereHas('order', function (Builder $query) use ($userId) {
                    $query->whereHas('sales', fn(Builder $q) => $q->where('user_id', $userId))
                          ->where('category', 'PO');
                })
                ->with('product')
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Nama Produk'),
                Tables\Columns\TextColumn::make('quantity')->label('Terjual'),
                Tables\Columns\TextColumn::make('product.stock')->label('Stok Produk'),
            ])
            ->defaultSort('quantity', 'desc');
    }
}
