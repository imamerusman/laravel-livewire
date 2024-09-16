<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(!app()->isProduction());
//        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());
        Model::unguard();
        Model::preventAccessingMissingAttributes(!app()->isProduction());
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
