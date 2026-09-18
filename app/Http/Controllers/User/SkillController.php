<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $userSkills = $user->skills()->withPivot('proficiency_level')->get();

        $categories = JobCategory::with(['skills' => function ($query) {
            $query->orderBy('name');
        }])->where('is_active', true)->orderBy('name')->get();

        return view('user.profile.skills', compact('user', 'userSkills', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'proficiency_level' => ['required', 'in:pemula,menengah,ahli'],
        ]);

        $user = Auth::user();
        $user->skills()->syncWithoutDetaching([
            $request->input('skill_id') => [
                'proficiency_level' => $request->input('proficiency_level'),
            ],
        ]);

        return redirect()->route('user.profile.skills')
            ->with('success', 'Skill berhasil ditambahkan ke profil Anda.');
    }

    public function destroy(int $skillId): RedirectResponse
    {
        $user = Auth::user();
        $user->skills()->detach($skillId);

        return redirect()->route('user.profile.skills')
            ->with('success', 'Skill berhasil dihapus dari profil.');
    }
}
