<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\ScrapingLog;
use App\Models\ScrapingSource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        $stats = [
            'total_users' => User::where('role', 'job_seeker')->count(),
            'total_jobs' => Job::count(),
            'active_jobs' => Job::active()->count(),
            'expired_jobs' => Job::where('status', 'expired')->count(),
            'active_sources' => ScrapingSource::where('is_active', true)->count(),
            'today_jobs' => Job::whereDate('created_at', $today)->count(),
        ];

        $latestLog = ScrapingLog::with('source')->latest('started_at')->first();
        $recentLogs = ScrapingLog::with('source')->latest('started_at')->take(5)->get();

        // Chart Data: Lowongan per Kategori
        $categoryStats = JobCategory::withCount('jobs')
            ->orderByDesc('jobs_count')
            ->take(6)
            ->get();

        // Chart Data: Lowongan per Lokasi (Sumenep vs Luar Sumenep vs Remote)
        $locationStats = [
            'Sumenep' => Job::active()->sumenep()->count(),
            'Remote' => Job::active()->where('is_remote', true)->count(),
            'Luar Sumenep' => Job::active()->where('is_remote', false)->where(function ($q) {
                $q->whereNull('normalized_regency')->orWhere('normalized_regency', '!=', 'Sumenep');
            })->count(),
        ];

        // Chart Data: Lowongan per Tingkat Pendidikan
        $educationStats = EducationLevel::withCount('jobs')
            ->orderBy('hierarchy_order')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'latestLog',
            'recentLogs',
            'categoryStats',
            'locationStats',
            'educationStats'
        ));
    }
}
