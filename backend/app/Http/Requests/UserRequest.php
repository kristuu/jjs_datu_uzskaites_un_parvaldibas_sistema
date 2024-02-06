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
        $userPersonCode = $this->route('id');

        $rules = [
            'person_code' => ['required', 'string', 'size:11'],
            'name' => ['required', 'string', 'max:60', 'regex:/^[\pL\s\-]+$/u'],
            'surname' => ['required', 'string', 'max:60', 'regex:/^[\pL\s\-]+$/u'],
            'birthdate' => ['required', 'date'],
            'email' => ['required', 'max:60', 'email', "unique:users,email,{$userPersonCode},person_code"],   // check for existing emails excluding the modifiable user
            'phone' => ['string', 'max:32', 'nullable'],
            'iban_code' => ['string', 'max:255', 'nullable']
        ];

        $rules['birthdate'][] = 'before:' . date('d-m-Y');

        if ($this->has('password')) {
            $rules['password'] = ['required', 'confirmed', 'min:8'];
        }

        return $rules;
    }

    public function messages() {
        $messages = [
            'name.regex' => 'Lauks ":attribute" var saturēt tikai burtus, atstarpes un domuzīmes',
            'person_code.size' => 'Lauka ":attribute" garumam jābūt 11 ciparu, neieskaitot domuzīmi',
        ];

        return $messages;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}
