<?php

namespace App\Services;

use App\Requests\CurrencyRequests;
use Illuminate\Support\Facades\{DB, Log};
use App\Models\Currency;
use Exception;

class CurrencyService
{
    public static function create (CurrencyRequests $request)
    {
        DB::beginTransaction();
        try {
                $currency = new Currency([
                    'name' => $request->name,
                    'is_enabled' => $request->is_enabled,
                ]);

                $currency->updated_at = null;

            if ($currency->save())
            {
                DB::commit();
                return response()->json([
                    'status'      => true,
                    'status_code' => 200,
                    'message'     => 'Currency successfully added.',
                    'data'        => [
                        'name' => $currency->name,
                        'is_enabled' => $currency->is_enabled,
                        'created_at' => $currency->created_at
                    ]
                ], 200);
            }
        }
        catch (Exception $e)
        {
            DB::rollBack();

            Log::info('Creating currency ' . $request->name . ' failed.');
            Log::error($e->getMessage());

            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }

    public function update(CurrencyRequests $request)
    {
        DB::beginTransaction();
        try
        {
            $currency = Currency::where('name', $request->name)->first();

            if ($currency->update(['is_enabled' => $request->is_enabled]))
            {
                DB::commit();
                return response()->json([
                    'status'      => true,
                    'status_code' => 200,
                    'message'     => 'Currency successfully updated.'
                ], 200);
            }
        }
        catch (Exception $e)
        {
            DB::rollBack();

            Log::info('Updating currency ' . $request->name . ' failed.');
            Log::error($e->getMessage());

            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }
}
