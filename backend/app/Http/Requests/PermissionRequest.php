<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/* Dependencies for IBAN bank account number and phone number validation */
use Nembie\IbanRule\ValidIban;
use libphonenumber\PhoneNumberFormat;

class PermissionRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $this->route('id') ?? null],
            'guard_name' => ['required'],
        ];

        return $rules;
    }

    public function messages() {
        return [

        ];
    }

    public function attributes()
    {
        return trans('validation.attributes.permissions');
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'guard_name' => 'web',
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}
