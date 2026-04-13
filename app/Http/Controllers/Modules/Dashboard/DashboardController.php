<?php

namespace App\Http\Controllers\Modules\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Transaction;
use App\Support\Reports\TransactionReportService;

class DashboardController extends Controller
{
    public function __invoke(TransactionReportService $reports)
    {
        return view('modules.dashboard.index', [
            'summary' => $reports->overview(),
            'monthlyTrend' => $reports->monthlyTrend(),
            'recentTransactions' => Transaction::query()
                ->with(['category', 'user'])
                ->latest('transaction_date')
                ->take(8)
                ->get(),
            'announcements' => Announcement::query()
                ->published()
                ->latest('published_at')
                ->take(5)
                ->get(),
        ]);
    }
}
