<?php

namespace App\Filament\Admin\Resources\SalesTargetResource\Pages;

use App\Filament\Admin\Resources\SalesTargetResource;
use App\Models\Sales;
use App\Models\SalesTarget;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSalesTargets extends ListRecords
{
    protected static string $resource = SalesTargetResource::class;

    public function mount(): void
{
    parent::mount();

    $month = now()->month;
    $year = now()->year;

    $totalTarget = SalesTarget::where('month', $month)
        ->where('year', $year)
        ->sum('targetprod');

    Notification::make()
        ->title("📊 Total Target Bulan Ini")
        ->body("Total target semua sales: {$totalTarget} produk")
        ->success()
        ->send();
}
    protected function getHeaderActions(): array
    {
        return [
            
            Action::make('generateTargetSales')
                ->label('Generate Target Semua Sales')
                ->icon('heroicon-o-plus-circle')
                ->form([
                    TextInput::make('month')
                        ->numeric()->minValue(1)->maxValue(12)
                        ->default(now()->month)
                        ->required(),

                    TextInput::make('year')
                        ->numeric()
                        ->default(now()->year)
                        ->required(),

                    TextInput::make('targetprod')
                        ->label('Target Produk')
                        ->numeric()
                        ->default(5)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $salesList = Sales::with('targets')->get();
                    $count = 0;

                    foreach ($salesList as $sales) {
                        $created = SalesTarget::firstOrCreate([
                            'sales_id' => $sales->id,
                            'year' => $data['year'],
                            'month' => $data['month'],
                        ], [
                            'targetprod' => $data['targetprod'],
                        ]);

                        if ($created->wasRecentlyCreated) {
                            $count++;
                        }
                    }

                    Notification::make()
                        ->success()
                        ->title('Target Digenerate')
                        ->body("Berhasil membuat {$count} target baru untuk sales.")
                        ->send();
                }),
        ];
    }

}
