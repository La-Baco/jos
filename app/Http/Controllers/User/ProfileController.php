<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\EducationLevel;
use App\Models\JobCategory;
use App\Models\JobSeekerProfile;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $profile = $user->profile ?? new JobSeekerProfile;

        $educationLevels = EducationLevel::orderBy('hierarchy_order')->get();
        $categories = JobCategory::where('is_active', true)->orderBy('name')->get();
        $districts = Location::where('type', 'kecamatan')->orderBy('name')->get();

        $selectedCategories = $user->preferredCategories->pluck('id')->toArray();

        return view('user.profile.index', compact(
            'user',
            'profile',
            'educationLevels',
            'categories',
            'districts',
            'selectedCategories'
        ));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        // 1. Update basic user account info
        $user->update([
            'name' => $request->validated('name'),
            'phone' => $request->validated('phone'),
        ]);

        // 2. Update or create detailed job seeker profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $request->validated('phone'),
                'birth_date' => $request->validated('birth_date'),
                'gender' => $request->validated('gender'),
                'province' => $request->validated('province', 'Jawa Timur'),
                'regency' => $request->validated('regency', 'Sumenep'),
                'district' => $request->validated('district'),
                'village' => $request->validated('village'),
                'education_level_id' => $request->validated('education_level_id'),
                'major' => $request->validated('major'),
                'graduation_year' => $request->validated('graduation_year'),
                'willing_outside_sumenep' => $request->boolean('willing_outside_sumenep'),
                'willing_outside_madura' => $request->boolean('willing_outside_madura'),
                'willing_remote' => $request->boolean('willing_remote'),
                'expected_salary_min' => $request->validated('expected_salary_min'),
                'preferred_location' => $request->validated('preferred_location'),
                'bio' => $request->validated('bio'),
            ]
        );

        // 3. Sync preferred job categories
        $user->preferredCategories()->sync($request->input('preferred_categories', []));

        return redirect()->route('user.profile.index')
            ->with('success', 'Data profil dan preferensi pekerjaan Anda berhasil disimpan.');
    }
}
