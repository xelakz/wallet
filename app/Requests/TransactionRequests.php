<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class TransactionRequests extends FormRequest
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
        $rules['start_date'] = 'required|date';
        $rules['end_date'] = 'required|date|after_or_equal:start_date';

        if (!empty($this->input('currency'))) {
            $rules['currency'] = 'exists:currencies,name';
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
