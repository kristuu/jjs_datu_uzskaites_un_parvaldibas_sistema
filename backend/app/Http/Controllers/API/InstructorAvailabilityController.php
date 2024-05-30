<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\instructors_availability as InstructorAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InstructorAvailabilityController extends Controller
{
    /**
     * Get availability list for a specific date.
     */
    public function getAvailabilitiesByDate(Request $request, $date)
    {
        // Parse the date
        $parsedDate = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');

        // Fetch availabilities for the specified date
        $availabilities = InstructorAvailability::whereDate('start_time', $parsedDate)
            ->orWhereDate('end_time', $parsedDate)
            ->get();

        return response()->json($availabilities);
    }
}
