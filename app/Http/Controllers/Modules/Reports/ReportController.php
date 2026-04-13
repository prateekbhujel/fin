<?php

namespace App\Http\Controllers\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Reports\TransactionReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request, TransactionReportService $reports)
    {
        $filters = $request->only([
            'search',
            'type',
            'category_id',
            'date_from',
            'date_to',
            'from_bs',
            'to_bs',
        ]);

        return view('modules.reports.index', [
            'filters' => $filters,
            'overview' => $reports->overview($filters),
            'monthlySummary' => $reports->monthlySummary($filters),
            'yearlySummary' => $reports->yearlySummary($filters),
            'categorySummary' => $reports->categorySummary($filters),
            'categories' => Category::query()->orderBy('name')->get(),
            'types' => config('finance.transaction_types'),
        ]);
    }
}
