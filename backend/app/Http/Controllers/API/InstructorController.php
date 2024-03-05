<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstructorRequest;
use App\Http\Resources\InstructorResource;
use App\Http\Resources\InstructorResourceCollection;
use App\Http\Traits\PaginationTrait;

use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = [];
    private array $relationships = [];

    public function __construct() {
        $this->relationships = ['user', 'reservation', 'certificate', 'certificate.category',
            'availability' => function ($query) {
                $query->whereDoesntHave('reservation');
            }
        ];
    }

    public function getAllInstructors()
    {
        $instructors = $this->getAll(
            Instructor::class,
            $this->globalFilterFields,
            $this->relationships,
        );

        $instances = InstructorResourceCollection::collection($instructors)->toTable(request());

        $this->responseInstances($instances, $this->globalFilterFields);
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
