<?php

namespace App\Modules\ImportExport\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ImportExport\Http\Requests\ImportTransactionsRequest;
use App\Modules\ImportExport\Services\TransactionImportExportService;
use App\Modules\Transactions\DTOs\TransactionFiltersData;
use Illuminate\Http\Request;

class TransactionImportExportController extends Controller
{
    public function __construct(
        protected TransactionImportExportService $imports,
    ) {
    }

    public function index()
    {
        return view('modules.import-export.index', $this->imports->payload());
    }

    public function import(ImportTransactionsRequest $request)
    {
        $result = $this->imports->import($request->file('file'), (int) auth()->id());

        return redirect()
            ->route('import-export.index')
            ->with('status', 'Imported '.$result['count'].' transactions successfully.')
            ->with('warning', $result['failures'] ?: null);
    }

    public function export(Request $request)
    {
        return $this->imports->export(TransactionFiltersData::fromArray($request->only([
            'search',
            'type',
            'category_id',
            'date_from',
            'date_to',
            'from_bs',
            'to_bs',
        ])));
    }

    public function template()
    {
        return $this->imports->template();
    }
}
