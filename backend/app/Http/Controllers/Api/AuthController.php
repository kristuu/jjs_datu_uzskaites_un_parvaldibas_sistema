<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'phone' => ['phone', 'required'],
            'password' => ['string', 'required', 'min:8', 'confirmed'],
        ]);

        $user = User::create($validated);

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Ievadīta neeksistējoša e-pasta adrese vai nesaderīga e-pasta un paroles kombinācija'
            ], 401);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json(['user' => $user]);
        }

        return response()->json(['message' => 'Authentication failed'], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard("web")->logout();

        $request->session()->invalidate();

        // return json response with the default 200 HTTP status code
        return response()->json(['message' => 'Autentifikācijas sesija beigta']);
    }

    public function getCurrentUser()
    {
        return auth()->user();
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['old_password' => 'Ievadītā vecā parole neatbilst sistēmas ierakstiem'], 422);
        }

        $user->password = $request->new_password;
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }
}
