<?php

use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.basic'])->prefix('v1')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->middleware('permission:api.transactions.view')
        ->name('api.transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])
        ->middleware('permission:api.transactions.create')
        ->name('api.transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
        ->middleware('permission:api.transactions.view')
        ->name('api.transactions.show');
});
