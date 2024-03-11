<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Http\Traits\PaginationTrait;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name', 'guard_name'];

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

    public function storePermission(PermissionRequest $request)
    {
        $request["guard_name"] = 'web';
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
