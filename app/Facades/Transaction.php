<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Transaction extends Facade
{
    protected static function getFacadeAccessor() { return 'App\Services\TransactionService'; }
}
