<?php

namespace App\Services;

use App\Models\{Transaction, Currency};
use App\Requests\TransactionRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class TransactionService
{
    public static function transactions(TransactionRequests $request)
    {
        try
        {
            $where = [
                'uuid' => $request->uuid
            ];
            if (!empty($request->currency)) {
                $currencyId = Currency::getCurrencyId($request->currency);
                $where = array_merge($where, ['currency_id' => $currencyId]);
            }
            $output = [];
            $transactions = Transaction::where($where)
                ->whereDate('created_at', '>=', Carbon::parse($request->start_date)->toDateString())
                ->whereDate('created_at', '<=', Carbon::parse($request->end_date)->toDateString())
                ->orderBy('created_at', 'DESC')
                ->get();

            if (!empty($transactions)) {
                foreach($transactions as $transaction) {
                    if (!empty($request->currency)) {
                        $output[] = [
                            'amount'    => $transaction->amount,
                            'type'      => $transaction->type,
                            'reason'    => $transaction->reason,
                            'timestamp' => $transaction->created_at
                        ];
                    }
                    else {
                        $currency = Currency::getNameById($transaction->currency_id);
                        $output[$currency][] = [
                            'amount'    => $transaction->amount,
                            'type'      => $transaction->type,
                            'reason'    => $transaction->reason,
                            'timestamp' => $transaction->created_at
                        ];
                    }

                }
            }

            return response()->json([
                'status'      => true,
                'status_code' => 200,
                'data'        => !empty($output) ? $output : null
            ], 200);
        }
        catch (Exception $e)
        {
            Log::info('Viewing transactions for uuid: ' . $request->uuid . ' failed.');
            Log::error($e->getMessage());
            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }
}
