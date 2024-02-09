<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\PaginationTrait;

use App\Models\Instructor;

class InstructorController extends Controller
{
    use PaginationTrait;

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
}



$roleSuperAdmin = \Spatie\Permission\Models\Role::create(['name' => 'super admin', "guard_name" => "api"]);

$permissions = [
    'can-view-users',
    'can-create-users',
    'can-edit-users',
    'can-delete-users',
];

foreach($permissions as $permissionName) {
    $permission = \Spatie\Permission\Models\Permission::create(['name' => $permissionName, "guard_name" => "api"]);

    $roleSuperAdmin->givePermissionTo($permission);
}
