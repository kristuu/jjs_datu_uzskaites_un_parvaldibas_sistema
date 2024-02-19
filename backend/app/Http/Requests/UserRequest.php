<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/* Dependencies for IBAN bank account number and phone number validation */
use Nembie\IbanRule\ValidIban;
use libphonenumber\PhoneNumberFormat;

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
        $userPersonCode = $this->route('id');

        $rules = [
            'person_code' => ['required', 'string', 'size:11'],
            'name' => ['required', 'string', 'max:60', 'regex:/^[\pL\s\-]+$/u'],
            'surname' => ['required', 'string', 'max:60', 'regex:/^[\pL\s\-]+$/u'],
            'birthdate' => ['required', 'date'],
            'email' => ['required', 'max:60', 'email', "unique:users,email,{$userPersonCode},person_code"],   // check for existing emails excluding the modifiable user
            'phone' => ['phone', 'required', 'max:36'],
            'iban_code' => ['max:255', 'nullable', new ValidIban()],
        ];

        $rules['birthdate'][] = 'before:' . date('d-m-Y');

        if ($this->method() === 'POST') {
            $rules['password'] = ['required', 'confirmed', 'min:8'];
            $rules['person_code'] += ["unique:users,person_code,{$userPersonCode},person_Code"];
        }

        return $rules;
    }

    public function messages() {
        return [
            'name.regex' => 'Lauks ":attribute" var saturēt tikai burtus, atstarpes un domuzīmes',
            'person_code.size' => 'Lauka ":attribute" garumam jābūt 11 ciparu, neieskaitot domuzīmi',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}
