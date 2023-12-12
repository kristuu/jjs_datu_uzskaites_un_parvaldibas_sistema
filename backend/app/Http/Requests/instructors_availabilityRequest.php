<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class instructors_availabilityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date'],
            'instructor_id' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
