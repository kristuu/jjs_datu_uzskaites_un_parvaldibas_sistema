<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/* Dependencies for IBAN bank account number and phone number validation */

class CityRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:cities,name,' . $this->route('id') ?? null],
            'country_id' => ['required']
        ];

        return $rules;
    }

    public function messages()
    {
        return [

        ];
    }

    public function attributes()
    {
        return trans('validation.attributes.regions');
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}
