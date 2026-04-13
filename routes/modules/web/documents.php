<?php

use App\Http\Controllers\Modules\Documents\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->middleware('permission:documents.view')->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->middleware('permission:documents.create')->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->middleware('permission:documents.download')->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->middleware('permission:documents.delete')->name('documents.destroy');
});
