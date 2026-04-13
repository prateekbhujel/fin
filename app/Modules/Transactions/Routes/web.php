<?php

use App\Modules\Transactions\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->middleware('permission:transactions.view')->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->middleware('permission:transactions.create')->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->middleware('permission:transactions.create')->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->middleware('permission:transactions.view')->name('transactions.show');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->middleware('permission:transactions.update')->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->middleware('permission:transactions.update')->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->middleware('permission:transactions.delete')->name('transactions.destroy');
});
