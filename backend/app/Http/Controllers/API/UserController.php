<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Instructor;
use Illuminate\Support\Facades\Http;
use Session;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use function MongoDB\BSON\toJSON;

class UserController extends Controller
{
    public function getAllInstructorData()
    {
        $users = Instructor::with(['user', 'reservation', 'certificate', 'certificate.category',
            'availability' => function ($query) {
                $query->whereDoesntHave('reservation');
            }
        ])->get();

        if ($users) {
            return response()->json([
                'message' => $users,
            ]);
        } else {
            return response()->json([
                'message' => 'No instructors found',
            ], 404);
        }
    }

    public function paginated(Request $request)
    {
        $perPage = $request->query('perPage', 10);

        $users = User::with(['address', 'address.region', 'address.region.country'])->paginate($perPage);
        $totalUsers = $users->total();

        if ($totalUsers > 0) {

            return response()->json([
                'message' => $users,
            ]);
        } else {
            return response()->json([
                'message' => 'No users found'
            ], 404);
        }
    }

    public function index()
    {
        $users = User::all();

        if ($users->count() > 0) {

            return response()->json([
                'status' => 200,
                'message' => $users
            ], 200);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'No records found'
            ], 404);
        }

    }

    public function store(UserRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        $user = User::create($validated);

        if ($user) {
            return response()->json([
                'status' => 200,
                'message' => 'User created successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'User creation failed'
            ], 500);
        }
    }

    public function findByID(string $person_code)
    {
        $user = User::find($person_code);
        if ($user) {

            return response()->json([
                'status' => 200,
                'message' => $user
            ], 200);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'No user with such person code found'
            ], 404);
        }
    }

    public function edit(?string $person_code)
    {
        $user = User::where('person_code', $person_code)->first();
        if ($user) {

            return response()->json([
                'status' => 200,
                'message' => $user
            ], 200);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'No such user found'
            ], 404);
        }
    }

    public function update(UserRequest $request, string $person_code)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        $user = User::find($person_code);

        if ($user) {
            $user->update($validated);

            return response()->json([
                'status' => 200,
                'message' => 'User updated succesfully'
            ], 200);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'No such user found'
            ], 404);
        }
    }

    public function destroy(string $person_code)
    {
        $user = User::find($person_code);

        if ($user) {
            $user->delete();

            return response()->json([
                'status' => 200,
                'message' => 'User with person code ' . $person_code . ' deleted succesfully'
            ]);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'No such user found'
            ], 404);
        }
    }

}
