<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstructorRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\Instructor;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = [];
    private array $relationships = [];

    public function __construct()
    {
        /*$this->relationships = ['user', 'reservation', 'certificate', 'certificate.category',
            'availability' => function ($query) {
                $query->whereDoesntHave('reservation');
            }
        ];*/

        $this->relationships = ['user', 'certificate', 'certificate.category'];
    }

    public function getAllInstructors()
    {
        return $this->getAll(
            Instructor::class,
            $this->globalFilterFields,
            $this->relationships,
        );
    }

    public function getPaginatedInstructors(Request $request)
    {
        //
    }

    public function storeInstructor(InstructorRequest $request)
    {
        return $this->store($request, Instructor::class);
    }

    public function findInstructorById(string $id)
    {
        return $this->findById(Instructor::class, $id, $this->relationships);
    }

    public function updateInstructor(InstructorRequest $request, string $id)
    {
        return $this->update($request, $id, Instructor::class);
    }

    public function destroyInstructor(string $id)
    {
        return $this->destroy($id, Instructor::class);
    }

    public function getReservations()
    {
        $instructor = Auth::user();

        $reservations = Reservation::where('instructor_id', $instructor->id)->with('user')->get();

        return response()->json($reservations);
    }

    public function manageReservation(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);

        $reservation = Reservation::findOrFail($id);
        
        $reservation->status = $request->status;
        $reservation->save();

        return response()->json(['message' => 'Reservation updated successfully']);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
