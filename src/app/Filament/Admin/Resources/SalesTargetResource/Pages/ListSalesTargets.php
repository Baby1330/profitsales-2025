<?php

namespace App\Filament\Admin\Resources\SalesTargetResource\Pages;

use App\Filament\Admin\Resources\SalesTargetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesTargets extends ListRecords
{
    protected static string $resource = SalesTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
