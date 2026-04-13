<?php

namespace App\Modules\RolesPermissions\Services;

use App\Modules\RolesPermissions\DTOs\RolePermissionsData;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionService
{
    public function roles(): Collection
    {
        return Role::query()->with('permissions')->get();
    }

    public function permissionGroups(): Collection
    {
        return Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => Str::before($permission->name, '.'));
    }

    public function sync(Role $role, RolePermissionsData $data): void
    {
        $role->syncPermissions($data->permissions);
    }
}
