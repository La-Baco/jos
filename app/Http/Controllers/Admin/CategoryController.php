<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = JobCategory::withCount('jobs')->withCount('skills')->orderBy('name')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:job_categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        JobCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori pekerjaan baru berhasil ditambahkan.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $category = JobCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:job_categories,name,'.$id],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori pekerjaan berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = JobCategory::findOrFail($id);

        if ($category->jobs()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena memiliki lowongan terkait.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori pekerjaan berhasil dihapus.');
    }
}
