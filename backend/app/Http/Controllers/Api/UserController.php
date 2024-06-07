<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Nembie\IbanRule\ValidIban;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['person_code', 'name', 'surname', 'email', 'phone', 'iban_code'];

    public function getUsersColumnNames()
    {
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
            ['address', 'address.city', 'address.city.country'],
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

    public function updateProfile(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();

            // Validate the input data
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:60', 'regex:/^[\pL\s\-]+$/u'],
                'surname' => ['required', 'string', 'max:60', 'regex:/^[\pL\s\-]+$/u'],
                'email' => ['required', 'max:60', 'email', "unique:users,email,{$user->person_code},person_code"],
                'phone' => ['phone', 'required', 'max:36'],
                'profile_picture' => ['nullable', 'string', 'max:255'],
                'iban_code' => ['max:255', 'nullable', new ValidIban()],
            ]);

            // Update user details
            $user->update(
                $validated
            );

            // Send a success response
            return $this->sendResponse(['message' => 'Profile updated successfully']);
        } catch (ValidationException $e) {
            // Return validation error response
            return $this->sendResponse(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Catch all other exceptions and send an error response
            return $this->sendResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function setAddress(Request $request)
    {
        $user = auth()->user();

        $country = Country::firstOrCreate(['name' => $request->country->name]);
        $region = City::firstOrCreate(['name' => $request->region->name]);
        $user->update([
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'city' => $region->id,
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
