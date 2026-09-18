@extends('layouts.admin')

@section('title', 'Dashboard Utama — JOS Admin')
@section('page_title', 'Dashboard Ringkasan Sistem')

@section('content')
<div class="space-y-8">
    <!-- Top Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Lowongan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Total Lowongan</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total_jobs'] }}</div>
                <span class="text-[11px] text-emerald-600 font-semibold mt-1 inline-block">{{ $stats['active_jobs'] }} Aktif</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Lowongan Expired -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Lowongan Expired</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['expired_jobs'] }}</div>
                <span class="text-[11px] text-slate-400 font-medium mt-1 inline-block">Melewati batas deadline</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Pencari Kerja -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Pencari Kerja</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total_users'] }}</div>
                <span class="text-[11px] text-blue-600 font-semibold mt-1 inline-block">Warga Terdaftar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Sumber Scraping Aktif -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Sumber Scraping</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['active_sources'] }} Aktif</div>
                <span class="text-[11px] text-emerald-600 font-semibold mt-1 inline-block">Agregator Publik</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>
    </div>

    <!-- Quick Scraper Action Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-blue-950 text-white rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-blue-300 font-semibold mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Scraping Terakhir: {{ $latestLog ? \Carbon\Carbon::parse($latestLog->started_at)->diffForHumans() : 'Belum pernah dijalankan' }}</span>
            </div>
            <h2 class="text-lg font-bold text-white">Jalankan Web Scraping Manual</h2>
            <p class="text-xs text-slate-300 mt-0.5">Memicu modul Python scraper untuk memeriksa, membersihkan, dan menormalisasi lowongan baru.</p>
        </div>

        <form action="{{ route('admin.scraping-sources.scrape-all') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Jalankan Scraping Sekarang
            </button>
        </form>
    </div>

    <!-- 2 Column Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Lowongan per Kategori -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                Lowongan per Kategori Terbanyak
            </h3>
            <div class="space-y-3">
                @foreach($categoryStats as $cat)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-800">{{ $cat->name }}</span>
                            <span class="font-bold text-blue-700">{{ $cat->jobs_count }} Lowongan</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total_jobs'] > 0 ? ($cat->jobs_count / $stats['total_jobs']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Lowongan per Lokasi & Wilayah -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                Distribusi Lowongan Berdasarkan Wilayah
            </h3>
            <div class="grid grid-cols-3 gap-3 text-center mb-6">
                <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                    <span class="block text-xs font-bold text-blue-900">Sumenep</span>
                    <span class="text-xl font-black text-blue-700 mt-1 block">{{ $locationStats['Sumenep'] }}</span>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                    <span class="block text-xs font-bold text-emerald-900">Remote (WFH)</span>
                    <span class="text-xl font-black text-emerald-700 mt-1 block">{{ $locationStats['Remote'] }}</span>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl border border-purple-100">
                    <span class="block text-xs font-bold text-purple-900">Luar Sumenep</span>
                    <span class="text-xl font-black text-purple-700 mt-1 block">{{ $locationStats['Luar Sumenep'] }}</span>
                </div>
            </div>

            <!-- Log Scraping Terkini -->
            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Riwayat Log Scraping Terkini</h4>
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($recentLogs as $lg)
                    <div class="py-2.5 flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-slate-800">{{ $lg->source ? $lg->source->name : 'Sumber Umum' }}</span>
                            <span class="text-[11px] text-slate-400 block">{{ \Carbon\Carbon::parse($lg->started_at)->isoFormat('D MMM Y, HH:mm') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $lg->status === 'success' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ strtoupper($lg->status) }}
                            </span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">+{{ $lg->jobs_new }} baru</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
