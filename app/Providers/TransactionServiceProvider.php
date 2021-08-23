<?php

namespace App\Providers;

use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Services\TransactionService', function() {
            return new TransactionService;
        });
    }
}
