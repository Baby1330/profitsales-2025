<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = -2;

    // public static function getEloquentQuery(): Builder
    // {
    //     $query = parent::getEloquentQuery();
    //     $user = Filament::auth()->user();
    //     $panel = Filament::getCurrentPanel()?->getId();

    //     // Sales can only see their own commissions
    //     if ($panel === 'sales' && $user->hasRole('sales')) {
    //         $query->whereHas('sales', function ($q) use ($user) {
    //             $q->whereHas('employee', function ($q) use ($user) {
    //                 $q->where('user_id', $user->id);
    //             });
    //         });
    //     }

    //     return $query;
    // }

    public static function canViewAny(): bool
    {
        $user = Filament::auth()->user();
        $panel = Filament::getCurrentPanel()?->getId();

        return match ($panel) {
            'admin' => $user?->hasRole('super_admin'),
            'sales' => $user?->hasRole('sales'),
            default => true,
        };
    }

    public static function canCreate(): bool
    {
        return Filament::auth()->user()?->hasRole('super_admin');
    }

    public static function canEdit(Model $record): bool
    {
        return Filament::auth()->user()?->hasRole('super_admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->reactive() // penting: agar bisa trigger perubahan ke cost
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state !== null && is_numeric($state)) {
                            $set('cost', round($state * 0.01)); // otomatis 1%
                        }
                    }),

                Forms\Components\TextInput::make('cost')
                    ->label('Profit Sales (1%)')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled() // agar tidak bisa diubah user
                    ->dehydrated(true), // ✅ tetap dikirim ke server walau disabled

                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->maxLength(255),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Profit Sales')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Stock_Ready')
                    ->label('AVL')
                    ->state(function ($record) {
                        $sold = $record->orderDetails()
                            ->whereHas('order', fn($q) => $q->where('category', 'PO'))
                            ->sum('quantity');
                
                        return $record->stock - $sold;
                    })
                    ->color(function ($record) {
                        $sold = $record->orderDetails()
                            ->whereHas('order', fn($q) => $q->where('category', 'PO'))
                            ->sum('quantity');
                
                        $stockReady = $record->stock - $sold;
                
                        return $stockReady < 100 ? 'danger' : 'success';
                    }),
                Tables\Columns\TextColumn::make('sold_quantity')
                    ->label('SLD')
                    ->state(function ($record) {
                        return $record->orderDetails()
                            ->whereHas('order', fn($q) => $q->where('category', 'PO')) // hanya hitung PO
                            ->sum('quantity');
                    }),
                Tables\Columns\TextColumn::make('stock')
                    ->label('STK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
