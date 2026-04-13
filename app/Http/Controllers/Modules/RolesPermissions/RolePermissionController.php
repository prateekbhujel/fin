<?php

namespace App\Http\Controllers\Modules\RolesPermissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\RolesPermissions\UpdateRolePermissionsRequest;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        return view('modules.roles-permissions.index', [
            'roles' => Role::query()->with('permissions')->get(),
        ]);
    }

    public function edit(Role $role)
    {
        $permissions = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => Str::before($permission->name, '.'));

        return view('modules.roles-permissions.edit', [
            'role' => $role->load('permissions'),
            'permissionGroups' => $permissions,
        ]);
    }

    public function update(UpdateRolePermissionsRequest $request, Role $role)
    {
        $role->syncPermissions($request->validated('permissions', []));

        return redirect()->route('roles-permissions.index')->with('status', 'Role permissions updated successfully.');
    }
}
