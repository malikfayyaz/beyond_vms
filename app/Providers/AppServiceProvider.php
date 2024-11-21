<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\CareerOpportunitiesOfferService;
use App\Services\CareerOpportunitiesContractService;
use App\Services\TimesheetService;
use App\Services\RateshelpersService;
use Illuminate\Support\Facades\Schema;

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
        $this->app->singleton('careerOpportunitiescontract', function () {
            return new CareerOpportunitiesContractService();
        });

        $this->app->singleton('timesheet', function () {
            return new TimesheetService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        //
        // app('router')->pushMiddlewareToGroup('web', \App\Http\Middleware\EnsureRoleIsSelected::class);
    }
}
