<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Requests\BalanceRequests;
use Illuminate\Support\Facades\{DB, Log};
use App\Models\{User, Balance, Transaction, Currency};
use Exception;

class BalanceService
{
    public static function credit(BalanceRequests $request)
    {
        DB::beginTransaction();
        try
        {
            $isExistingUser = User::where('uuid', $request->uuid)->first();

            if (empty($isExistingUser))
            {
                $user = new User([
                    'uuid' => $request->uuid
                ]);
                $user->save();
            }

            $currencyId = Currency::getCurrencyId($request->currency);

            $balance = Balance::where('uuid', $request->uuid)
                    ->where('currency_id', $currencyId)
                    ->first();

            if (!empty($balance))
            {
                $balance->where('uuid', $request->uuid)->update(['balance' => $balance->balance + $request->amount]);
            }
            else {
                $balance = new Balance([
                    'uuid'          => $request->uuid,
                    'currency_id'   => $currencyId,
                    'balance'       => $request->amount
                ]);

            }
            $balance->save();

            $transaction = new Transaction([
                'uuid'      => $request->uuid,
                'type'      => 'credit',
                'reason'    => $request->reason,
                'amount'    => $request->amount,
                'currency_id' => $currencyId
            ]);

            if ($transaction->save())
            {
                DB::commit();
                return response()->json([
                    'status'      => true,
                    'status_code' => 200,
                    'message'   => 'Amount successfully credited.',
                    'data'        => [
                        'id'        => $transaction->id,
                        'type'      => $transaction->type,
                        'amount'    => $transaction->amount,
                        'reason'    => $transaction->reason,
                        'timestamp' => $transaction->created_at
                    ]
                ], 200);
            }
        }
        catch (Exception $e)
        {
            DB::rollBack();

            Log::info('Creating balances for uuid ' . $request->uuid . ' failed.');
            Log::error($e->getMessage());
            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }

    public static function debit(BalanceRequests $request)
    {
        DB::beginTransaction();
        try
        {
            $currencyId = Currency::getCurrencyId($request->currency);

            $balance = Balance::where(['uuid' => $request->uuid, 'currency_id' => $currencyId])->first();
            $newBalance = $balance->balance - $request->amount;
            $balance->where(['uuid' => $request->uuid, 'currency_id' => $currencyId])->update(['balance' => $newBalance]);

            $transaction = new Transaction([
                'uuid'      => $request->uuid,
                'type'      => 'debit',
                'reason'    => $request->reason,
                'amount'    => $request->amount,
                'currency_id' => $currencyId
            ]);

            if ($transaction->save())
            {
                DB::commit();
                return response()->json([
                    'status'      => true,
                    'status_code' => 200,
                    'message'   => 'Amount successfully debited.',
                    'data'        => [
                        'id'        => $transaction->id,
                        'type'      => $transaction->type,
                        'amount'    => $transaction->amount,
                        'reason'    => $transaction->reason,
                        'timestamp' => $transaction->created_at
                    ]
                ], 200);
            }
        }
        catch (Exception $e)
        {
            DB::rollBack();

            Log::info('Deducting balances for uuid ' . $request->uuid . ' failed.');
            Log::error($e->getMessage());
            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }

    public static function balance(BalanceRequests $request)
    {
        try
        {
            $where = ['uuid' => $request->uuid];
            if (!empty($request->currency)) {
                $currencyId = Currency::getCurrencyId($request->currency);
                $where = array_merge($where, ['currency_id' => $currencyId]);
            }

            $output   = [];
            $balances = Balance::where($where)->get();
            if (!empty($balances)) {
                foreach($balances as $balance) {
                    if (!empty($request->currency)) {
                        $output = [
                            'balance'   => $balance->balance,
                            'currency'  => $request->currency,
                            'timestamp' => $balance->updated_at
                        ];
                    }
                    else {
                        $currency = Currency::getNameById($balance->currency_id);
                        $output[$currency] = [
                            'balance'   => $balance->balance,
                            'currency'  => $currency,
                            'timestamp' => $balance->updated_at
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
            Log::info('Viewing balances for uuid: ' . $request->uuid . ', currency: '. $request->currency .' failed.');
            Log::error($e->getMessage());
            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }

    public static function batch(BalanceRequests $request)
    {
        try
        {
            $where = null;
            if (!empty($request->currency)) {
                $currencyId = Currency::getCurrencyId($request->currency);
                $where = ['currency_id' => $currencyId];
            }
            else {
                $currencies = Currency::getAllCurrencies();
            }

            $balances = Balance::whereIn('uuid', $request->uuids)
                        ->where($where)
                        ->get();

            if (!empty($balances))
            {
                foreach($balances as $balance)
                {
                    if (!empty($request->currency)) {
                        $output[$balance->uuid] = [
                            'balance'   => $balance->balance,
                            'currency'  => $request->currency,
                            'timestamp' => $balance->updated_at
                        ];
                    }
                    else {
                        $currency = $currencies[$balance->currency_id];
                        $output[$balance->uuid][$currency] = [
                            'balance'   => $balance->balance,
                            'currency'  => $currency,
                            'timestamp' => $balance->updated_at
                        ];
                    }
                }
            }
            return response()->json([
                'status'      => true,
                'status_code' => 200,
                'data'        => $output
            ], 200);
        }
        catch (Exception $e)
        {
            Log::info('Viewing balances for uuids: ' . $request->uuids . ' failed.');
            Log::error($e->getMessage());
            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }
}
