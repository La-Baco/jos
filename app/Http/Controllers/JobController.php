<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\JobView;
use App\Models\Location;
use App\Services\JobMatchingService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request, JobMatchingService $matchingService): View
    {
        $query = Job::active()->with(['category', 'educationLevel', 'source', 'skills']);

        // 1. Keyword search (Title, Company, Description)
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('company', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('requirements', 'like', "%{$keyword}%");
            });
        }

        // 2. Filter Category
        if ($request->filled('category')) {
            $query->where('job_category_id', $request->input('category'));
        }

        // 3. Filter Education
        if ($request->filled('education')) {
            $query->where('education_level_id', $request->input('education'));
        }

        // 4. Filter Location
        if ($request->filled('location')) {
            $loc = $request->input('location');
            if ($loc === 'sumenep') {
                $query->sumenep();
            } elseif ($loc === 'remote') {
                $query->where('is_remote', true);
            } else {
                $query->where(function ($q) use ($loc) {
                    $q->where('location', 'like', "%{$loc}%")
                        ->orWhere('normalized_regency', 'like', "%{$loc}%")
                        ->orWhere('normalized_district', 'like', "%{$loc}%");
                });
            }
        }

        // 5. Filter Remote
        if ($request->boolean('remote')) {
            $query->where('is_remote', true);
        }

        // 6. Filter Employment Type
        if ($request->filled('type')) {
            $query->where('employment_type', $request->input('type'));
        }

        // 7. Filter Experience
        if ($request->filled('experience')) {
            $exp = $request->input('experience');
            if ($exp === 'fresh') {
                $query->where('min_experience_years', 0);
            } elseif ($exp === '1-2') {
                $query->whereBetween('min_experience_years', [1, 2]);
            } elseif ($exp === '3+') {
                $query->where('min_experience_years', '>=', 3);
            }
        }

        // 8. Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'deadline') {
            $query->orderBy('deadline', 'asc');
        } elseif ($sort === 'salary') {
            $query->orderBy('salary_max', 'desc');
        } else {
            $query->orderBy('posted_at', 'desc')->orderBy('id', 'desc');
        }

        $jobs = $query->paginate(12)->withQueryString();

        // Calculate scores for authenticated job seekers
        $jobScores = [];
        $savedJobIds = [];
        if (Auth::check() && Auth::user()->isJobSeeker()) {
            $user = Auth::user();
            $savedJobIds = $user->savedJobs()->pluck('jobs.id')->toArray();

            foreach ($jobs as $job) {
                $jobScores[$job->id] = $matchingService->match($user, $job);
            }
        }

        // Filter metadata
        $categories = JobCategory::where('is_active', true)->orderBy('name')->get();
        $educationLevels = EducationLevel::orderBy('hierarchy_order')->get();
        $districts = Location::where('type', 'kecamatan')->orderBy('name')->get();

        return view('public.jobs.index', compact(
            'jobs',
            'categories',
            'educationLevels',
            'districts',
            'jobScores',
            'savedJobIds'
        ));
    }

    public function show(int $id, Request $request, JobMatchingService $matchingService): View
    {
        $job = Job::with(['category', 'educationLevel', 'source', 'skills.aliases'])->findOrFail($id);

        // Record view in job_views
        JobView::create([
            'job_id' => $job->id,
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'viewed_at' => Carbon::now(),
        ]);

        $matchResult = null;
        $isSaved = false;

        if (Auth::check() && Auth::user()->isJobSeeker()) {
            $user = Auth::user();
            $matchResult = $matchingService->match($user, $job);
            $isSaved = $user->savedJobs()->where('job_id', $job->id)->exists();
        }

        // Related jobs in same category or location
        $relatedJobs = Job::active()
            ->where('id', '!=', $job->id)
            ->where(function ($q) use ($job) {
                $q->where('job_category_id', $job->job_category_id)
                    ->orWhere('normalized_regency', $job->normalized_regency);
            })
            ->take(4)
            ->get();

        return view('public.jobs.show', compact('job', 'matchResult', 'isSaved', 'relatedJobs'));
    }

    public function toggleSave(int $id): RedirectResponse
    {
        $user = Auth::user();
        $job = Job::findOrFail($id);

        if ($user->savedJobs()->where('job_id', $job->id)->exists()) {
            $user->savedJobs()->detach($job->id);
            $message = 'Lowongan dihapus dari daftar tersimpan.';
        } else {
            $user->savedJobs()->attach($job->id);
            $message = 'Lowongan berhasil disimpan ke akun Anda.';
        }

        return back()->with('success', $message);
    }
}
