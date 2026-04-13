<?php

use App\Http\Controllers\Modules\RolesPermissions\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:roles_permissions.view'])->prefix('roles-permissions')->name('roles-permissions.')->group(function () {
    Route::get('/', [RolePermissionController::class, 'index'])->name('index');
    Route::get('/{role}/edit', [RolePermissionController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RolePermissionController::class, 'update'])
        ->middleware('permission:roles_permissions.update')
        ->name('update');
});
