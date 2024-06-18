<?php

/*
 * Rezervāciju pārvaldība
 * Funkcijas ietver:
 * - rezervāciju veikšana
 * - rezervāciju atcelšana
 * - rezervāciju PDF ģenerēšana: ģenerē PDF dokumentus ar rezervāciju informāciju, izmantojot speciālu PDF ģenerēšanas bibliotēku
 * - CRUD operācijas ar rezervācijām
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\Instructor;
use App\Models\InstructorAvailability as InstructorAvailability;
use App\Models\Notification;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class ReservationController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['status', 'user_person_code', 'user.name', 'user.surname', 'user.email', 'user.phone', 'user.iban_code'];
    private array $relationships = ['user', 'instructor', 'instructor.user', 'instructor.certificate.category', 'InstructorAvailability'];

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

    public function storeReservation(Request $request)
    {
        $user = auth()->user();

        $instructorAvailability = InstructorAvailability::find($request->instructor_availability_id);

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
        $user = auth()->user();

        $request->validate([
            'reason' => ['required', 'string', 'max:255', 'min: 5'],
        ]);

        $reservation = Reservation::findOrFail($reservationId);
        $reservation->load('instructor.user');

        $reservation->status = 'cancelled';
        $reservation->save();

        $longMessage = view('reservation.reservation_cancelled', [
            'reservation' => $reservation,
            'reason' => $request->reason,
            'toInstructor' => true
        ])->render();

        Notification::create([
            'user_person_code' => $reservation->instructor->user->person_code === $user->person_code ? $reservation->user_person_code : $reservation->instructor->user->person_code,
            'short_message' => "Atcelts rezervācijas pieteikums",
            'long_message' => $longMessage,
        ]);

        return response()->json(['message' => 'Reservation cancelled and notification sent.']);
    }

    public function acceptReservation(Request $request, $reservationId)
    {
        $user = auth()->user();

        $reservation = Reservation::findOrFail($reservationId);
        $reservation->load('instructor.user');

        if (!$reservation->instructor->user_person_code === $user->person_code) {
            return response()->json(['message' => 'Reservation instructors person code does not equal requests users person code'], 403);
        }

        $reservation->status = 'accepted';
        $reservation->save();

        $longMessage = view('reservation.reservation_accepted', [
            'reservation' => $reservation,
            'toInstructor' => false
        ])->render();

        Notification::create([
            'user_person_code' => $reservation->user_person_code,
            'short_message' => "Apstiprināts rezervācijas pieteikums",
            'long_message' => $longMessage,
        ]);

        return response()->json(['message' => 'Reservation accepted and notification sent.']);
    }

    public function denyReservation(Request $request, $reservationId)
    {
        $user = auth()->user();

        $request->validate([
            'reason' => ['required', 'string', 'max:255', 'min: 5'],
        ]);

        $reservation = Reservation::findOrFail($reservationId);
        $reservation->load('instructor.user');

        if (!$reservation->instructor->user_person_code === $user->person_code) {
            return response()->json(['message' => 'Reservation instructors person code does not equal requests users person code'], 403);
        }

        $reservation->status = 'denied';
        $reservation->save();

        $longMessage = view('reservation.reservation_denied', [
            'reservation' => $reservation,
            'reason' => $request->reason,
            'toInstructor' => false
        ])->render();

        Notification::create([
            'user_person_code' => $reservation->user_person_code,
            'short_message' => "Noraidīts rezervācijas pieteikums",
            'long_message' => $longMessage,
        ]);

        return response()->json(['message' => 'Reservation denied and notification sent.']);
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

    public function hasAtleastOneSuccessfulReservation($instructorId)
    {
        $user = auth()->user();

        $successfulReservationCount = Reservation::where('user_person_code', $user->person_code)
            ->where('instructor_id', $instructorId)
            ->where('status', 'accepted')
            ->whereHas('instructorAvailability', function ($query) {
                $query->where('end_time', '<', now());
            })
            ->count();

        if ($successfulReservationCount > 0) {
            return response()->json(['hasSuccessfulReservation' => true], 200);
        } else {
            return response()->json(['hasSuccessfulReservation' => false], 200);
        }
    }

    public function getRating($instructorId)
    {
        $user = auth()->user();
        $ratingFilePath = "ratings/instructor_{$instructorId}.json";

        if (Storage::exists($ratingFilePath)) {
            $ratings = json_decode(Storage::get($ratingFilePath), true);
            if (isset($ratings[$user->person_code])) {
                return response()->json(['rating' => $ratings[$user->person_code]], 200);
            }
        }

        return response()->json(['rating' => null], 200);
    }

    public function rateInstructor(Request $request, $instructorId)
    {
        $user = auth()->user();


        $request->validate([
            'rating' => 'required|min:1|max:5',
        ]);

        $submittedRating = floatval($request->input('rating'));
        $ratingFilePath = "ratings/instructor_{$instructorId}.json";

        if (!Storage::exists('ratings')) {
            Storage::makeDirectory('ratings');
        }

        $ratings = [];
        if (Storage::exists($ratingFilePath)) {
            $ratings = json_decode(Storage::get($ratingFilePath), true);
        }

        $ratings[$user->person_code] = $submittedRating;
        Storage::put($ratingFilePath, json_encode($ratings));

        $totalRating = array_sum($ratings);
        $ratingCount = count($ratings);
        $newRating = $totalRating / $ratingCount;

        $instructor = Instructor::findOrFail($instructorId);
        $instructor->rating = $newRating;
        $instructor->save();

        return response()->json(['message' => 'Rating submitted successfully', 'newRating' => $newRating], 200);
    }
}
