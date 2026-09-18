<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScrapingLog;
use Illuminate\View\View;

class ScrapingLogController extends Controller
{
    public function index(): View
    {
        $logs = ScrapingLog::with('source')
            ->latest('started_at')
            ->paginate(15);

        return view('admin.scraping-logs.index', compact('logs'));
    }

    public function show(int $id): View
    {
        $log = ScrapingLog::with('source')->findOrFail($id);

        return view('admin.scraping-logs.show', compact('log'));
    }
}
