<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Region;
use App\Models\User;

class UserService
{
    public function getAll()
    {
        $users = User::all();

        return response()->json([

        ]);
    }
}
