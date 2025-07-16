<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Resources\ProductResource;
use App\Filament\Admin\Resources\SalesTargetResource;
use App\Filament\Admin\Widgets\BranchEarningsStat;
use App\Filament\Client\Pages\Auth\Register as ClientRegister;
use App\Filament\Client\Widgets\SalesHistoryList;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\SalesCommissionsResource;
use App\Filament\Sales\Widgets\ProductSoldTable;
use App\Filament\Sales\Widgets\TotalCommissions;
use App\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ClientPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('client')
            ->path('client')
            ->brandName('Client Portal')
            ->login()
            ->registration(\App\Filament\Client\Pages\Auth\Register::class)
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->group(
                    NavigationGroup::make('Client Menu')
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->url(fn() => Pages\Dashboard::getUrl())
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn() => request()->routeIs('filament.client.pages.dashboard')),
                            NavigationItem::make('My Orders')
                                ->url(fn() => OrderResource::getUrl('index'))
                                ->icon('heroicon-o-shopping-cart')
                                ->isActiveWhen(fn() => request()->routeIs(OrderResource::getRouteBaseName() . '.*')),
                        ]),
                );
            })
            ->discoverResources(in: app_path('Filament/Client/Resources'), for: 'App\\Filament\\Client\\Resources')
            ->discoverPages(in: app_path('Filament/Client/Pages'), for: 'App\\Filament\\Client\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Client/Widgets'), for: 'App\\Filament\\Client\\Widgets')
            ->widgets([
                ProductSoldTable::class,
                SalesHistoryList::class,
            ])
            ->resources([
                OrderResource::class,
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
            ])
            // Tenancy and multi-guard configurations can be added here if needed
        ;
    }
}
