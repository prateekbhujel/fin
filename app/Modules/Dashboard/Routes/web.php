<?php

use App\Modules\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:dashboard.view'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});
