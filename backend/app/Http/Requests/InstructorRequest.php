<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstructorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'job_start_date' => ['required', 'date'],
            'user_person_code' => ['required', ''],
            'certificate_id' => ['required', 'integer'],
            'short_description' => ['nullable', 'max:100'],
            'description' => ['nullable', 'max:65535']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
