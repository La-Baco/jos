<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\JobMatchingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SavedJobController extends Controller
{
    public function index(JobMatchingService $matchingService): View
    {
        $user = Auth::user();

        $savedJobs = $user->savedJobs()
            ->with(['category', 'educationLevel', 'source', 'skills'])
            ->orderBy('saved_jobs.created_at', 'desc')
            ->paginate(12);

        $jobScores = [];
        foreach ($savedJobs as $job) {
            $jobScores[$job->id] = $matchingService->match($user, $job);
        }

        return view('user.saved-jobs', compact('savedJobs', 'jobScores'));
    }
}
