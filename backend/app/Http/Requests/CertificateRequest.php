<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'expiration_date' => ['required', 'date'],
            'category_id' => ['required']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
