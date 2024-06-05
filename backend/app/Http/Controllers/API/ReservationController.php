<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Models\instructors_availability;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ReservationController extends Controller
{
    public function getUserReservations(Request $request)
    {
        $user = auth()->user();
        $reservations = Reservation::where('user_person_code', $user->person_code)->get();
        return $this->sendResponse($reservations);
    }

    public function storeReservation(ReservationRequest $request)
    {
        $user = auth()->user();

        $instructorAvailability = instructors_availability::find($request->instructor_availability_id);

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

    public function destroyReservation(int $id)
    {
        return $this->destroy($id, Reservation::class);
    }

    public function generatePdf(Request $request, $reservationId)
    {
        $reservation = Reservation::with('instructor', 'instructor.user', 'instructor.certificate.category')
            ->findOrFail($reservationId);

        $mpdf = new Mpdf(['tempDir' => __DIR__ . '/tmp/pdf']);
        $html = view('pdf.reservation', compact('reservation'))->render();

        $mpdf->WriteHTML($html);

        $filename = 'JJS-rezervacija-' . $reservation->instructor->certificate->category->name . '-' .
            $reservation->instructor->user->name . '_' . $reservation->instructor->user->surname . '-' .
            $reservation->instructorAvailability->start_time->format('d.m.Y_H.i') . '-' . $reservation->instructorAvailability->end_time->format('d.m.Y_H.i') . '.pdf';

        $filename = sanitize_filename($filename);

        $mpdf->setTitle($filename);

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $filename);
    }
}
