@props(['job', 'score' => null, 'isSaved' => false])

<div class="bg-white rounded-2xl border border-slate-200/90 hover:border-blue-500 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between p-5 relative group">
    <div>
        <!-- Card Top Bar: Badges & Score -->
        <div class="flex items-start justify-between gap-2 mb-3">
            <div class="flex flex-wrap items-center gap-1.5">
                <!-- Location badge -->
                @if($job->is_remote)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Remote / WFH
                    </span>
                @elseif(str_contains(strtolower($job->normalized_regency ?? $job->location), 'sumenep'))
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        <svg class="w-3 h-3 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $job->normalized_district ? "Kec. {$job->normalized_district}" : 'Sumenep' }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 text-slate-700">
                        <svg class="w-3 h-3 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $job->normalized_regency ?: 'Luar Sumenep' }}
                    </span>
                @endif

                <!-- Category badge -->
                @if($job->category)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                        {{ $job->category->name }}
                    </span>
                @endif
            </div>

            <!-- Match Score Pill (if user authenticated & match computed) -->
            @if(isset($score['total_score']))
                @php
                    $sc = $score['total_score'];
                    $colorClass = $sc >= 85 ? 'bg-emerald-600 text-white' : ($sc >= 70 ? 'bg-blue-600 text-white' : ($sc >= 50 ? 'bg-amber-500 text-white' : 'bg-slate-600 text-white'));
                @endphp
                <div class="flex items-center space-x-1 shrink-0 px-2.5 py-1 rounded-xl text-xs font-black {{ $colorClass }} shadow-xs ring-1 ring-black/5" title="Tingkat kecocokan dengan kualifikasi profil Anda">
                    <span>{{ $sc }}%</span>
                    <span class="text-[10px] font-semibold uppercase tracking-wider opacity-90">Cocok</span>
                </div>
            @endif
        </div>

        <!-- Job Title -->
        <h3 class="text-base font-extrabold text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-2 leading-snug mb-1">
            <a href="{{ route('jobs.show', $job->id) }}" class="focus:outline-none">
                {{ $job->title }}
            </a>
        </h3>

        <!-- Company Name -->
        <p class="text-xs font-semibold text-slate-600 mb-3 flex items-center">
            <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <span class="truncate">{{ $job->company }}</span>
        </p>

        <!-- Salary Badge (Sangat Diperhatikan Pelamar) -->
        <div class="mb-3.5">
            @if($job->salary_min || $job->salary_max)
                <div class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/90 shadow-2xs">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ $job->formatted_salary }}</span>
                </div>
            @else
                <div class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-200">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ $job->formatted_salary }}</span>
                </div>
            @endif
        </div>

        <!-- Requirements Summary (Pendidikan & Pengalaman) -->
        <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600 mb-4 bg-slate-50/80 p-2.5 rounded-xl border border-slate-100">
            <div class="flex items-center" title="Pendidikan minimal yang disyaratkan">
                <svg class="w-3.5 h-3.5 mr-1.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                <span class="truncate font-semibold">{{ $job->educationLevel ? $job->educationLevel->name : 'Semua Jenjang' }}</span>
            </div>
            <div class="flex items-center" title="Pengalaman kerja minimal">
                <svg class="w-3.5 h-3.5 mr-1.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span class="truncate font-semibold">{{ $job->min_experience_years > 0 ? "Min. {$job->min_experience_years} thn" : 'Fresh Graduate' }}</span>
            </div>
        </div>

        <!-- Skills tags (first 3) -->
        @if($job->skills->isNotEmpty())
            <div class="flex flex-wrap gap-1 mb-4">
                @foreach($job->skills->take(3) as $sk)
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-800 border border-blue-100">
                        {{ $sk->name }}
                    </span>
                @endforeach
                @if($job->skills->count() > 3)
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] text-slate-500 font-medium bg-slate-100">
                        +{{ $job->skills->count() - 3 }} lainnya
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Card Bottom Footer: Posted Date, Source, and Action -->
    <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs gap-2">
        <div class="text-[11px] text-slate-400 min-w-0">
            <span class="truncate block">
                @if($job->posted_at)
                    {{ \Carbon\Carbon::parse($job->posted_at)->diffForHumans() }}
                @else
                    Baru tayang
                @endif
                @if($job->source)
                    • {{ $job->source->name }}
                @endif
            </span>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            @auth
                <form action="{{ route('jobs.save', $job->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="p-2 rounded-xl border {{ $isSaved ? 'border-blue-400 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-300' }} transition-colors"
                            title="{{ $isSaved ? 'Hapus Simpanan' : 'Simpan Lowongan' }}">
                        <svg class="w-4 h-4 {{ $isSaved ? 'text-blue-600 fill-blue-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    </button>
                </form>
            @endauth
            <a href="{{ route('jobs.show', $job->id) }}"
               class="inline-flex items-center px-3.5 py-2 text-xs font-bold rounded-xl bg-blue-700 hover:bg-blue-800 text-white shadow-2xs transition-all hover:scale-102">
                Detail & Lamar
                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
</div>
