<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\InstructorAvailability as InstructorAvailability;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorAvailabilityController extends Controller
{
    /**
     * Get availability list for a specific date and instructor ID.
     */
    public function getAvailabilitiesByDate(Request $request)
    {
        $instructor_id = $request->route("instructor_id");
        $date = $request->route("date");

        // Parse the date
        $parsedDate = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');

        // Fetch availabilities for the specified date and instructor ID that are in the future
        $availabilities = InstructorAvailability::where('instructor_id', $instructor_id)
            ->where(function ($query) use ($parsedDate) {
                $query->whereDate('start_time', $parsedDate)
                    ->orWhereDate('end_time', $parsedDate);
            })
            ->where('start_time', '>', Carbon::now())
            ->get();

        foreach ($availabilities as $availability) {
            $reservation = Reservation::where('instructor_availability_id', $availability->id)
                ->where('instructor_id', $instructor_id)
                ->whereIn('status', ['submitted', 'accepted'])
                ->first();

            $availability->reserved = (bool)$reservation;
        }

        return response()->json($availabilities);
    }

    /**
     * Get availability counts for a specific instructor ID.
     */
    public function getAvailabilityCounts(Request $request, $instructorId)
    {
        $availabilityCounts = DB::table('instructors_availabilities as ia')
            ->leftJoin('reservations as r', 'ia.id', '=', 'r.instructor_availability_id')
            ->select(DB::raw('DATE(ia.start_time) as date'), DB::raw('count(*) as count'))
            ->where('ia.instructor_id', $instructorId)
            ->where('ia.start_time', '>', Carbon::now())
            ->where(function ($query) {
                $query->whereNull('r.status')
                    ->orWhereNotIn('r.status', ['submitted', 'accepted']);
            })
            ->groupBy(DB::raw('DATE(ia.start_time)'))
            ->get();

        return response()->json($availabilityCounts);
    }

    public function addNewAvailability(Request $request, $instructorId)
    {
        $instructor = Instructor::find($instructorId);

        if (!$instructor) {
            return response()->json(['message' => 'Instructor not found'], 404);
        }

        $request->validate([
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'price' => ['required', 'numeric', 'gte:0']
        ]);

        $overlappingAvailability = InstructorAvailability::where('instructor_id', $instructorId)
            ->where(function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhereRaw('? > start_time AND ? < end_time', [$request->start_time, $request->start_time])
                        ->orWhereRaw('? > start_time AND ? < end_time', [$request->end_time, $request->end_time]);
                })
                    ->whereRaw('start_time <> ?', [$request->end_time])
                    ->whereRaw('end_time <> ?', [$request->start_time]);
            })
            ->exists();

        if ($overlappingAvailability) {
            return response()->json(['errors' => ['end_time' => ['Pieejamība pārklājas ar citu jau esošu pieejamību']]], 422);
        }

        $instructorAvailability = InstructorAvailability::create([
            'instructor_id' => $instructorId,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'price' => $request->price,
        ]);

        return response()->json(['message' => 'Availability added successfully', 'data' => $instructorAvailability], 201);
    }

    public function deleteAvailability(Request $request, $availabilityId)
    {
        $user = auth()->user();

        $instructorAvailability = InstructorAvailability::with('instructor.user')
            ->find($availabilityId);

        if (!$instructorAvailability) {
            return response()->json(['message' => 'Availability not found'], 404);
        }

        if ($instructorAvailability->instructor->user->person_code !== $user->person_code) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $instructorAvailability->delete();

        return response()->json(['message' => 'Availability deleted successfully'], 200);
    }
}
