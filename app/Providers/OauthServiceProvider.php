<?php

namespace App\Providers;

use App\Services\{OauthClientService, OauthTokenService};
use Illuminate\Support\ServiceProvider;

class OauthServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Services\OauthTokenService', function() {
            return new OauthTokenService;
        });

        $this->app->bind('oauthclient', function () {
            return new OauthClientService;
        });
    }
}
