<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date'],
            'status' => ['required'],
            'person_code' => ['required'],
            'instructor_id' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
