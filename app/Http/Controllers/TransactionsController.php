<?php

namespace App\Http\Controllers;

use App\Requests\TransactionRequests;
use App\Facades\Transaction as TransactionFacade;

class TransactionsController extends Controller
{
    public function transactions(TransactionRequests $request)
    {
        return TransactionFacade::transactions($request);
    }
}
