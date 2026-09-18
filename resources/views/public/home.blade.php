@extends('layouts.app')

@section('title', 'JOS — Job Opportunity Sumenep | Temukan Peluang Kerjamu')
@section('meta_description', 'Temukan lowongan pekerjaan dari berbagai sumber publik dan dapatkan rekomendasi pekerjaan yang sesuai dengan profilmu di Kabupaten Sumenep.')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-slate-900 via-blue-950 to-slate-900 text-white overflow-hidden py-16 sm:py-24">
    <!-- Subtle grid glow background -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-40"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-semibold mb-6 backdrop-blur">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Agregator Lowongan Terpercaya Kabupaten Sumenep & Madura</span>
        </div>

        <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-white max-w-4xl mx-auto leading-tight">
            JOS <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-teal-300">— Job Opportunity Sumenep</span>
        </h1>
        <p class="mt-4 text-xl sm:text-2xl font-bold text-blue-200 tracking-wide">
            "Temukan Peluang Kerjamu"
        </p>
        <p class="mt-4 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed font-normal">
            Temukan lowongan pekerjaan dari berbagai sumber publik dan dapatkan rekomendasi pekerjaan yang sesuai dengan profilmu.
        </p>

        <!-- CTAs -->
        <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-3.5">
            <a href="{{ route('jobs.index') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-base shadow-lg shadow-blue-600/30 transition-all hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Cari Lowongan
            </a>
            @guest
                <a href="{{ route('register') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-base border border-white/20 backdrop-blur transition-all">
                    Daftar Sekarang (Gratis)
                </a>
            @else
                <a href="{{ route('user.dashboard') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-base border border-white/20 backdrop-blur transition-all">
                    Buka Dashboard Saya
                </a>
            @endguest
        </div>

        <!-- Live Statistics Bar -->
        <div class="mt-14 max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 bg-white/5 border border-white/10 p-5 rounded-2xl backdrop-blur">
            <div class="text-center p-2">
                <div class="text-2xl sm:text-3xl font-black text-white">{{ $stats['total_jobs'] }}</div>
                <div class="text-xs text-slate-300 font-medium mt-1">Total Lowongan</div>
            </div>
            <div class="text-center p-2 border-l border-white/10">
                <div class="text-2xl sm:text-3xl font-black text-emerald-400">{{ $stats['active_jobs'] }}</div>
                <div class="text-xs text-slate-300 font-medium mt-1">Lowongan Aktif</div>
            </div>
            <div class="text-center p-2 border-l border-white/10">
                <div class="text-2xl sm:text-3xl font-black text-blue-300">{{ $stats['sumenep_jobs'] }}</div>
                <div class="text-xs text-slate-300 font-medium mt-1">Wilayah Sumenep</div>
            </div>
            <div class="text-center p-2 border-l border-white/10">
                <div class="text-2xl sm:text-3xl font-black text-amber-300">{{ $stats['registered_users'] }}</div>
                <div class="text-xs text-slate-300 font-medium mt-1">Pengguna Terdaftar</div>
            </div>
        </div>
    </div>
</section>

<!-- Section: Quick Search Bar -->
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-7 relative z-20">
    <form action="{{ route('jobs.index') }}" method="GET" class="bg-white p-3 sm:p-4 rounded-2xl shadow-xl shadow-slate-200/70 border border-slate-200 flex flex-col md:flex-row gap-3">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="q" placeholder="Cari posisi kerja, skill, atau nama perusahaan..."
                   class="w-full pl-10 pr-4 py-3 text-sm border-0 bg-slate-50 focus:bg-white rounded-xl focus:ring-2 focus:ring-blue-600">
        </div>

        <div class="w-full md:w-56">
            <select name="location" class="w-full py-3 px-3.5 text-sm border-0 bg-slate-50 rounded-xl focus:ring-2 focus:ring-blue-600 font-medium text-slate-700">
                <option value="">Semua Wilayah</option>
                <option value="sumenep">Kab. Sumenep</option>
                <option value="Kota Sumenep">Kec. Kota Sumenep</option>
                <option value="Kalianget">Kec. Kalianget</option>
                <option value="Saronggi">Kec. Saronggi</option>
                <option value="Pamekasan">Pamekasan</option>
                <option value="remote">Remote / WFH</option>
            </select>
        </div>

        <button type="submit" class="px-7 py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm rounded-xl shadow-sm transition-colors flex items-center justify-center">
            Temukan Lowongan
        </button>
    </form>
</section>

<!-- Section: Bagaimana JOS Bekerja? -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <h2 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-2">Alur Kerja Platform</h2>
            <p class="text-3xl font-extrabold text-slate-900 tracking-tight">Bagaimana JOS Bekerja?</p>
            <p class="text-sm text-slate-600 mt-2">
                Empat langkah praktis menemukan lowongan kerja yang relevan dengan kualifikasi Anda di Sumenep.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
            <!-- Step 1 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs relative">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-black text-lg mb-4">
                    1
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Buat Profil</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Daftar akun dan lengkapi data pendidikan, keahlian/skill, pengalaman, serta preferensi lokasi kerja Anda.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs relative">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-black text-lg mb-4">
                    2
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Sistem Mencocokkan</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Algoritma rule-based scoring menghitung kecocokan skill (30%), pendidikan (20%), lokasi (20%), pengalaman (15%), minat (10%), dan usia (5%).
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs relative">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-lg mb-4">
                    3
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Dapatkan Rekomendasi</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Lihat daftar lowongan berperingkat skor kecocokan tertinggi lengkap dengan penjelasan rinci mengapa pekerjaan tersebut cocok.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs relative">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-black text-lg mb-4">
                    4
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Lamar di Sumber Asli</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Klik tautan langsung ke website atau kanal publik resmi penyedia lowongan untuk mengirimkan lamaran Anda secara aman.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Section: Kategori Pekerjaan Populer -->
<section class="py-16 bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
            <div>
                <h2 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-1.5">Eksplorasi Karir</h2>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kategori Pekerjaan Populer</h3>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center mt-2 sm:mt-0">
                Lihat Semua Kategori
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($popularCategories as $cat)
                <a href="{{ route('jobs.index', ['category' => $cat->id]) }}"
                   class="p-4 rounded-2xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/40 hover:shadow-xs transition-all group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-blue-600 group-hover:text-white text-slate-700 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-blue-700 truncate transition-colors">{{ $cat->name }}</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $cat->jobs_count }} lowongan aktif</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Section: Lowongan Terbaru -->
<section class="py-16 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
            <div>
                <h2 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-1.5">Terbaru Ditambahkan</h2>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Lowongan Pekerjaan Terbaru</h3>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center mt-2 sm:mt-0">
                Lihat Semua Lowongan ({{ $stats['active_jobs'] }})
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if($latestJobs->isEmpty())
            <div class="text-center py-12 bg-white rounded-2xl border border-slate-200">
                <p class="text-slate-500 text-sm">Belum ada data lowongan aktif saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestJobs as $job)
                    <x-job-card :job="$job" :score="$jobScores[$job->id] ?? null" />
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
