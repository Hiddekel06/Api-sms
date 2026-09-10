<?php

namespace App\Http\Controllers;

use App\Models\SmsLog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $recentLogs = SmsLog::with('apiToken')
            ->latest()
            ->limit(50)
            ->get();

        return view('dashboard', compact('recentLogs'));
    }
}
