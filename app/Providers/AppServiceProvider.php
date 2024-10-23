<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\CareerOpportunitiesOfferService;
use App\Services\RateshelpersService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('careerOpportunitiesoffer', function () {
            return new CareerOpportunitiesOfferService();
        });
        $this->app->singleton('Rateshelper', function () {
            return new RateshelpersService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // app('router')->pushMiddlewareToGroup('web', \App\Http\Middleware\EnsureRoleIsSelected::class);
    }
}
