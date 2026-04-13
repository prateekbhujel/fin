<?php

use App\Http\Controllers\Modules\Reports\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:reports.view'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
