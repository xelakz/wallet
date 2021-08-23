<?php

namespace App\Requests;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CurrencyRequests extends FormRequest
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
        $rules['name'] = 'required|max:3';

        if ($path == 'api/v1/currency/create') {
            $rules['name'] .= '|unique:currencies,name';
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
}
