<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstructorRequest;
use App\Http\Traits\PaginationTrait;

use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = [];
    private array $relationships = [];

    public function __construct() {
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
        $instructor = Instructor::find($id);


    }

    public function destroyInstructor(string $id)
    {
        return $this->destroy($id, Instructor::class);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
