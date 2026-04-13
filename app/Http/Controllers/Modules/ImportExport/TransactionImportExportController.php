<?php

namespace App\Http\Controllers\Modules\ImportExport;

use App\Exports\TransactionsExport;
use App\Exports\TransactionsTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\ImportExport\ImportTransactionsRequest;
use App\Imports\TransactionsImport;
use App\Models\Category;
use Illuminate\Http\Request;

class TransactionImportExportController extends Controller
{
    public function index()
    {
        return view('modules.import-export.index', [
            'categories' => Category::query()->orderBy('name')->get(),
            'types' => config('finance.transaction_types'),
        ]);
    }

    public function import(ImportTransactionsRequest $request)
    {
        $import = new TransactionsImport((int) auth()->id());
        $import->import($request->file('file'));

        $failureText = collect($import->failures())->map(function ($failure) {
            return 'Row '.$failure->row().': '.implode(', ', $failure->errors());
        })->implode(' | ');

        return redirect()
            ->route('import-export.index')
            ->with('status', 'Imported '.$import->importedRows.' transactions successfully.')
            ->with('warning', $failureText ?: null);
    }

    public function export(Request $request)
    {
        return (new TransactionsExport($request->only([
            'search',
            'type',
            'category_id',
            'date_from',
            'date_to',
            'from_bs',
            'to_bs',
        ])))->download('transactions-'.now()->format('YmdHis').'.xlsx');
    }

    public function template()
    {
        return (new TransactionsTemplateExport())->download('transactions-template.xlsx');
    }
}
