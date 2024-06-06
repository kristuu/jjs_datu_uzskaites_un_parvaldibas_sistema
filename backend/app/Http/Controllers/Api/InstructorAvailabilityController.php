<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\instructors_availability as InstructorAvailability;
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
    public function getAvailabilityCounts(Request $request)
    {
        $instructor_id = $request->route("instructor_id");

        $availabilityCounts = DB::table('instructors_availabilities as ia')
            ->leftJoin('reservations as r', 'ia.id', '=', 'r.instructor_availability_id')
            ->select(DB::raw('DATE(ia.start_time) as date'), DB::raw('count(*) as count'))
            ->where('ia.instructor_id', $instructor_id)
            ->where('ia.start_time', '>', Carbon::now())
            ->where(function ($query) {
                $query->whereNull('r.status')
                    ->orWhereNotIn('r.status', ['submitted', 'accepted']);
            })
            ->groupBy(DB::raw('DATE(ia.start_time)'))
            ->get();

        return response()->json($availabilityCounts);
    }
}
