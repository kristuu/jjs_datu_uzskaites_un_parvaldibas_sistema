<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\Event;
use Carbon\Carbon;
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
        try {
            $requestData = $request->validated(); // Extracting validated data

            // Parsing and modifying the dates
            $start = Carbon::parse($requestData['start'])->setTimezone('Europe/Riga')->format('Y-m-d H:i:s');
            $end = Carbon::parse($requestData['end'])->setTimezone('Europe/Riga')->format('Y-m-d H:i:s');

            // Replace the old dates with the modified ones
            $requestData['start'] = $start;
            $requestData['end'] = $end;

            // Create the event
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
                'message' => 'Error occurred while creating the ' . class_basename($className),
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function updateEvent(EventRequest $request, string $id)
    {
        $requestData = $request->validated(); // Extracting validated data

        // Parsing and modifying the dates
        $start = Carbon::parse($requestData['start'])->setTimezone('Europe/Riga')->format('Y-m-d H:i:s');
        $end = Carbon::parse($requestData['end'])->setTimezone('Europe/Riga')->format('Y-m-d H:i:s');

        // Replace the old dates with the modified ones
        $requestData['start'] = $start;
        $requestData['end'] = $end;

        // Create the event
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
