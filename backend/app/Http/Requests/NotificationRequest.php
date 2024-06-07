<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'short_message' => ['required'],
            'long_message' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
