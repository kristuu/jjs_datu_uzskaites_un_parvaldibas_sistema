<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HorseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'passport_number' => ['required', 'integer'],
            'name' => ['required'],
            'birthdate' => ['required', 'date'],
            'breed_id' => ['required', 'integer'],
            'owner_person_code' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
