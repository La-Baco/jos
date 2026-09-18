<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobCategory;
use App\Models\User;
use App\Services\JobMatchingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(JobMatchingService $matchingService): View
    {
        $stats = [
            'total_jobs' => Job::count(),
            'active_jobs' => Job::active()->count(),
            'registered_users' => User::where('role', 'job_seeker')->count(),
            'sumenep_jobs' => Job::active()->sumenep()->count(),
        ];

        $popularCategories = JobCategory::where('is_active', true)
            ->withCount(['jobs' => function ($q) {
                $q->active();
            }])
            ->orderByDesc('jobs_count')
            ->take(8)
            ->get();

        $latestJobs = Job::active()
            ->with(['category', 'educationLevel', 'source'])
            ->latest('posted_at')
            ->take(6)
            ->get();

        // If user is logged in, pre-compute match scores for latest jobs
        $jobScores = [];
        if (Auth::check() && Auth::user()->isJobSeeker()) {
            $user = Auth::user();
            foreach ($latestJobs as $job) {
                $match = $matchingService->match($user, $job);
                $jobScores[$job->id] = $match;
            }
        }

        return view('public.home', compact('stats', 'popularCategories', 'latestJobs', 'jobScores'));
    }
}
