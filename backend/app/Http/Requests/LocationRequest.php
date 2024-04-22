<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'stables' => ['boolean'],
            'competitions' => ['boolean'],
            'horse_capacity' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
