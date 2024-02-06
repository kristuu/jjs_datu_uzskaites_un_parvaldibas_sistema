<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'person_code' => ['required', 'string', 'min:11', 'max:11'],
            'name' => ['required', 'string', 'max:60'],
            'surname' => ['required', 'string', 'max:60'],
            'birthdate' => ['required', 'date'],
            'phone' => ['string', 'nullable'],
            'iban_code' => ['string', 'max:64', 'nullable']
        ];

        $rules['birthdate'][] = 'before:' . date('d-m-Y');

        if ($this->method() == 'PUT') {
            $rules['email'] = [
                'required',
                'email'
            ];
        } else {
            $rules['email'] = ['required', 'email', 'unique:users'];
        }

        if ($this->has('password')) {
            $rules['password'] = ['required', 'confirmed', 'min:8'];
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}
