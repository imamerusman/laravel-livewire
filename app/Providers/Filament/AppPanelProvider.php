<?php

namespace App\Providers\Filament;

use App\Filament\AppPanel\Dashboard\AppDashboard;
use App\Filament\AppPanel\Pages\Login;
use App\Http\Middleware\HaveCompletedSteps;
use App\Http\Middleware\PlanFeatureMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Livewire\Dashboard\BillingOverView;
use App\Livewire\DashboardOverview;
use App\Livewire\FirebaseCredentials;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Blade;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentView::registerRenderHook('panels::body.start', fn(): string => Blade::render('@vite(\'resources/css/app.css\')'));
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login(Login::class)
            ->passwordReset()
            ->spa()
            ->databaseNotifications()
            ->darkMode(false)
            ->colors([
                'primary' => Color::hex('#007558'),
                'secondary' => Color::hex('#6c757d'),
                'success' => Color::hex('#28a745'),
                'info' => Color::hex('#17a2b8'),
                'warning' => Color::hex('#ffc107'),
                'danger' => Color::hex('#dc3545'),
            ])
            ->discoverResources(in: app_path('Filament/AppPanel/Resources'), for: 'App\\Filament\\AppPanel\\Resources')
            ->discoverPages(in: app_path('Filament/AppPanel/Pages'), for: 'App\\Filament\\AppPanel\\Pages')
            ->pages([
                AppDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/AppPanel/Dashboard/Widgets'), for: 'App\\Filament\\AppPanel\\Dashboard\\Widgets')
            ->widgets([
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                BreezyCore::make()->myProfile()
                    ->enableSanctumTokens()
                    ->myProfileComponents([
                        FirebaseCredentials::class,
                        DashboardOverview::class,
                        BillingOverView::class
                    ]),
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
                UserMiddleware::class,
                PlanFeatureMiddleware::class,
                HaveCompletedSteps::class,
            ]);
    }
}
