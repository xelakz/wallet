<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use App\Models\{User, Currency};
use Validator;
class AppServiceProvider extends ServiceProvider
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
        Validator::extend('no_invalid_uuid', function($attribute, $value, $parameters, $validator) {
            $userCount = User::wherein('uuid', $value)->count();
            if (($userCount > 0) && ((int) $userCount === (int) count($value)))
            {
                return true;
            }
            return false;
        });

        Validator::extend('is_currency_enabled', function($attribute, $value, $parameters, $validator) {
            $currency = Currency::where(['name' => $value, 'is_enabled' => true])->count();
            if (!empty($currency))
            {
                return true;
            }
            return false;
        });
    }
}
