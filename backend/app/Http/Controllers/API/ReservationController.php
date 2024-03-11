<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\ReservationRequest;
use App\Models\Category;
use App\Models\instructors_availability;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function storeReservation(ReservationRequest $request)
    {
        $user = auth()->user();

        $instructorAvailability = instructors_availability::find($request->id);

        if ($instructorAvailability) {
            $reservation = Reservation::create([
                'status' => 'submitted',
                'user_person_code' => $user->person_code,
                'instructor_id' => $instructorAvailability->instructor_id,
                'instructor_availability_id' => $instructorAvailability->id
            ]);

            if ($reservation) {
                return response()->json([
                    'message' => 'Reservation submitted successfully'
                ]);
            } else {
                return response()->json([
                    'message' => 'Reservation submission failed'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Instructor availability not found'
            ], 404);
        }
    }
}
