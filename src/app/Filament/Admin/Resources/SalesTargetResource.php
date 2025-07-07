<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SalesTargetResource\Pages;
use App\Filament\Admin\Resources\SalesTargetResource\RelationManagers;
use App\Models\Branch;
use App\Models\Order;
use App\Models\SalesTarget;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesTargetResource extends Resource
{
    protected static ?string $model = SalesTarget::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sales_id')
                ->relationship('sales.employee.user', 'name') 
                //->searchable()
                ->required(),

                TextInput::make('year')
                    ->numeric()
                    ->required(),

                TextInput::make('month')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12)
                    ->required(),

                TextInput::make('targetprod')
                    ->label('Target Produk')
                    ->numeric()
                    ->default(5)
                    ->required(),
                
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('sales.employee.user.name')
                ->label('Nama Sales')
                ->searchable(),

            TextColumn::make('year'),
            TextColumn::make('month'),

            TextColumn::make('targetprod')
                ->label('Target Produk'),
                TextColumn::make('achieved')
                ->label('Tercapai')
                ->getStateUsing(function ($record) {
                    // Ambil total order dari sales ini yang berstatus PO / CO
                    $achieved = Order::where('sales_id', $record->sales_id)
                        ->whereYear('created_at', $record->year)
                        ->whereMonth('created_at', $record->month)
                        ->whereIn('status', ['PO', 'CO']) // sesuaikan status valid
                        ->count();

                    return $achieved;
                }),

            TextColumn::make('status')
                ->label('Status Target')
                ->getStateUsing(function ($record) {
                    $achieved = Order::where('sales_id', $record->sales_id)
                        ->whereYear('created_at', $record->year)
                        ->whereMonth('created_at', $record->month)
                        ->whereIn('status', ['PO', 'CO'])
                        ->count();

                    return $achieved >= $record->targetprod ? '✅ Tercapai' : '❌ Belum';
                })
                ->color(fn($state) => $state === '✅ Tercapai' ? 'success' : 'danger'),
        
        ])
        ->filters([
            SelectFilter::make('branch_id')
        ->label('Cabang')
        ->options(Branch::all()->pluck('name', 'id'))
        ->query(function (Builder $query, array $data): Builder {
            if (! $data['value']) {
                return $query;
            }

            return $query->whereHas('sales.employee', function ($q) use ($data) {
                $q->where('branch_id', $data['value']);
            });
        }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSalesTargets::route('/'),
            'create' => Pages\CreateSalesTarget::route('/create'),
            'edit' => Pages\EditSalesTarget::route('/{record}/edit'),
        ];
    }
}
