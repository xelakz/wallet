<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OauthToken extends Facade
{
    protected static function getFacadeAccessor() { return 'App\Services\OauthTokenService'; }
}
