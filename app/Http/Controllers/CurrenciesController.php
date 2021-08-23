<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requests\CurrencyRequests;
use App\Models\Currency;
use App\Facades\Currency as CurrencyFacade;

class CurrenciesController extends Controller
{
    public function list()
    {
        $currencies = Currency::list();
        return response()->json([
            'status'      => true,
            'status_code' => 200,
            'data'        => $currencies
        ], 200);
    }

    public function create(CurrencyRequests $request)
    {
        return CurrencyFacade::create($request);
    }

    public function update(CurrencyRequests $request)
    {
        return CurrencyFacade::update($request);
    }
}
