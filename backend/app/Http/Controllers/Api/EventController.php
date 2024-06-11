<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\Event;
use App\Models\EventType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name', 'start', 'end', 'comment'];
    private array $relationships = ['eventType', 'eventCategory', 'location'];


    public function getAllEvents()
    {
        return $this->getAll(Event::class, $this->globalFilterFields, $this->relationships);
    }

    public function getUpcomingEvents()
    {
        $currentDate = Carbon::now();

        $competitions = Event::whereHas('eventType', function ($query) {
            $query->where('name', '=', 'Sacensības / Competition');
        })->with(['eventType', 'eventCategory', 'location'])->whereDate('end', '>=', $currentDate)->orderBy('start', 'asc')->limit(3)->get();

        $seminars = Event::whereHas('eventType', function ($query) {
            $query->where('name', '=', 'Seminārs / Seminar');
        })->with(['eventType', 'eventCategory', 'location'])->whereDate('end', '>=', $currentDate)->orderBy('start', 'asc')->limit(3)->get();

        return response()->json([
            'competitions' => $competitions,
            'seminars' => $seminars,
        ]);
    }

    public function getThisYearsEventCount()
    {
        $currentYear = date('Y');

        $competitions = Event::whereHas('eventType', function ($query) {
            $query->where('name', 'Sacensības / Competition');
        })->whereYear('start', $currentYear)->count();

        $seminars = Event::whereHas('eventType', function ($query) {
            $query->where('name', 'Seminārs / Seminar');
        })->whereYear('start', $currentYear)->count();

        return response()->json([
            'competitions' => $competitions,
            'seminars' => $seminars,
        ]);
    }

    public function getEventCountByMonth()
    {
        $eventTypes = EventType::with('events')->get();

        $data = [];

        foreach ($eventTypes as $eventType) {
            $events = DB::table('events')->select(
                'event_types.name',
                DB::raw('YEAR(events.start) as year'),
                DB::raw('MONTH(events.start) as month'),
                DB::raw('COUNT(*) as count')
            )
                ->join('event_types', 'events.event_type_id', '=', 'event_types.id')
                ->where('event_types.id', $eventType->id)
                ->groupBy('event_types.name', 'year', 'month')
                ->get()
                ->groupBy('year')
                ->mapWithKeys(function ($yearGroup, $year) {
                    $monthArray = $yearGroup->keyBy('month')->map->count->toArray();
                    return [$monthArray + array_fill(1, 12, 0)];
                })
                ->toArray();

            $data[$eventType->name] = $events;
        }

        return response()->json($data);
    }

    public function getEventPercentageThisYear()
    {
        $currentYear = date('Y');
        $currentDate = new \DateTime();

        $allCompetitionsCount = Event::whereHas('eventType', function ($query) {
            $query->where('name', '=', 'Sacensības / Competition');
        })->whereYear('start', $currentYear)->count();

        $passedCompetitionsCount = Event::whereHas('eventType', function ($query) {
            $query->where('name', '=', 'Sacensības / Competition');
        })->whereYear('start', $currentYear)->whereDate('end', '<=', $currentDate)->count();

        $allSeminarsCount = Event::whereHas('eventType', function ($query) {
            $query->where('name', '=', 'Seminārs / Seminar');
        })->whereYear('start', $currentYear)->count();

        $passedSeminarsCount = Event::whereHas('eventType', function ($query) {
            $query->where('name', '=', 'Seminārs / Seminar');
        })->whereYear('start', $currentYear)->whereDate('end', '<=', $currentDate)->count();

        return response()->json([
            'seminars' => $passedSeminarsCount > 0 ? $passedSeminarsCount / $allSeminarsCount * 100 : 0,
            'competitions' => $passedCompetitionsCount > 0 ? $passedCompetitionsCount / $allCompetitionsCount * 100 : 0
        ]);
    }

    public function getThisYearsEvents()
    {
        $currentYear = date('Y');
        return response()->json(Event::whereYear('start', $currentYear)->whereYear('end', $currentYear)->get());
    }

    public function storeEvent(EventRequest $request)
    {
        try {
            $requestData = $request->validated();

            $start = Carbon::parse($requestData['start'])->setTimezone('Europe/Riga')->format('Y-m-d H:i:s');
            $end = Carbon::parse($requestData['end'])->setTimezone('Europe/Riga')->format('Y-m-d H:i:s');

            $requestData['start'] = $start;
            $requestData['end'] = $end;

            $instance = Event::create($requestData);

            if ($instance) {
                return $this->sendResponse(['message' => __('validation.instance.creation', [
                    'model' => __('validation.models.' . class_basename(Event::class))
                ])]);
            } else {
                return $this->sendResponse(['message' => __('`validation.instance.creation_failed', [
                    'model' => __('validation.models.' . class_basename(Event::class))
                ])], 500);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendResponse([
                'message' => 'Error occurred while creating the ' . class_basename(Event::class),
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function updateEvent(EventRequest $request, string $id)
    {
        $requestData = $request->validated();

        $start = Carbon::parse($requestData['start'])->setTimezone('Europe/Riga')->format('Y-m-d H:i:s');
        $end = Carbon::parse($requestData['end'])->setTimezone('Europe/Riga')->format('Y-m-d H:i:s');

        $requestData['start'] = $start;
        $requestData['end'] = $end;

        $instance = Event::find($id);

        if ($instance) {
            $instance->update($requestData);

            return $this->sendResponse(['message' => __('validation.instance.updated', [
                'model' => __('validation.models.' . class_basename(Event::class))
            ])]);
        } else {
            return $this->sendResponse(['message' => __('validation.instance.not_found', [
                'model' => __('validation.models.' . class_basename(Event::class)),
                'id' => $id])], 404);
        }
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
