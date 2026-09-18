<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\ScrapingSource;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Job::with(['category', 'educationLevel', 'source', 'skills']);

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('company', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('source_id')) {
            $query->where('scraping_source_id', $request->input('source_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('job_category_id', $request->input('category_id'));
        }

        if ($request->filled('location')) {
            $loc = $request->input('location');
            $query->where(function ($q) use ($loc) {
                $q->where('location', 'like', "%{$loc}%")
                    ->orWhere('normalized_regency', 'like', "%{$loc}%");
            });
        }

        $jobs = $query->latest('scraped_at')->paginate(15)->withQueryString();

        $sources = ScrapingSource::orderBy('name')->get();
        $categories = JobCategory::orderBy('name')->get();

        return view('admin.jobs.index', compact('jobs', 'sources', 'categories'));
    }

    public function edit(int $id): View
    {
        $job = Job::with('skills')->findOrFail($id);
        $categories = JobCategory::where('is_active', true)->orderBy('name')->get();
        $educationLevels = EducationLevel::orderBy('hierarchy_order')->get();
        $sources = ScrapingSource::orderBy('name')->get();
        $allSkills = Skill::orderBy('name')->get();
        $selectedSkillIds = $job->skills->pluck('id')->toArray();

        return view('admin.jobs.edit', compact(
            'job',
            'categories',
            'educationLevels',
            'sources',
            'allSkills',
            'selectedSkillIds'
        ));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $job = Job::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'normalized_regency' => ['nullable', 'string', 'max:100'],
            'normalized_district' => ['nullable', 'string', 'max:100'],
            'is_remote' => ['boolean'],
            'job_category_id' => ['nullable', 'exists:job_categories,id'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'education_level_id' => ['nullable', 'exists:education_levels,id'],
            'min_experience_years' => ['required', 'integer', 'min:0'],
            'min_age' => ['nullable', 'integer', 'min:17', 'max:70'],
            'max_age' => ['nullable', 'integer', 'min:17', 'max:70'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'salary_notes' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', 'in:active,expired,inactive'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['exists:skills,id'],
        ]);

        $validated['is_remote'] = $request->boolean('is_remote');

        $job->update($validated);

        if ($request->has('skills')) {
            $job->skills()->sync($request->input('skills', []));
        }

        return redirect()->route('admin.jobs.index')
            ->with('success', "Data lowongan '{$job->title}' berhasil diperbarui oleh Administrator.");
    }

    public function toggleStatus(Request $request, int $id): RedirectResponse
    {
        $job = Job::findOrFail($id);
        $newStatus = $request->input('status', 'inactive');

        if (! in_array($newStatus, ['active', 'expired', 'inactive'])) {
            $newStatus = 'inactive';
        }

        $job->update(['status' => $newStatus]);

        return back()->with('success', "Status lowongan '{$job->title}' diubah menjadi {$newStatus}.");
    }
}
