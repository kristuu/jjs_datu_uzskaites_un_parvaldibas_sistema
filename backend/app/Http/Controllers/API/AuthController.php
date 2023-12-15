<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['string', 'required', 'max:60'],
            'surname' => ['string', 'required', 'max:60'],
            'person_code' => ['string', 'required', 'min:11', 'max:11'],
            'birthdate' => ['date', 'required'],
            'email' => ['string', 'required', 'email', 'unique:users'],
            'password' => ['string', 'required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'person_code' => $validated['person_code'],
            'birthdate' => $validated['birthdate'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['string', 'required', 'email'],
            'password' => ['string', 'required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;

            // return json response with the default 200 HTTP status code
            return response()->json(['user' => $user, 'token' => $token]);
        } else {
            return response()->json(['message' => 'Neeksistējoša e-pasta adrese vai nesaderīga e-pasta un paroles kombinācija'], 401);
        }


    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        // return json response with the default 200 HTTP status code
        return response()->json(['message' => 'Autentifikācijas sesija beigta']);
    }
}
