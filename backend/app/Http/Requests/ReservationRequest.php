<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => ['required', 'in:submitted,accepted,denied'],
            'user_person_code' => ['required', 'exists:users,person_code'],
            'instructor_id' => ['required', 'exists:instructors,id'],
            'instructor_availability_id' => ['required', 'exists:instructors_availabilities,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
