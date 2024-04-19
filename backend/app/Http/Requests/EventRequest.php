<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:start'],
            'event_category_id' => ['required', 'exists:event_categories'],
            'event_type_id' => ['required', 'exists:event_types'],
            'location_id' => ['required', 'exists:locations'],
            'comment' => ['string', 'max:4294967295'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
