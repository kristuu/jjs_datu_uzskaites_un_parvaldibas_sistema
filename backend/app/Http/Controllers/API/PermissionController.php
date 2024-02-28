<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Country;
use App\Models\Region;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\PaginationTrait;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name'];

    public function getPermissionsColumnNames() {
        $model = new Permission;
        return $this->sendResponse($this->getColumnNames($model));
    }

    public function getAllPermissions()
    {
        return $this->getAll(Permission::class, $this->globalFilterFields);
    }

    public function getPaginatedPermissions(PermissionRequest $request)
    {
        return $this->getPaginated($request,
                          Permission::class,
                                   [],
                                   $request->perPage,
                                   $this->globalFilterFields);
    }

    public function storePermission(Request $request)
    {
        return $this->store($request, Permission::class);
    }

    public function findPermissionById($id)
    {
        $permission = $this->findById(Permission::class, $id);

        return $permission;
    }

    public function updatePermission(PermissionRequest $request, $id)
    {
        return $this->update($request, $id, Permission::class);
    }

    public function destroyPermission($id)
    {
        return $this->destroy($id, Permission::class);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
