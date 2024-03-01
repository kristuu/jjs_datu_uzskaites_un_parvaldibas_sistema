<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Instructor;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\PaginationTrait;
use Propaganistas\LaravelPhone\PhoneNumber;
use Propaganistas\LaravelPhone\Exceptions\NumberParseException as libNumberParseException;
use App\Exceptions\PhoneNumberException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['person_code', 'name', 'surname', 'email', 'phone', 'iban_code'];

    public function getUsersColumnNames() {
        $model = new User;
        return $this->sendResponse($this->getColumnNames($model));
    }

    public function getAllUsers()
    {
        return $this->getAll(User::class, $this->globalFilterFields);
    }

    public function getPaginatedUsers(Request $request)
    {
        return $this->getPaginated($request,
                          User::class,
                                   ['address', 'address.region', 'address.region.country'],
                                   $request->perPage,
                                   $this->globalFilterFields);
    }

    public function storeUser(UserRequest $request)
    {
        return $this->store($request, User::class);
    }

    public function findUserById(string $person_code)
    {
        $user = User::find($person_code);
        $userRoles = $user->roles;
        $user["all_roles"] = Role::get()->each(function ($role) use ($userRoles) {
            $role->userHas = $userRoles->contains($role);
        });

        return response()->json($user);
    }

    public function updateUser(UserRequest $request, string $person_code)
    {
        $user = User::find($person_code);
        $roles = array_map(function ($role) {
           return $role["userHas"] ? $role["id"] : null;
        }, $request->all_roles);
        return $user->syncRoles($roles);
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

    public function destroyUser(string $person_code)
    {
        return $this->destroy($person_code, User::class);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
