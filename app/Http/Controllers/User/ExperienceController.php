<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreExperienceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $experiences = $user->experiences;

        return view('user.profile.experiences', compact('user', 'experiences'));
    }

    public function store(StoreExperienceRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $user->experiences()->create([
            'job_title' => $request->validated('job_title'),
            'company_name' => $request->validated('company_name'),
            'start_year' => $request->validated('start_year'),
            'end_year' => $request->boolean('is_current') ? null : $request->validated('end_year'),
            'is_current' => $request->boolean('is_current'),
            'description' => $request->validated('description'),
        ]);

        return redirect()->route('user.profile.experiences')
            ->with('success', 'Riwayat pengalaman kerja berhasil ditambahkan.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = Auth::user();
        $experience = $user->experiences()->where('id', $id)->firstOrFail();
        $experience->delete();

        return redirect()->route('user.profile.experiences')
            ->with('success', 'Pengalaman kerja berhasil dihapus.');
    }
}
