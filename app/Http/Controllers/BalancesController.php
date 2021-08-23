<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requests\BalanceRequests;
use App\Facades\Balance as BalanceFacade;

class BalancesController extends Controller
{
    public function credit(BalanceRequests $request)
    {
        return BalanceFacade::credit($request);
    }

    public function debit(BalanceRequests $request)
    {
        return BalanceFacade::debit($request);
    }

    public function balance(BalanceRequests $request)
    {
        return BalanceFacade::balance($request);
    }

    public function batch(BalanceRequests $request)
    {
        return BalanceFacade::batch($request);
    }
}
