<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Services\JobMatchingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(JobMatchingService $matchingService): View
    {
        $user = Auth::user();

        // 1. Profile completion progress
        $completionPercentage = $user->profileCompletionPercentage();

        // 2. Generate or fetch top matching recommendations
        $recommendations = $matchingService->generateRecommendationsForUser($user, 8);

        // 3. Saved jobs
        $savedJobs = $user->savedJobs()->with(['category', 'educationLevel'])->take(4)->get();

        // 4. Latest jobs in Sumenep
        $latestSumenepJobs = Job::active()
            ->sumenep()
            ->latest('posted_at')
            ->take(4)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'completionPercentage',
            'recommendations',
            'savedJobs',
            'latestSumenepJobs'
        ));
    }
}
