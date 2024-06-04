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
            ->where('user_person_code', $user->person_code)
            ->where('status', '!=', 'denied')
            ->whereHas('instructorAvailability', function ($query) {
                $query->where('end_time', '>=', now());
            })
            ->join('instructors_availabilities', 'reservations.instructor_availability_id', '=', 'instructors_availabilities.id')
            ->orderBy(DB::raw('instructors_availabilities.start_time'), 'asc')
            ->take(3)
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
            ->where('user_person_code', $user->person_code)
            ->join('instructors_availabilities', 'reservations.instructor_availability_id', '=', 'instructors_availabilities.id')
            ->orderBy(DB::raw('instructors_availabilities.start_time'), 'asc')
            ->get(['reservations.*']);

        return $this->sendResponse($reservations);
    }
}
