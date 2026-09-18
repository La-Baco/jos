@extends('layouts.app')

@section('title', 'Semua Lowongan Pekerjaan — JOS')
@section('meta_description', 'Cari dan saring lowongan pekerjaan terverifikasi di Kabupaten Sumenep, Madura Raya, dan posisi Remote.')

@section('content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Daftar Lowongan Pekerjaan</h1>
        <p class="text-sm text-slate-300 mt-1 max-w-2xl">
            Informasi lowongan kerja dari berbagai sumber publik di internet yang telah dinormalisasi untuk wilayah Sumenep & sekitarnya.
        </p>

        @auth
            @if(auth()->user()->isJobSeeker())
                <div class="mt-4 inline-flex items-center space-x-2 bg-blue-500/20 border border-blue-400/30 px-3 py-1.5 rounded-xl text-xs text-blue-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Skor kecocokan personal aktif berdasarkan profil kualifikasi Anda.</span>
                </div>
            @endif
        @endauth
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ filterDrawerOpen: false }">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filter (Desktop) -->
        <div class="hidden lg:block lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs sticky top-24">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Filter Pencarian</h2>
                    <a href="{{ route('jobs.index') }}" class="text-xs font-semibold text-blue-700 hover:underline">
                        Reset Filter
                    </a>
                </div>

                <form action="{{ route('jobs.index') }}" method="GET" class="space-y-5">
                    <!-- Kata Kunci -->
                    <div>
                        <label for="q" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Kunci</label>
                        <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Posisi atau perusahaan..."
                               class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="location" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Wilayah / Lokasi</label>
                        <select name="location" id="location" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Lokasi</option>
                            <option value="sumenep" {{ request('location') === 'sumenep' ? 'selected' : '' }}>Semua Kab. Sumenep</option>
                            @foreach($districts as $dst)
                                <option value="{{ $dst->name }}" {{ request('location') === $dst->name ? 'selected' : '' }}>Kec. {{ $dst->name }}</option>
                            @endforeach
                            <option value="pamekasan" {{ request('location') === 'pamekasan' ? 'selected' : '' }}>Pamekasan</option>
                            <option value="surabaya" {{ request('location') === 'surabaya' ? 'selected' : '' }}>Surabaya</option>
                            <option value="remote" {{ request('location') === 'remote' ? 'selected' : '' }}>Remote / WFH</option>
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Pekerjaan</label>
                        <select name="category" id="category" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pendidikan -->
                    <div>
                        <label for="education" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pendidikan Minimal</label>
                        <select name="education" id="education" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Tingkat</option>
                            @foreach($educationLevels as $lvl)
                                <option value="{{ $lvl->id }}" {{ request('education') == $lvl->id ? 'selected' : '' }}>{{ $lvl->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Pekerjaan -->
                    <div>
                        <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Pekerjaan</label>
                        <select name="type" id="type" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Tipe</option>
                            <option value="Full-time" {{ request('type') === 'Full-time' ? 'selected' : '' }}>Penuh Waktu (Full-time)</option>
                            <option value="Part-time" {{ request('type') === 'Part-time' ? 'selected' : '' }}>Paruh Waktu (Part-time)</option>
                            <option value="Kontrak" {{ request('type') === 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="Freelance" {{ request('type') === 'Freelance' ? 'selected' : '' }}>Freelance / Lepas</option>
                        </select>
                    </div>

                    <!-- Pengalaman -->
                    <div>
                        <label for="experience" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pengalaman Kerja</label>
                        <select name="experience" id="experience" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Pengalaman</option>
                            <option value="fresh" {{ request('experience') === 'fresh' ? 'selected' : '' }}>Fresh Graduate (0 Tahun)</option>
                            <option value="1-2" {{ request('experience') === '1-2' ? 'selected' : '' }}>1 - 2 Tahun</option>
                            <option value="3+" {{ request('experience') === '3+' ? 'selected' : '' }}>3+ Tahun</option>
                        </select>
                    </div>

                    <!-- Remote Only -->
                    <div class="pt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remote" value="1" {{ request('remote') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <span class="ml-2 text-xs font-bold text-slate-800">Hanya Lowongan Remote (WFH)</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content (Job Listings) -->
        <div class="lg:col-span-3">
            <!-- Filter Bar Mobile Trigger & Sorting -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-xs text-slate-600 font-medium">
                    Menampilkan <strong class="text-slate-900">{{ $jobs->total() }}</strong> lowongan ditemukan
                    @if(request('q')) untuk kata kunci "<span class="text-blue-700 font-bold">{{ request('q') }}</span>"@endif
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Mobile Filter Toggle -->
                    <button @click="filterDrawerOpen = !filterDrawerOpen" class="lg:hidden px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>

                    <!-- Sorting -->
                    <form action="{{ route('jobs.index') }}" method="GET" class="flex items-center space-x-1.5 text-xs">
                        <!-- Keep other queries -->
                        @foreach(request()->except('sort') as $k => $v)
                            @if(is_array($v))
                                @foreach($v as $subV)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $subV }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach
                        <label for="sort" class="text-slate-500 font-medium">Urutkan:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()"
                                class="py-1 px-2.5 border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 focus:ring-blue-600">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="deadline" {{ request('sort') === 'deadline' ? 'selected' : '' }}>Mendekati Deadline</option>
                            <option value="salary" {{ request('sort') === 'salary' ? 'selected' : '' }}>Gaji Tertinggi</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Job Cards Grid -->
            @if($jobs->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">Tidak Ada Lowongan yang Sesuai</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                        Coba ubah kata kunci atau longgarkan filter wilayah dan kualifikasi yang Anda pilih.
                    </p>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-700 text-white font-bold text-xs hover:bg-blue-800">
                        Reset Semua Filter
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($jobs as $job)
                        <x-job-card :job="$job" :score="$jobScores[$job->id] ?? null" :isSaved="in_array($job->id, $savedJobIds)" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $jobs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
