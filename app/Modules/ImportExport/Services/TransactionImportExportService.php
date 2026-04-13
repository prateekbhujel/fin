<?php

namespace App\Modules\ImportExport\Services;

use App\Exports\TransactionsExport;
use App\Exports\TransactionsTemplateExport;
use App\Imports\TransactionsImport;
use App\Models\Category;
use App\Modules\Transactions\DTOs\TransactionFiltersData;
use Illuminate\Http\UploadedFile;

class TransactionImportExportService
{
    public function payload(): array
    {
        return [
            'categories' => Category::query()->orderBy('name')->get(),
            'types' => config('finance.transaction_types'),
        ];
    }

    public function import(UploadedFile $file, int $userId): array
    {
        $import = new TransactionsImport($userId);
        $import->import($file);

        return [
            'count' => $import->importedRows,
            'failures' => collect($import->failures())->map(function ($failure) {
                return 'Row '.$failure->row().': '.implode(', ', $failure->errors());
            })->implode(' | '),
        ];
    }

    public function export(TransactionFiltersData $filters)
    {
        return (new TransactionsExport($filters->toArray()))
            ->download('transactions-'.now()->format('YmdHis').'.xlsx');
    }

    public function template()
    {
        return (new TransactionsTemplateExport())->download('transactions-template.xlsx');
    }
}
