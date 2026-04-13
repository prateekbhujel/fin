<?php

use App\Http\Controllers\Modules\ImportExport\TransactionImportExportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('import-export')->name('import-export.')->group(function () {
    Route::get('/', [TransactionImportExportController::class, 'index'])->middleware('permission:transactions.import')->name('index');
    Route::post('/import', [TransactionImportExportController::class, 'import'])->middleware('permission:transactions.import')->name('import');
    Route::get('/export', [TransactionImportExportController::class, 'export'])->middleware('permission:transactions.export')->name('export');
    Route::get('/template', [TransactionImportExportController::class, 'template'])->middleware('permission:transactions.export')->name('template');
});
