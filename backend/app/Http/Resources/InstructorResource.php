<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'person_code' => $this->user->person_code,
                    'name' => $this->user->name,
                    'surname' => $this->user->surname,
                ];
            }),
            'job_start_date' => $this->job_start_date->format('d.m.Y'),
            'certificate' => $this->whenLoaded('certificate', function () {
                return [
                    'category' => $this->certificate->category->name,
                    'expiration_date' => $this->certificate->expiration_date->format('d.m.Y')
                ];
            })
        ];
    }

    public function toTable($request)
    {

    }
}
