@extends('layouts.app')

@section('title', "{$job->title} di {$job->company} — JOS")
@section('meta_description', "Informasi lowongan kerja {$job->title} di {$job->company}, {$job->location}. Dapatkan rincian kualifikasi dan tautan ke sumber publik asli.")

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-400 mb-3">
            <a href="{{ route('jobs.index') }}" class="hover:text-white flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Semua Lowongan
            </a>
            <span>/</span>
            <span class="text-slate-300 truncate">{{ $job->title }}</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    @if($job->isExpired())
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-red-500/20 text-red-300 border border-red-500/30">
                            Lowongan Kedaluwarsa (Expired)
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            Aktif
                        </span>
                    @endif

                    @if($job->is_remote)
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30">
                            Remote / WFH
                        </span>
                    @endif

                    <span class="px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-800 text-slate-300">
                        {{ $job->employment_type ?: 'Penuh Waktu' }}
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white leading-tight">
                    {{ $job->title }}
                </h1>
                <p class="text-base font-semibold text-blue-300 mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    {{ $job->company }}
                </p>
            </div>

            <!-- Action CTA on Top -->
            <div class="flex items-center space-x-3 shrink-0">
                @auth
                    <form action="{{ route('jobs.save', $job->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2.5 rounded-xl border {{ $isSaved ? 'border-blue-400 bg-blue-500/20 text-blue-300' : 'border-slate-700 bg-slate-800 text-slate-200 hover:bg-slate-700' }} text-xs font-bold transition-colors">
                            <svg class="w-4 h-4 mr-1.5 {{ $isSaved ? 'fill-blue-400 text-blue-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            {{ $isSaved ? 'Tersimpan' : 'Simpan Lowongan' }}
                        </button>
                    </form>
                @endauth

                <a href="{{ $job->original_url }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition-all hover:scale-105">
                    Lihat Lowongan Asli
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri / Utama: Rincian & Matching Breakdown -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Notice Sumber Publik (Wajib Sesuai Ketentuan) -->
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-2xl flex items-start space-x-3 text-xs text-blue-900">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <strong class="font-bold block mb-0.5">Informasi lowongan ini dikumpulkan dari sumber publik.</strong>
                    JOS bukan marketplace lowongan kerja dan tidak membuka lamaran internal. Untuk melamar, silakan buka tautan sumber publik asli yang terverifikasi di bawah.
                </div>
            </div>

            <!-- DETAIL MATCHING / REKOMENDASI (Jika User Login & Profil Ada) -->
            @if($matchResult)
                <div class="bg-white rounded-2xl border-2 border-blue-600 shadow-md p-6 sm:p-7 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-[11px] font-bold px-4 py-1 rounded-bl-xl uppercase tracking-wider">
                        Analisis Algoritma JOS
                    </div>

                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-lg">
                            {{ $matchResult['total_score'] }}%
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900">Mengapa lowongan ini cocok untuk Anda?</h2>
                            <p class="text-xs text-slate-500">Skor dihitung transparan menggunakan rumus rule-based tanpa kecerdasan buatan (AI).</p>
                        </div>
                    </div>

                    <!-- Progress Bar Component -->
                    <div class="w-full bg-slate-100 rounded-full h-3 mb-6 overflow-hidden">
                        <div class="h-3 rounded-full {{ $matchResult['total_score'] >= 80 ? 'bg-emerald-600' : ($matchResult['total_score'] >= 60 ? 'bg-blue-600' : 'bg-amber-500') }} transition-all duration-700"
                             style="width: {{ $matchResult['total_score'] }}%"></div>
                    </div>

                    <!-- 6 Kriteria Breakdown -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <!-- 1. Skill (30%) -->
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70">
                            <div class="flex items-center justify-between font-bold mb-1">
                                <span class="text-slate-700">1. Keahlian & Skill (Bobot 30%)</span>
                                <span class="text-blue-700 font-extrabold">{{ $matchResult['skill_score'] }}%</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">{{ $matchResult['breakdown']['skill']['explanation'] }}</p>
                        </div>

                        <!-- 2. Pendidikan (20%) -->
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70">
                            <div class="flex items-center justify-between font-bold mb-1">
                                <span class="text-slate-700">2. Jenjang Pendidikan (Bobot 20%)</span>
                                <span class="text-blue-700 font-extrabold">{{ $matchResult['education_score'] }}%</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">{{ $matchResult['breakdown']['education']['explanation'] }}</p>
                        </div>

                        <!-- 3. Lokasi (20%) -->
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70">
                            <div class="flex items-center justify-between font-bold mb-1">
                                <span class="text-slate-700">3. Lokasi & Mobilitas (Bobot 20%)</span>
                                <span class="text-blue-700 font-extrabold">{{ $matchResult['location_score'] }}%</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">{{ $matchResult['breakdown']['location']['explanation'] }}</p>
                        </div>

                        <!-- 4. Pengalaman (15%) -->
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70">
                            <div class="flex items-center justify-between font-bold mb-1">
                                <span class="text-slate-700">4. Pengalaman Kerja (Bobot 15%)</span>
                                <span class="text-blue-700 font-extrabold">{{ $matchResult['experience_score'] }}%</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">{{ $matchResult['breakdown']['experience']['explanation'] }}</p>
                        </div>

                        <!-- 5. Minat (10%) -->
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70">
                            <div class="flex items-center justify-between font-bold mb-1">
                                <span class="text-slate-700">5. Kategori Minat (Bobot 10%)</span>
                                <span class="text-blue-700 font-extrabold">{{ $matchResult['interest_score'] }}%</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">{{ $matchResult['breakdown']['interest']['explanation'] }}</p>
                        </div>

                        <!-- 6. Usia (5%) -->
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70">
                            <div class="flex items-center justify-between font-bold mb-1">
                                <span class="text-slate-700">6. Kesesuaian Usia (Bobot 5%)</span>
                                <span class="text-blue-700 font-extrabold">{{ $matchResult['age_score'] }}%</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">{{ $matchResult['breakdown']['age']['explanation'] }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Deskripsi Pekerjaan -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Deskripsi Pekerjaan
                </h2>
                <div class="prose prose-sm text-slate-700 max-w-none whitespace-pre-line leading-relaxed text-xs sm:text-sm">
                    {{ $job->description ?: 'Deskripsi pekerjaan tidak dicantumkan oleh sumber asli.' }}
                </div>
            </div>

            <!-- Persyaratan & Kualifikasi -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Persyaratan & Kualifikasi
                </h2>
                <div class="prose prose-sm text-slate-700 max-w-none whitespace-pre-line leading-relaxed text-xs sm:text-sm">
                    {{ $job->requirements ?: 'Persyaratan khusus tidak dicantumkan dalam rincian lowongan.' }}
                </div>

                <!-- Keahlian yang Dibutuhkan -->
                @if($job->skills->isNotEmpty())
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2.5">Keahlian (Skills) yang Dibutuhkan:</h3>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($job->skills as $sk)
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                    {{ $sk->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tombol Aksi Lamaran Asli -->
            <div class="bg-slate-900 text-white rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold">Tertarik dengan posisi ini?</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kirimkan berkas lamaran Anda langsung ke penyedia lowongan resmi.</p>
                </div>
                <a href="{{ $job->original_url }}" target="_blank" rel="noopener noreferrer"
                   class="w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-lg shadow-blue-600/30 transition-all text-center">
                    Buka Website Sumber Asli
                </a>
            </div>
        </div>

        <!-- Kolom Kanan: Ringkasan Informasi Lowongan -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 uppercase tracking-wider">
                    Ringkasan Lowongan
                </h3>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Lokasi Kerja</span>
                        <span class="font-semibold text-slate-800">{{ $job->location }}</span>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Kategori Pekerjaan</span>
                        <span class="font-semibold text-slate-800">{{ $job->category ? $job->category->name : 'Umum' }}</span>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Pendidikan Minimal</span>
                        <span class="font-semibold text-slate-800">{{ $job->educationLevel ? $job->educationLevel->name : 'Semua Jenjang' }}</span>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengalaman Kerja</span>
                        <span class="font-semibold text-slate-800">
                            {{ $job->min_experience_years > 0 ? "Minimal {$job->min_experience_years} tahun" : 'Terbuka untuk Fresh Graduate' }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Kisaran Gaji</span>
                        <span class="font-semibold text-slate-800">{{ $job->formatted_salary }}</span>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Batas Usia</span>
                        <span class="font-semibold text-slate-800">
                            @if($job->min_age && $job->max_age)
                                {{ $job->min_age }} – {{ $job->max_age }} tahun
                            @elseif($job->min_age)
                                Minimal {{ $job->min_age }} tahun
                            @elseif($job->max_age)
                                Maksimal {{ $job->max_age }} tahun
                            @else
                                Tidak dicantumkan
                            @endif
                        </span>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Batas Waktu Lamaran (Deadline)</span>
                        <span class="font-semibold {{ $job->isExpired() ? 'text-red-600' : 'text-slate-800' }}">
                            {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->isoFormat('D MMMM Y') : 'Hingga Terisi' }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Sumber Publik</span>
                        <span class="font-semibold text-slate-800">{{ $job->source ? $job->source->name : 'Publik' }}</span>
                    </div>
                </div>
            </div>

            <!-- Lowongan Terkait -->
            @if($relatedJobs->isNotEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                        Lowongan Terkait
                    </h3>
                    <div class="space-y-3">
                        @foreach($relatedJobs as $rJob)
                            <a href="{{ route('jobs.show', $rJob->id) }}" class="block p-3 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/30 transition-all group">
                                <h4 class="text-xs font-bold text-slate-900 group-hover:text-blue-700 line-clamp-1">{{ $rJob->title }}</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $rJob->company }} • {{ $rJob->normalized_district ?: $rJob->normalized_regency }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
