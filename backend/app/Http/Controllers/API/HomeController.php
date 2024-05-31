<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

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
            ->get();

        return $this->sendResponse($reservations);
    }
}
