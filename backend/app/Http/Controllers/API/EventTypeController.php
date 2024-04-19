<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventTypeRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name'];

    public function getAllEventTypes()
    {
        return $this->getAll(EventType::class, $this->globalFilterFields);
    }

    public function getPaginatedEventTypes(Request $request)
    {
        return $this->getPaginated($request, EventType::class, [], $request->perPage, $this->globalFilterFields);
    }

    public function storeEventType(EventTypeRequest $request)
    {
        return $this->store($request, EventType::class);
    }

    public function findEventTypeById(int $id)
    {
        return $this->findById(EventType::class, $id);
    }

    public function updateEventType(EventTypeRequest $request, string $id)
    {
        return $this->update($request, $id, EventType::class);
    }

    public function destroyEventType(string $id)
    {
        $this->destroy($id, EventType::class);
    }
}
