<?php

namespace App\Modules\Dashboard\Services;

use App\Models\Transaction;
use App\Modules\Announcements\Services\AnnouncementService;
use App\Modules\Reports\Services\ReportService;

class DashboardService
{
    public function __construct(
        protected ReportService $reports,
        protected AnnouncementService $announcements,
    ) {
    }

    public function payload(): array
    {
        return [
            'summary' => $this->reports->overview(),
            'monthlyTrend' => $this->reports->monthlyTrend(),
            'recentTransactions' => Transaction::query()
                ->with(['category', 'user'])
                ->latest('transaction_date')
                ->take(8)
                ->get(),
            'announcements' => $this->announcements->publishedForDashboard(),
        ];
    }
}
