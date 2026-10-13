<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index(): View
    {
        // 1. Lowongan berdasarkan kategori
        $jobsByCategory = JobCategory::withCount('jobs')
            ->orderByDesc('jobs_count')
            ->get();

        // 2. Lowongan berdasarkan lokasi
        $jobsByLocation = Job::select('normalized_regency', DB::raw('count(*) as total'))
            ->groupBy('normalized_regency')
            ->orderByDesc('total')
            ->get();

        // 3. Lowongan berdasarkan pendidikan minimal
        $jobsByEducation = EducationLevel::withCount('jobs')
            ->orderBy('hierarchy_order')
            ->get();

        // 4. Pencari kerja berdasarkan pendidikan
        $seekersByEducation = EducationLevel::withCount(['profiles as seekers_count' => function ($q) {
            $q->whereHas('user', function ($u) {
                $u->where('role', 'job_seeker');
            });
        }])->orderBy('hierarchy_order')->get()->keyBy('id');

        // 5. Skill yang paling banyak dicari perusahaan vs Skill pencari kerja (Gap Analysis)
        $topDemandedSkills = Skill::withCount('jobs')
            ->withCount('users')
            ->orderByDesc('jobs_count')
            ->take(12)
            ->get();

        // Compute skills gap: (jobs_count - users_count)
        $skillsGapAnalysis = $topDemandedSkills->map(function ($skill) {
            $demanded = $skill->jobs_count;
            $supplied = $skill->users_count;
            $gap = $demanded - $supplied;

            return [
                'name' => $skill->name,
                'demanded' => $demanded,
                'supplied' => $supplied,
                'gap' => $gap,
                'status' => $gap > 0 ? 'Defisit (Kurang Tenaga Terampil)' : ($gap < 0 ? 'Surplus (Persaingan Tinggi)' : 'Seimbang'),
            ];
        });

        return view('admin.statistics.index', compact(
            'jobsByCategory',
            'jobsByLocation',
            'jobsByEducation',
            'seekersByEducation',
            'topDemandedSkills',
            'skillsGapAnalysis'
        ));
    }
}
