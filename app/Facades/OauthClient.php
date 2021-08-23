<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OauthClient extends Facade
{
    protected static function getFacadeAccessor() { return 'oauthclient'; }
}
