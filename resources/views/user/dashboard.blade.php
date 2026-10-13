@extends('layouts.app')

@section('title', 'Dashboard Pencari Kerja — JOS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Header & Profile Progress Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 rounded-3xl text-white p-6 sm:p-10 mb-8 shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 max-w-3xl">
            <div class="flex items-center space-x-2 mb-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-400/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                    Warga Kabupaten Sumenep
                </span>
            </div>
            
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
                Selamat Datang, {{ $user->name }}! 👋
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                Algoritma JOS telah mencocokkan profil kualifikasi Anda dengan database lowongan publik. Di bawah ini adalah ringkasan peluang karir paling ideal untuk Anda.
            </p>

            <!-- Profile Progress Card -->
            <div class="mt-6 bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/15 max-w-xl">
                <div class="flex items-center justify-between text-xs sm:text-sm font-bold mb-2">
                    <span class="text-blue-100 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Kelengkapan Profil Anda
                    </span>
                    <span class="text-white font-extrabold">{{ $completionPercentage }}% Lengkap</span>
                </div>
                
                <div class="w-full bg-white/20 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-400 to-emerald-400 h-3 rounded-full transition-all duration-700" style="width: {{ $completionPercentage }}%"></div>
                </div>

                @if($completionPercentage < 100)
                    <div class="mt-3 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-blue-200 gap-2">
                        <span>Lengkapi profil untuk memaksimalkan akurasi rekomendasi (bobot skill 30%):</span>
                        <a href="{{ route('user.profile.index') }}" class="font-bold underline text-white hover:text-blue-200 shrink-0">
                            Lengkapi Sekarang &rarr;
                        </a>
                    </div>
                @else
                    <p class="mt-2.5 text-xs text-emerald-300 font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Profil Anda sudah lengkap 100%! Hasil rekomendasi di bawah ini telah terhitung maksimal.
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- 4 Quick Shortcut Cards (Kemudahan Navigasi Pencari Kerja) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <a href="{{ route('jobs.index') }}"
           class="p-4 rounded-2xl bg-white border border-slate-200/90 hover:border-blue-400 hover:shadow-md transition-all group">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold mb-3 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-700">Cari Lowongan</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Jelajahi semua lowongan</p>
        </a>

        <a href="{{ route('user.recommendations') }}"
           class="p-4 rounded-2xl bg-white border border-slate-200/90 hover:border-blue-400 hover:shadow-md transition-all group">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold mb-3 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>
            <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-700">Rekomendasi</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Lowongan paling cocok</p>
        </a>

        <a href="{{ route('user.saved-jobs') }}"
           class="p-4 rounded-2xl bg-white border border-slate-200/90 hover:border-blue-400 hover:shadow-md transition-all group">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold mb-3 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
            </div>
            <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-700">Tersimpan</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">{{ $savedJobs->count() }} lowongan dibookmark</p>
        </a>

        <a href="{{ route('user.profile.skills') }}"
           class="p-4 rounded-2xl bg-white border border-slate-200/90 hover:border-blue-400 hover:shadow-md transition-all group">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold mb-3 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-700">Keahlian / Skill</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">{{ $user->skills->count() }} skill terdaftar</p>
        </a>
    </div>

    <!-- REKOMENDASI UNTUK ANDA (Fitur Unggulan) -->
    <div class="mb-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Rekomendasi Pekerjaan Terbaik</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Diurutkan otomatis dari skor kesesuaian kualifikasi profil Anda (Skill 30%, Pendidikan 20%, Lokasi 20%, Pengalaman 15%, Minat 10%, Usia 5%).
                </p>
            </div>
            <a href="{{ route('user.recommendations') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center mt-2 sm:mt-0">
                Lihat Semua Rekomendasi
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if($recommendations->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center shadow-xs">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Belum ada rekomendasi pekerjaan</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-4 leading-relaxed">
                    Lengkapi profil, pendidikan terakhir, dan keahlian Anda untuk mulai mendapatkan rekomendasi lowongan pekerjaan yang akurat.
                </p>
                <a href="{{ route('user.profile.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-700 text-white font-bold text-xs hover:bg-blue-800">
                    Lengkapi Profil
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recommendations->take(6) as $rec)
                    @php $job = $rec->job; @endphp
                    @if($job)
                        <div class="bg-white rounded-2xl border-2 {{ $rec->total_score >= 80 ? 'border-emerald-300 shadow-sm' : 'border-slate-200' }} hover:shadow-md transition-all p-5 flex flex-col justify-between group">
                            <div>
                                <!-- Top Bar: Match Score & District -->
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="flex items-center space-x-1.5 px-3 py-1 rounded-xl text-xs font-black {{ $rec->total_score >= 85 ? 'bg-emerald-600 text-white' : ($rec->total_score >= 70 ? 'bg-blue-600 text-white' : 'bg-amber-500 text-white') }} shadow-xs">
                                        <span class="text-sm">{{ $rec->total_score }}%</span>
                                        <span class="text-[10px] uppercase font-bold tracking-wider opacity-90">Kecocokan</span>
                                    </div>

                                    <span class="text-[11px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-lg">
                                        {{ $job->normalized_district ? "Kec. {$job->normalized_district}" : ($job->is_remote ? 'Remote' : 'Sumenep') }}
                                    </span>
                                </div>

                                <h3 class="text-base font-extrabold text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-2 leading-snug mb-1">
                                    <a href="{{ route('jobs.show', $job->id) }}">
                                        {{ $job->title }}
                                    </a>
                                </h3>
                                <p class="text-xs font-semibold text-slate-600 mb-3">{{ $job->company }}</p>

                                <!-- Alasan Singkat Kecocokan (Transparan & Mudah Dipahami) -->
                                <div class="bg-slate-50/80 p-3 rounded-xl border border-slate-100 mb-4 space-y-1.5 text-[11px]">
                                    <div class="font-bold text-slate-700 mb-1 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Mengapa Cocok Untuk Anda?
                                    </div>
                                    <div class="flex items-center justify-between text-slate-600">
                                        <span>• Skill (Bobot 30%):</span>
                                        <span class="font-bold {{ $rec->skill_score >= 70 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $rec->skill_score }}%</span>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-600">
                                        <span>• Pendidikan:</span>
                                        <span class="font-bold {{ $rec->education_score >= 80 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $rec->education_score }}% ({{ $job->educationLevel ? $job->educationLevel->name : 'Semua' }})</span>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-600">
                                        <span>• Lokasi:</span>
                                        <span class="font-bold {{ $rec->location_score >= 70 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $rec->location_score }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs gap-2">
                                <span class="font-bold text-slate-800 truncate">{{ $job->formatted_salary }}</span>
                                <a href="{{ route('jobs.show', $job->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-xl bg-blue-700 hover:bg-blue-800 text-white transition-colors shrink-0">
                                    Detail & Alasan
                                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
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
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs">
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
                    <p class="text-[11px] text-slate-400 mt-1">Klik ikon bookmark pada kartu lowongan untuk menyimpannya.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($savedJobs as $sj)
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-blue-200 transition-colors">
                            <div class="min-w-0 pr-3">
                                <h4 class="text-xs font-bold text-slate-900 truncate">
                                    <a href="{{ route('jobs.show', $sj->id) }}" class="hover:text-blue-700">
                                        {{ $sj->title }}
                                    </a>
                                </h4>
                                <p class="text-[11px] text-slate-500 mt-0.5 truncate">{{ $sj->company }} • {{ $sj->normalized_district ?: $sj->location }}</p>
                            </div>
                            <a href="{{ route('jobs.show', $sj->id) }}" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-blue-700 hover:bg-blue-50 shrink-0">
                                Buka
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Lowongan Terbaru di Sumenep -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Lowongan Baru di Sumenep</h3>
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
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-blue-200 transition-colors">
                            <div class="min-w-0 pr-3">
                                <h4 class="text-xs font-bold text-slate-900 truncate">
                                    <a href="{{ route('jobs.show', $lj->id) }}" class="hover:text-blue-700">
                                        {{ $lj->title }}
                                    </a>
                                </h4>
                                <p class="text-[11px] text-slate-500 mt-0.5 truncate">{{ $lj->company }} • {{ $lj->normalized_district ? "Kec. {$lj->normalized_district}" : 'Sumenep' }}</p>
                            </div>
                            <a href="{{ route('jobs.show', $lj->id) }}" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-blue-700 hover:bg-blue-50 shrink-0">
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
