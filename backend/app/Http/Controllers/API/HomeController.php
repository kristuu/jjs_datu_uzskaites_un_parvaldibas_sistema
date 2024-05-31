<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $reservations = Reservation::with([
            'instructor.user',
            'instructor.certificate.category',
            'instructorAvailability'
        ])
            ->whereHas('instructor', function ($query) use ($user) {
                $query->where('user_person_code', $user->person_code);
            })
            ->where('status', '!=', 'denied') // Exclude denied reservations for small reservation list
            ->whereHas('instructorAvailability', function ($query) {
                $query->where('end_time', '>=', now()); // Exclude past reservations
            })
            ->join('instructors_availabilities', 'reservations.instructor_availability_id', '=', 'instructors_availabilities.id')
            ->orderBy(DB::raw('DATE(instructors_availabilities.start_time)'), 'asc') // Order by date
            ->get(['reservations.*']);

        return $this->sendResponse($reservations);
    }

    public function getAllReservations()
    {
        $user = auth()->user();

        $reservations = Reservation::with([
            'instructor.user',
            'instructor.certificate.category',
            'instructorAvailability'
        ])
            ->whereHas('instructor', function ($query) use ($user) {
                $query->where('user_person_code', $user->person_code);
            })
            ->join('instructors_availabilities', 'reservations.instructor_availability_id', '=', 'instructors_availabilities.id')
            ->orderBy(DB::raw('DATE(instructors_availabilities.start_time)'), 'asc') // Order by date
            ->get(['reservations.*']); // Select only columns from reservations

        return $this->sendResponse($reservations);
    }
}
