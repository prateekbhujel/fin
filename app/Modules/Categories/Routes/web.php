<?php

use App\Modules\Categories\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->middleware('permission:categories.view')->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->middleware('permission:categories.create')->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('permission:categories.create')->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->middleware('permission:categories.update')->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('permission:categories.update')->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('permission:categories.delete')->name('categories.destroy');
});
