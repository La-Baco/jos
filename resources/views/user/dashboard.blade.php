@extends('layouts.app')

@section('title', 'Dashboard Pencari Kerja — JOS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Header & Profile Progress Banner -->
    <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-slate-900 rounded-3xl text-white p-6 sm:p-10 mb-8 shadow-xl relative overflow-hidden">
        <div class="relative z-10 max-w-3xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-500/30 text-blue-200 border border-blue-400/30 mb-3">
                Warga Kabupaten Sumenep
            </span>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
                Selamat datang, {{ $user->name }}!
            </h1>
            <p class="text-sm sm:text-base text-blue-100 mt-2">
                Sistem JOS telah menganalisis profil dan menghitung kecocokan kualifikasi Anda dengan berbagai lowongan kerja publik aktif.
            </p>

            <!-- Profile Progress Card -->
            <div class="mt-6 bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/15 max-w-xl">
                <div class="flex items-center justify-between text-xs sm:text-sm font-bold mb-2">
                    <span class="text-blue-100">Kelengkapan Profil Anda</span>
                    <span class="text-white">{{ $completionPercentage }}% Lengkap</span>
                </div>
                <div class="w-full bg-white/20 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-300 to-emerald-400 h-3 rounded-full transition-all duration-700" style="width: {{ $completionPercentage }}%"></div>
                </div>

                @if($completionPercentage < 100)
                    <div class="mt-3 flex items-center justify-between text-xs text-blue-200">
                        <span>Lengkapi data untuk meningkatkan akurasi rekomendasi:</span>
                        <a href="{{ route('user.profile.index') }}" class="font-bold underline text-white hover:text-blue-200">
                            Lengkapi Sekarang &rarr;
                        </a>
                    </div>
                @else
                    <p class="mt-2 text-xs text-emerald-300 font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Profil Anda sudah lengkap! Hasil rekomendasi di bawah ini telah terhitung maksimal.
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- REKOMENDASI UNTUK ANDA (Fitur Utama: 5-10 lowongan skor tertinggi) -->
    <div class="mb-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Rekomendasi Untuk Anda</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Pekerjaan dengan skor kecocokan tertinggi berdasarkan kualifikasi profil Anda.
                </p>
            </div>
            <a href="{{ route('user.recommendations') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center mt-2 sm:mt-0">
                Lihat Semua Rekomendasi
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if($recommendations->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Belum ada rekomendasi pekerjaan</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-4">
                    Lengkapi profil, pendidikan, dan keahlian Anda untuk mulai mendapatkan rekomendasi lowongan pekerjaan yang akurat.
                </p>
                <a href="{{ route('user.profile.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-700 text-white font-bold text-xs hover:bg-blue-800">
                    Lengkapi Profil
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recommendations as $rec)
                    @php $job = $rec->job; @endphp
                    @if($job)
                        <div class="bg-white rounded-2xl border-2 {{ $rec->total_score >= 80 ? 'border-emerald-300 shadow-sm' : 'border-slate-200' }} hover:shadow-md transition-all p-5 flex flex-col justify-between group">
                            <div>
                                <!-- Top Bar: Score & Badges -->
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="flex items-center space-x-1 px-3 py-1 rounded-xl text-xs font-black {{ $rec->total_score >= 85 ? 'bg-emerald-600 text-white' : ($rec->total_score >= 70 ? 'bg-blue-600 text-white' : 'bg-amber-500 text-white') }} shadow-xs">
                                        <span>{{ $rec->total_score }}%</span>
                                        <span class="text-[10px] font-medium opacity-90">cocok</span>
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

                                <!-- Alasan Singkat Kecocokan (Sesuai Kebutuhan Spesifikasi Section 13) -->
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 mb-4 space-y-1 text-[11px]">
                                    <div class="font-bold text-slate-700 mb-1">Alasan Kecocokan:</div>
                                    <div class="flex items-center text-slate-600">
                                        <svg class="w-3.5 h-3.5 mr-1.5 {{ $rec->education_score >= 80 ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span>Pendidikan: {{ $rec->education_score }}% ({{ $job->educationLevel ? $job->educationLevel->name : 'Semua' }})</span>
                                    </div>
                                    <div class="flex items-center text-slate-600">
                                        <svg class="w-3.5 h-3.5 mr-1.5 {{ $rec->skill_score >= 70 ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span>Skill: {{ $rec->skill_score }}% match</span>
                                    </div>
                                    <div class="flex items-center text-slate-600">
                                        <svg class="w-3.5 h-3.5 mr-1.5 {{ $rec->location_score >= 70 ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span>Lokasi: {{ $rec->location_score }}% ({{ $job->is_remote ? 'Remote' : ($job->normalized_district ?: 'Sumenep') }})</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-700">{{ $job->formatted_salary }}</span>
                                <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg bg-blue-700 hover:bg-blue-800 text-white transition-colors">
                                    Lihat Detail & Alasan
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <!-- Section Bawah: Lowongan Tersimpan & Lowongan Terbaru Sumenep -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Lowongan Tersimpan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Lowongan Tersimpan</h3>
                </div>
                <a href="{{ route('user.saved-jobs') }}" class="text-xs font-semibold text-blue-700 hover:underline">Lihat Semua</a>
            </div>

            @if($savedJobs->isEmpty())
                <div class="text-center py-8 text-xs text-slate-400">
                    <p>Belum ada lowongan yang Anda simpan.</p>
                    <p class="text-[11px] text-slate-400 mt-1">Tandai bookmark pada lowongan yang Anda minati untuk melamar nanti.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($savedJobs as $sj)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 line-clamp-1">
                                    <a href="{{ route('jobs.show', $sj->id) }}" class="hover:text-blue-700">
                                        {{ $sj->title }}
                                    </a>
                                </h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $sj->company }} • {{ $sj->location }}</p>
                            </div>
                            <a href="{{ route('jobs.show', $sj->id) }}" class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-blue-700 hover:bg-blue-50 shrink-0">
                                Buka
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Lowongan Terbaru di Sumenep -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Lowongan Terbaru di Sumenep</h3>
                </div>
                <a href="{{ route('jobs.index', ['location' => 'sumenep']) }}" class="text-xs font-semibold text-blue-700 hover:underline">Lihat Semua</a>
            </div>

            @if($latestSumenepJobs->isEmpty())
                <div class="text-center py-8 text-xs text-slate-400">
                    <p>Belum ada lowongan baru untuk wilayah Sumenep.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($latestSumenepJobs as $lj)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 line-clamp-1">
                                    <a href="{{ route('jobs.show', $lj->id) }}" class="hover:text-blue-700">
                                        {{ $lj->title }}
                                    </a>
                                </h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $lj->company }} • {{ $lj->normalized_district ?: 'Sumenep' }}</p>
                            </div>
                            <a href="{{ route('jobs.show', $lj->id) }}" class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-blue-700 hover:bg-blue-50 shrink-0">
                                Detail
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
