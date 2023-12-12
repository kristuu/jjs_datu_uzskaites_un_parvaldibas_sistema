<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstructorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'job_start_date' => ['required', 'date'],
            'user_person_code' => ['required', 'integer'],
            'certificate_id' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
