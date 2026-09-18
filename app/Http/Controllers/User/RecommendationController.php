<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\JobCategory;
use App\Models\JobRecommendation;
use App\Services\JobMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    public function index(Request $request, JobMatchingService $matchingService): View
    {
        $user = Auth::user();

        // Ensure fresh recommendations are generated for this user
        $matchingService->generateRecommendationsForUser($user, 100);

        // Fetch recommendations with query filters on job
        $query = JobRecommendation::where('user_id', $user->id)
            ->with(['job.category', 'job.educationLevel', 'job.skills', 'job.source'])
            ->whereHas('job', function ($q) use ($request) {
                $q->active();

                if ($request->filled('category')) {
                    $q->where('job_category_id', $request->input('category'));
                }

                if ($request->filled('education')) {
                    $q->where('education_level_id', $request->input('education'));
                }

                if ($request->filled('location')) {
                    $loc = $request->input('location');
                    if ($loc === 'sumenep') {
                        $q->sumenep();
                    } elseif ($loc === 'remote') {
                        $q->where('is_remote', true);
                    } else {
                        $q->where(function ($sub) use ($loc) {
                            $sub->where('location', 'like', "%{$loc}%")
                                ->orWhere('normalized_regency', 'like', "%{$loc}%");
                        });
                    }
                }

                if ($request->filled('type')) {
                    $q->where('employment_type', $request->input('type'));
                }
            })
            ->orderBy('total_score', 'desc');

        $recommendations = $query->paginate(12)->withQueryString();

        $categories = JobCategory::where('is_active', true)->orderBy('name')->get();
        $educationLevels = EducationLevel::orderBy('hierarchy_order')->get();
        $savedJobIds = $user->savedJobs()->pluck('jobs.id')->toArray();

        return view('user.recommendations', compact(
            'user',
            'recommendations',
            'categories',
            'educationLevels',
            'savedJobIds'
        ));
    }
}
