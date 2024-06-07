<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\instructors_availability;
use App\Models\Notification;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ReservationController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['status', 'user_person_code', 'user.name', 'user.surname', 'user.email', 'user.phone', 'user.iban_code'];
    private array $relationships = ['user', 'instructor', 'instructor.user', 'instructor.certificate.category', 'instructorAvailability'];

    public function getAllReservations()
    {
        return $this->getAll(Reservation::class, $this->globalFilterFields, $this->relationships);
    }

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
                    'message' => 'Reservation submitted successfully',
                    'data' => $reservation
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

    public function findReservationById($id)
    {
        return $this->findById(Reservation::class, $id, $this->relationships);
    }

    public function updateReservation(ReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->validated());
        return response()->json(['message' => 'Reservation updated successfully', 'data' => $reservation]);
    }

    public function cancelReservation(Request $request, $reservationId)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $reservation = Reservation::findOrFail($reservationId);
        $reservation->status = 'cancelled';
        $reservation->save();

        Notification::create([
            'user_person_code' => $reservation->instructor->user->person_code,
            'short_message' => "Atcelts rezervācijas pieteikums",
            'long_message' => 'Your reservation has been cancelled. Reason: ' . $request->reason,
        ]);

        return response()->json(['message' => 'Reservation cancelled and notification sent.']);
    }

    public function generatePdf(Request $request, $reservationId)
    {
        $reservation = Reservation::with('instructor', 'instructor.user', 'instructor.certificate.category')
            ->findOrFail($reservationId);

        $start_time = $reservation->instructorAvailability->start_time;
        $end_time = $reservation->instructorAvailability->end_time;

        $mpdf = new Mpdf(['tempDir' => __DIR__ . '/tmp/pdf']);
        $html = view('pdf.reservation', compact('reservation', 'start_time', 'end_time'))->render();

        $mpdf->WriteHTML($html);

        $filename = 'JJS-rezervacija-' . $reservation->instructor->certificate->category->name . '-' .
            $reservation->instructor->user->name . '_' . $reservation->instructor->user->surname . '-' .
            $start_time->format('d.m.Y_H.i') . '-' . $end_time->format('d.m.Y_H.i') . '.pdf';

        $filename = sanitize_filename($filename);

        $mpdf->setTitle($filename);

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $filename);
    }
}
