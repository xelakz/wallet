<?php

namespace App\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class OauthTokenRequests extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'grant_type'    => 'required|in:client_credentials,refresh_token',
            'client_id'     => 'required',
            'client_secret' => 'required',
            'scope'         => 'nullable'
        ];

        $grantType = $this->route('grant_type');

        if (!empty($grantType) && $grantType == 'refresh_token') {
            $rules['refresh_token'] == 'required';
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
