<?php

namespace App\Providers;

use App\Services\BalanceService;
use Illuminate\Support\ServiceProvider;

class BalanceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Services\BalanceService', function() {
            return new BalanceService;
        });
    }
}
