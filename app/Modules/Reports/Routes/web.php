<?php

use App\Modules\Reports\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:reports.view'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
