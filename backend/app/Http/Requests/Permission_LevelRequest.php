<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Permission_LevelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
