<?php

use App\Http\Controllers\Modules\Settings\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->middleware('permission:settings.view')->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->middleware('permission:settings.update')->name('settings.update');
});
