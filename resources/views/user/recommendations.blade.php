@extends('layouts.app')

@section('title', 'Rekomendasi Pekerjaan Khusus Untuk Anda — JOS')
@section('meta_description', 'Daftar lowongan pekerjaan berperingkat tingkat kecocokan tertinggi berdasarkan kualifikasi profil Anda di JOS.')

@section('content')
<div class="bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white py-10 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold mb-3">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Fitur Utama JOS — Rule-Based Matching</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Rekomendasi Lowongan Untuk Anda</h1>
        <p class="text-sm text-slate-300 mt-1 max-w-3xl">
            Sistem secara otomatis membandingkan skill, pendidikan, lokasi, pengalaman kerja, minat, dan usia Anda dengan seluruh database lowongan publik. Diurutkan dari skor kecocokan tertinggi.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-8 shadow-xs">
        <form action="{{ route('user.recommendations') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div>
                <label for="location" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Lokasi</label>
                <select name="location" id="location" class="w-full text-xs py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Lokasi</option>
                    <option value="sumenep" {{ request('location') === 'sumenep' ? 'selected' : '' }}>Sumenep</option>
                    <option value="pamekasan" {{ request('location') === 'pamekasan' ? 'selected' : '' }}>Pamekasan</option>
                    <option value="surabaya" {{ request('location') === 'surabaya' ? 'selected' : '' }}>Surabaya</option>
                    <option value="remote" {{ request('location') === 'remote' ? 'selected' : '' }}>Remote</option>
                </select>
            </div>

            <div>
                <label for="category" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Kategori</label>
                <select name="category" id="category" class="w-full text-xs py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="education" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Pendidikan</label>
                <select name="education" id="education" class="w-full text-xs py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Pendidikan</option>
                    @foreach($educationLevels as $lvl)
                        <option value="{{ $lvl->id }}" {{ request('education') == $lvl->id ? 'selected' : '' }}>{{ $lvl->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="type" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Tipe Kerja</label>
                <select name="type" id="type" class="w-full text-xs py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Tipe</option>
                    <option value="Full-time" {{ request('type') === 'Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Part-time" {{ request('type') === 'Part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="Kontrak" {{ request('type') === 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                    <option value="Freelance" {{ request('type') === 'Freelance' ? 'selected' : '' }}>Freelance</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                    Saring Hasil
                </button>
            </div>
        </form>
    </div>

    <!-- Recommendations Grid -->
    @if($recommendations->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Belum Ada Rekomendasi yang Cocok</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mb-6">
                Pastikan Anda telah mengisi minimal pendidikan terakhir, domisili, dan keahlian/skill di profil Anda.
            </p>
            <a href="{{ route('user.profile.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-700 text-white font-bold text-xs hover:bg-blue-800">
                Kelola Profil Saya
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recommendations as $rec)
                @php $job = $rec->job; @endphp
                @if($job)
                    <div class="bg-white rounded-2xl border-2 {{ $rec->total_score >= 80 ? 'border-emerald-300 shadow-sm' : 'border-slate-200' }} hover:shadow-md transition-all p-5 flex flex-col justify-between group">
                        <div>
                            <!-- Header: Match Badge & Tag -->
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div class="flex items-center space-x-1.5 px-3 py-1 rounded-xl text-xs font-black {{ $rec->total_score >= 85 ? 'bg-emerald-600 text-white' : ($rec->total_score >= 70 ? 'bg-blue-600 text-white' : 'bg-amber-500 text-white') }} shadow-xs">
                                    <span class="text-sm">{{ $rec->total_score }}%</span>
                                    <span class="text-[10px] uppercase font-bold tracking-wider opacity-90">Kecocokan</span>
                                </div>

                                <span class="text-[11px] font-medium text-slate-500">
                                    {{ $job->normalized_regency ?: 'Sumenep' }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-2 mb-1">
                                <a href="{{ route('jobs.show', $job->id) }}">
                                    {{ $job->title }}
                                </a>
                            </h3>
                            <p class="text-xs font-semibold text-slate-600 mb-3">{{ $job->company }}</p>

                            <!-- 6 Radar Scores Matrix -->
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 mb-4 space-y-1.5 text-[11px]">
                                <div class="grid grid-cols-2 gap-1.5">
                                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-100">
                                        <span class="text-slate-500">Skill (30%):</span>
                                        <span class="font-bold {{ $rec->skill_score >= 70 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $rec->skill_score }}%</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-100">
                                        <span class="text-slate-500">Pendidikan (20%):</span>
                                        <span class="font-bold {{ $rec->education_score >= 80 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $rec->education_score }}%</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-100">
                                        <span class="text-slate-500">Lokasi (20%):</span>
                                        <span class="font-bold {{ $rec->location_score >= 70 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $rec->location_score }}%</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-100">
                                        <span class="text-slate-500">Pengalaman (15%):</span>
                                        <span class="font-bold {{ $rec->experience_score >= 80 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $rec->experience_score }}%</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-100">
                                        <span class="text-slate-500">Minat (10%):</span>
                                        <span class="font-bold {{ $rec->interest_score >= 80 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $rec->interest_score }}%</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-100">
                                        <span class="text-slate-500">Usia (5%):</span>
                                        <span class="font-bold {{ $rec->age_score >= 80 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $rec->age_score }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">{{ $job->formatted_salary }}</span>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg bg-blue-700 hover:bg-blue-800 text-white transition-colors">
                                    Detail & Alasan
                                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-8">
            {{ $recommendations->links() }}
        </div>
    @endif
</div>
@endsection
