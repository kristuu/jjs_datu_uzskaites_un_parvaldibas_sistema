<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class users_permission_levelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_person_code' => ['required'],
            'permission_level_id' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
