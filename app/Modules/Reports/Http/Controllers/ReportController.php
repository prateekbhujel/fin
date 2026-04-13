<?php

namespace App\Modules\Reports\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Modules\Reports\Services\ReportService;
use App\Modules\Transactions\DTOs\TransactionFiltersData;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request, ReportService $reports)
    {
        $filters = TransactionFiltersData::fromArray($request->only([
            'search',
            'type',
            'category_id',
            'date_from',
            'date_to',
            'from_bs',
            'to_bs',
        ]));

        return view('modules.reports.index', [
            'filters' => $filters->toArray(),
            'overview' => $reports->overview($filters->toArray()),
            'monthlySummary' => $reports->monthlySummary($filters->toArray()),
            'yearlySummary' => $reports->yearlySummary($filters->toArray()),
            'categorySummary' => $reports->categorySummary($filters->toArray()),
            'categories' => Category::query()->orderBy('name')->get(),
            'types' => config('finance.transaction_types'),
        ]);
    }
}
