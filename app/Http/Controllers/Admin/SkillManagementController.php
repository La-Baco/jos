<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\Skill;
use App\Models\SkillAlias;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SkillManagementController extends Controller
{
    public function index(): View
    {
        $skills = Skill::with(['category', 'aliases'])->withCount('jobs')->withCount('users')->orderBy('name')->paginate(15);
        $categories = JobCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.skills.index', compact('skills', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:skills,name'],
            'job_category_id' => ['nullable', 'exists:job_categories,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'aliases' => ['nullable', 'string'], // Comma-separated aliases
        ]);

        $skill = Skill::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'job_category_id' => $validated['job_category_id'],
            'description' => $validated['description'],
        ]);

        // Always add self as alias
        SkillAlias::create([
            'skill_id' => $skill->id,
            'alias' => $skill->name,
        ]);

        // Add additional aliases
        if (! empty($validated['aliases'])) {
            $rawAliases = explode(',', $validated['aliases']);
            foreach ($rawAliases as $al) {
                $trimmed = trim($al);
                if (! empty($trimmed) && strcasecmp($trimmed, $skill->name) !== 0) {
                    SkillAlias::firstOrCreate([
                        'skill_id' => $skill->id,
                        'alias' => $trimmed,
                    ]);
                }
            }
        }

        return redirect()->route('admin.skills.index')
            ->with('success', 'Master skill dan alias berhasil ditambahkan.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:skills,name,'.$id],
            'job_category_id' => ['nullable', 'exists:job_categories,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'aliases' => ['nullable', 'string'],
        ]);

        $skill->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'job_category_id' => $validated['job_category_id'],
            'description' => $validated['description'],
        ]);

        // Re-sync aliases
        $skill->aliases()->delete();

        // Add self
        SkillAlias::create([
            'skill_id' => $skill->id,
            'alias' => $skill->name,
        ]);

        if (! empty($validated['aliases'])) {
            $rawAliases = explode(',', $validated['aliases']);
            foreach ($rawAliases as $al) {
                $trimmed = trim($al);
                if (! empty($trimmed) && strcasecmp($trimmed, $skill->name) !== 0) {
                    SkillAlias::firstOrCreate([
                        'skill_id' => $skill->id,
                        'alias' => $trimmed,
                    ]);
                }
            }
        }

        return redirect()->route('admin.skills.index')
            ->with('success', 'Master skill dan alias berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill dan seluruh aliasnya berhasil dihapus.');
    }
}
