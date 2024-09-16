<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Banner;
use App\Models\Customer;
use App\Policies\BannerPolicy;
use App\Policies\CustomerPolicy;
use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        /*Auth::provider('shopify', function ($app, array $config) {
            return new ShopifyUserProvider($app['hash'], $config['model']);
        });*/
    }
}
