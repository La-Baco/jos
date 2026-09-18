<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScrapingSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class ScrapingSourceController extends Controller
{
    public function index(): View
    {
        $sources = ScrapingSource::withCount('jobs')->with('logs')->latest()->paginate(10);

        return view('admin.scraping-sources.index', compact('sources'));
    }

    public function create(): View
    {
        return view('admin.scraping-sources.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'base_url' => ['required', 'url', 'max:255'],
            'list_url' => ['required', 'url', 'max:255'],
            'scraper_type' => ['required', 'string', 'max:100'],
            'interval_hours' => ['required', 'integer', 'min:1', 'max:168'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        ScrapingSource::create($validated);

        return redirect()->route('admin.scraping-sources.index')
            ->with('success', 'Sumber scraping baru berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $source = ScrapingSource::findOrFail($id);

        return view('admin.scraping-sources.edit', compact('source'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $source = ScrapingSource::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'base_url' => ['required', 'url', 'max:255'],
            'list_url' => ['required', 'url', 'max:255'],
            'scraper_type' => ['required', 'string', 'max:100'],
            'interval_hours' => ['required', 'integer', 'min:1', 'max:168'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $source->update($validated);

        return redirect()->route('admin.scraping-sources.index')
            ->with('success', 'Data sumber scraping berhasil diperbarui.');
    }

    public function toggle(int $id): RedirectResponse
    {
        $source = ScrapingSource::findOrFail($id);
        $source->update(['is_active' => ! $source->is_active]);

        $status = $source->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Sumber scraping {$source->name} berhasil {$status}.");
    }

    public function triggerScrape(Request $request, int $id): RedirectResponse
    {
        $source = ScrapingSource::findOrFail($id);

        try {
            Artisan::call('jobs:scrape', ['--source' => (string) $source->id]);
            $output = Artisan::output();

            return back()->with('success', "Proses scraping manual untuk {$source->name} selesai dijalankan.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal menjalankan scraping: {$e->getMessage()}");
        }
    }

    public function triggerScrapeAll(): RedirectResponse
    {
        try {
            Artisan::call('jobs:scrape', ['--source' => 'all']);

            return back()->with('success', 'Semua sumber scraping aktif berhasil dijalankan.');
        } catch (\Exception $e) {
            return back()->with('error', "Gagal menjalankan scraping: {$e->getMessage()}");
        }
    }
}
