<?php

namespace App\Requests;

use App\Models\{Balance, Currency};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class BalanceRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $path = $this->path();
        $rules['uuid'] = 'required|max:36';
        $rules['currency'] = 'required|max:3|exists:currencies,name|is_currency_enabled';
        $rules['amount'] = 'required|numeric';
        $rules['reason'] = 'required';

        switch($path) {
            case 'api/v1/wallet/debit' : {
                $currencyId = Currency::getCurrencyId($this->input('currency'));
                if (!empty($currencyId)) {
                    $balance = Balance::where(['uuid' => $this->input('uuid'), 'currency_id' => $currencyId])->first()->balance;
                    $rules['amount'] .= '|lte:'.$balance;
                }
                break;
            }
            case 'api/v1/wallet/balance' : {
                $rules['currency'] = $rules['amount'] = $rules['reason'] = [];
                break;
            }
            case 'api/v1/wallet/balance/batch' : {
                $rules['uuids'] = 'required|no_invalid_uuid';
                $rules['uuid'] = $rules['currency'] = $rules['amount'] = $rules['reason'] = [];
            }
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status'      => false,
            'status_code' => 400,
            'errors'      => $validator->errors(),
        ], 400);

        throw new ValidationException($validator, $response);
    }

    public function messages()
    {
        return [
            'uuids.no_invalid_uuid' => 'The supplied :attribute have invalid values.',
            'currency.is_currency_enabled' => 'The selected :attribute is currently disabled.'
        ];
    }
}
