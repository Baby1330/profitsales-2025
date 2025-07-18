<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Resources\ProductResource;
use App\Filament\Admin\Resources\SalesTargetResource;
use App\Filament\Admin\Widgets\BranchEarningsStat;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\SalesCommissionsResource;
use App\Filament\Sales\Widgets\ProductSoldTable;
use App\Filament\Sales\Widgets\TotalCommissions;
use App\Models\SalesTarget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class SalesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('sales')
            ->path('sales')
            ->login()
            ->authGuard('web')
            ->brandName('Sales Portal')
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Sales/Resources'), for: 'App\\Filament\\Sales\\Resources')
            ->discoverPages(in: app_path('Filament/Sales/Pages'), for: 'App\\Filament\\Sales\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Sales/Widgets'), for: 'App\\Filament\\Sales\\Widgets')
            ->widgets([
                BranchEarningsStat::class,
                TotalCommissions::class,
                ProductSoldTable::class,
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->resources([
                SalesCommissionsResource::class,
                OrderResource::class,
                SalesTargetResource::class,
                ProductResource::class
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
