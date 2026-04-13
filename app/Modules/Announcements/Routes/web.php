<?php

use App\Modules\Announcements\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/announcements', [AnnouncementController::class, 'index'])->middleware('permission:announcements.view')->name('announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->middleware('permission:announcements.create')->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->middleware('permission:announcements.create')->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->middleware('permission:announcements.update')->name('announcements.edit');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->middleware('permission:announcements.update')->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->middleware('permission:announcements.delete')->name('announcements.destroy');
});
