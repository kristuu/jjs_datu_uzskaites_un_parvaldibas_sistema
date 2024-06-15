<?php

/*
 * Lomu pārvaldība
 * Funkcijas ietver:
 * - visu lomu iegūšana
 * - lomu saraksta parādīšana ar pagination
 * - CRUD operācijas ar lomām
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Traits\PaginationTrait;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name', 'guard_name'];

    public function getRolesColumnNames()
    {
        $model = new Role;
        return $this->sendResponse($this->getColumnNames($model));
    }

    public function getAllRoles()
    {
        return $this->getAll(Role::class, $this->globalFilterFields);
    }

    public function getPaginatedRoles(RoleRequest $request)
    {
        return $this->getPaginated($request,
            Role::class,
            [],
            $request->perPage,
            $this->globalFilterFields);
    }

    public function storeRole(RoleRequest $request)
    {
        $request["guard_name"] = 'web';
        $response = $this->store($request, Role::class);
        $role = Role::findByName($request->name, 'web');
        $permissions = array_map(function ($permission) {
            return $permission['checked'] ? $permission['id'] : null;
        }, $request->permissions);
        $role->syncPermissions($permissions);
        return $response;
    }

    /**
     * Find a role by its ID and retrieve the role's name along with it's permissions.
     */
    public function findRoleById($id)
    {
        $role = Role::findById($id, 'web');

        $data = $role;
        $rolePermissions = $role->getAllPermissions()->pluck('name');

        unset($data["permissions"]);
        $data["permissions"] = Permission::all()->each(function ($permission) use ($rolePermissions) {
            $permission->checked = $rolePermissions->contains($permission->name);
        });

        return $data;
    }

    public function updateRole(RoleRequest $request, $id)
    {
        $role = Role::findById($id, 'web');
        $permissions = array_map(function ($permission) {
            return $permission['checked'] ? $permission['id'] : null;
        }, $request->permissions);
        $this->update($request, $id, Role::class);
        return $role->syncPermissions($permissions);
    }

    public function destroyRole($id)
    {
        return $this->destroy($id, Role::class);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
