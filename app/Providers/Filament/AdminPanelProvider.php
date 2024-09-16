<?php

namespace App\Providers\Filament;

use App\Filament\AdminPanel\Pages\Login;
use App\Http\Middleware\AdminMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->spa()
            ->colors([
                'primary' => Color::hex('#000000'),
                'secondary' => Color::hex('#6c757d'),
                'success' => Color::hex('#28a745'),
                'info' => Color::hex('#17a2b8'),
                'warning' => Color::hex('#ffc107'),
                'danger' => Color::hex('#dc3545'),
            ])
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/AdminPanel/Resources'), for: 'App\\Filament\\AdminPanel\\Resources')
            ->discoverPages(in: app_path('Filament/AdminPanel/Pages'), for: 'App\\Filament\\AdminPanel\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
             ->widgets([
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                BreezyCore::make()->myProfile()
                    ->enableSanctumTokens(),
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
                AdminMiddleware::class,
            ]);
    }
}
