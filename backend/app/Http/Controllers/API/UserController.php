<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Instructor;
use App\Models\Region;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\PaginationTrait;
use Propaganistas\LaravelPhone\PhoneNumber;
use Propaganistas\LaravelPhone\Exceptions\NumberParseException as libNumberParseException;
use App\Exceptions\PhoneNumberException;

class UserController extends Controller
{
    use PaginationTrait;

    const STATUS_MESSAGE = [
        "no_records_found" => 'No records found',
        "no_such_user_found" => 'No such user found',
        "no_intrustors_found" => 'No instructors found'
        // add your more messages here
    ];

    public function getAllInstructorData()
    {
        $users = Instructor::with(['user', 'reservation', 'certificate', 'certificate.category',
            'availability' => function ($query) {
                $query->whereDoesntHave('reservation');
            }
        ])->get();

        if ($users) {
            return response()->json(['message' => $users]);
        }

        return $this->getResponseWithMessage(self::STATUS_MESSAGE['no_instructors_found'], 404);
    }

    public function getAllUsers()
    {
        return $this->getAll(User::class);
    }

    public function getPaginatedUsers(Request $request)
    {
        return $this->getPaginated($request,
                          User::class,
                                   ['address', 'address.region', 'address.region.country'],
                                   $request->perPage);
    }

    public function storeUser(UserRequest $request)
    {
        return $this->store($request, User::class);
    }

    private function findUserById(string $person_code)
    {
        return $this->findById($person_code, User::class);
    }

    public function update(UserRequest $request, string $person_code)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        try {
            $tempPhone = new PhoneNumber($validated['phone']);
            $validated['phone'] = $tempPhone->formatE164();
        } catch (libNumberParseException $e) {
            if ($e->getErrorType() === libNumberParseException::INVALID_COUNTRY_CODE) {
                return response()->json([
                    'phone' => [
                        trans('validation.exceptions.country_required',
                                   ['attribute' => 'Telefona numurs'])
                    ],
                ], 422);
            }
        }

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

    public function setAddress(Request $request)
    {
        $user = auth()->user();

        $country = Country::firstOrCreate(['name' => $request->country->name]);
        $region = Region::firstOrCreate(['name' => $request->region->name]);
        $user->update([
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'region' => $region->id,
            'country' => $country->id
        ]);

        return response()->json([
            'message' => 'Address updated succesfully'
        ], 200);
    }

    public function destroy(string $person_code)
    {
        $response = $this->findUserById($person_code);

        if ($response->getData()->status === 404) {
            return $response;
        }

        User::find($person_code)->delete();

        return $this->getResponseWithMessage('User with person code ' . $person_code . ' deleted successfully', 200);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }

}
