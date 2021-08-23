<?php

namespace App\Providers;

use App\Services\CurrencyService;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Services\CurrencyService', function() {
            return new CurrencyService;
        });
    }
}
