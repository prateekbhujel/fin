<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(config('finance.permissions'));

        $permissions->each(function (string $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        });

        foreach (config('finance.roles', []) as $roleName => $rolePermissions) {
            $role = Role::findOrCreate($roleName, 'web');

            if ($rolePermissions === ['*']) {
                $role->syncPermissions($permissions);
                continue;
            }

            $role->syncPermissions($rolePermissions);
        }
    }
}
