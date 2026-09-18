@extends('layouts.admin')

@section('title', 'Sumber Scraping — JOS Admin')
@section('page_title', 'Kelola Sumber Scraping')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Daftar Sumber Scraping Publik</h2>
            <p class="text-xs text-slate-500">Konfigurasi alamat portal lowongan kerja publik dan eksekusi scraping manual.</p>
        </div>

        <div class="flex items-center space-x-2.5">
            <form action="{{ route('admin.scraping-sources.scrape-all') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Scrape Semua Sumber
                </button>
            </form>
            <a href="{{ route('admin.scraping-sources.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs shadow-xs transition-colors">
                + Tambah Sumber
            </a>
        </div>
    </div>

    <!-- Sources Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Nama Sumber</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">URL Daftar Lowongan</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Tipe / Interval</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Scraping Terakhir</th>
                        <th class="px-5 py-3.5 text-right font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($sources as $src)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-900">
                                <div>{{ $src->name }}</div>
                                <span class="text-[11px] text-slate-400 font-normal">{{ $src->jobs_count }} lowongan di database</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600 max-w-xs truncate">
                                <a href="{{ $src->list_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:underline flex items-center">
                                    <span class="truncate">{{ $src->list_url }}</span>
                                    <svg class="w-3 h-3 ml-1 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </td>
                            <td class="px-5 py-4 text-slate-700">
                                <div class="font-semibold">{{ $src->scraper_type }}</div>
                                <span class="text-[11px] text-slate-500">Setiap {{ $src->interval_hours }} jam</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($src->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-500 text-[11px]">
                                {{ $src->last_scraped_at ? \Carbon\Carbon::parse($src->last_scraped_at)->isoFormat('D MMM Y, HH:mm') : 'Belum pernah' }}
                            </td>
                            <td class="px-5 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <!-- Trigger Manual Scrape -->
                                <form action="{{ route('admin.scraping-sources.scrape', $src->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-bold hover:bg-blue-100 transition-colors" title="Jalankan Scraping Sekarang">
                                        Scrape
                                    </button>
                                </form>

                                <!-- Toggle Active -->
                                <form action="{{ route('admin.scraping-sources.toggle', $src->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg {{ $src->is_active ? 'bg-amber-50 text-amber-800 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }} font-bold transition-colors">
                                        {{ $src->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <!-- Edit -->
                                <a href="{{ route('admin.scraping-sources.edit', $src->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-colors">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                Belum ada sumber scraping yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $sources->links() }}
        </div>
    </div>
</div>
@endsection
