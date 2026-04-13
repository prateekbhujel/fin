<?php

namespace App\Modules\RolesPermissions\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\RolesPermissions\DTOs\RolePermissionsData;
use App\Modules\RolesPermissions\Http\Requests\UpdateRolePermissionsRequest;
use App\Modules\RolesPermissions\Services\RolePermissionService;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function __construct(
        protected RolePermissionService $roles,
    ) {
    }

    public function index()
    {
        return view('modules.roles-permissions.index', [
            'roles' => $this->roles->roles(),
        ]);
    }

    public function edit(Role $role)
    {
        return view('modules.roles-permissions.edit', [
            'role' => $role->load('permissions'),
            'permissionGroups' => $this->roles->permissionGroups(),
        ]);
    }

    public function update(UpdateRolePermissionsRequest $request, Role $role)
    {
        $this->roles->sync($role, RolePermissionsData::fromArray($request->validated()));

        return redirect()->route('roles-permissions.index')->with('status', 'Role permissions updated successfully.');
    }
}
