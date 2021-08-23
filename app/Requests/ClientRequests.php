<?php

namespace App\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ClientRequests extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
                'client_id'     => 'required|max:32|alpha_num',
                'client_secret' => 'required|max:32|alpha_num'
            ];

        $path = $this->path();

        if ($path == 'api/v1/client/create') {
            $rules['client_id'] .= '|unique:oauth_clients,client_id';
            $rules['name'] = 'required|max:100|unique:oauth_clients,name';
        } else {
            $rules['client_id'] .= '|exists:oauth_clients,client_id';
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
