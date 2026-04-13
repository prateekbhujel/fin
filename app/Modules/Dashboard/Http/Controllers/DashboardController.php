<?php

namespace App\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Services\DashboardService;

class DashboardController extends Controller
{
    public function __invoke(DashboardService $dashboard)
    {
        return view('modules.dashboard.index', $dashboard->payload());
    }
}
