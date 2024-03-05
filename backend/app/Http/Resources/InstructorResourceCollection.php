<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InstructorResourceCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public function toTable($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user'),
            'reservation' => $this->reservation,
            'certificate' => $this->whenLoaded('certificate', function() {
                $certificate = $this->certificate;
                unset($certificate['id'], $certificate['category']['id']);
            }),
        ];
    }
}
