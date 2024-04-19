<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'stables' => ['required', 'boolean'],
            'competitions' => ['required', 'boolean'],
            'horse_capacity' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
