<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\Event;

class EventController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name', 'start', 'end', 'comment'];
    private array $relationships = ['eventType', 'eventCategory', 'location'];


    public function getAllEvents()
    {
        return $this->getAll(Event::class, $this->globalFilterFields, $this->relationships);
    }

    public function getThisYearsEvents()
    {
        $currentYear = date('Y');
        return response()->json(Event::whereYear('start', $currentYear)->whereYear('end', $currentYear)->get());
    }

    public function getThisYearsEventCount()
    {
        $currentYear = date('Y');
        return response()->json(
            [
                'total' => Event::whereYear('start', $currentYear)->whereYear('end', $currentYear)->count(),
                'completed' => Event::where('end', '<', now())->count(),
                'upcoming' => Event::where('start', '>', now())->count()
            ]
        );
    }

    public function storeEvent(EventRequest $request)
    {
        return $this->store($request, Event::class);
    }

    public function updateEvent(EventRequest $request, string $id)
    {
        return $this->update($request, $id, Event::class);
    }

    public function destroyEvent(string $id)
    {
        return $this->destroy($id, Event::class);
    }

    protected function findEventById($id)
    {
        return $this->findById(Event::class, $id, $this->relationships);
    }
}
