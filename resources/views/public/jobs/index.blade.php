@extends('layouts.app')

@section('title', 'Semua Lowongan Pekerjaan — JOS')
@section('meta_description', 'Cari dan saring lowongan pekerjaan terverifikasi di Kabupaten Sumenep, Madura Raya, dan posisi Remote.')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white py-10 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30 text-xs font-semibold mb-3">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Database Terpadu Lowongan Kerja Sumenep & Madura</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Eksplorasi Lowongan Pekerjaan</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Temukan peluang karir publik yang telah dinormalisasi untuk 27 Kecamatan di Kabupaten Sumenep, Madura Raya, hingga peluang kerja Remote.
        </p>

        @auth
            @if(auth()->user()->isJobSeeker())
                <div class="mt-4 inline-flex items-center space-x-2 bg-emerald-500/20 border border-emerald-400/30 px-3.5 py-1.5 rounded-xl text-xs text-emerald-200">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <span>Skor kecocokan personal aktif berdasarkan profil kualifikasi akun Anda.</span>
                </div>
            @endif
        @endauth
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ filterDrawerOpen: false }">
    <!-- Quick Filter Pills (Akses 1-Klik Paling Disukai Pencari Kerja) -->
    <div class="mb-6 overflow-x-auto pb-2 scrollbar-none">
        <div class="flex items-center space-x-2 text-xs font-bold whitespace-nowrap">
            <span class="text-slate-400 text-[11px] uppercase tracking-wider mr-1 hidden sm:inline">Pintasan:</span>
            
            <a href="{{ route('jobs.index') }}"
               class="px-3.5 py-2 rounded-xl border transition-all {{ !request()->hasAny(['location', 'education', 'experience', 'remote']) ? 'bg-blue-700 text-white border-blue-700 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
                Semua Lowongan
            </a>

            <a href="{{ route('jobs.index', array_merge(request()->query(), ['location' => 'sumenep'])) }}"
               class="px-3.5 py-2 rounded-xl border transition-all {{ request('location') === 'sumenep' ? 'bg-blue-700 text-white border-blue-700 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
                📍 Khusus Sumenep
            </a>

            <a href="{{ route('jobs.index', array_merge(request()->query(), ['experience' => 'fresh'])) }}"
               class="px-3.5 py-2 rounded-xl border transition-all {{ request('experience') === 'fresh' ? 'bg-blue-700 text-white border-blue-700 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
                🌱 Fresh Graduate
            </a>

            @php
                $smaId = $educationLevels->firstWhere('code', 'SMA/SMK')?->id;
                $s1Id = $educationLevels->firstWhere('code', 'S1')?->id;
            @endphp
            @if($smaId)
                <a href="{{ route('jobs.index', array_merge(request()->query(), ['education' => $smaId])) }}"
                   class="px-3.5 py-2 rounded-xl border transition-all {{ request('education') == $smaId ? 'bg-blue-700 text-white border-blue-700 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
                    🎓 Lulusan SMA/SMK
                </a>
            @endif

            @if($s1Id)
                <a href="{{ route('jobs.index', array_merge(request()->query(), ['education' => $s1Id])) }}"
                   class="px-3.5 py-2 rounded-xl border transition-all {{ request('education') == $s1Id ? 'bg-blue-700 text-white border-blue-700 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
                    🎓 Lulusan S1
                </a>
            @endif

            <a href="{{ route('jobs.index', array_merge(request()->query(), ['remote' => 1])) }}"
               class="px-3.5 py-2 rounded-xl border transition-all {{ request('remote') ? 'bg-blue-700 text-white border-blue-700 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
                💻 Remote (WFH)
            </a>

            @if(request()->hasAny(['q', 'location', 'category', 'education', 'type', 'experience', 'remote']))
                <a href="{{ route('jobs.index') }}"
                   class="px-3 py-2 rounded-xl text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 flex items-center transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Hapus Filter
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filter (Desktop) -->
        <div class="hidden lg:block lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs sticky top-24">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter Pencarian
                    </h2>
                    <a href="{{ route('jobs.index') }}" class="text-xs font-semibold text-blue-700 hover:underline">
                        Reset
                    </a>
                </div>

                <form action="{{ route('jobs.index') }}" method="GET" class="space-y-4">
                    <!-- Kata Kunci -->
                    <div>
                        <label for="q" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Cari Posisi / Skill</label>
                        <div class="relative">
                            <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Contoh: Admin, Kasir, IT..."
                                   class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Lokasi / Wilayah -->
                    <div>
                        <label for="location" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Wilayah / Kecamatan</label>
                        <select name="location" id="location" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Lokasi</option>
                            <option value="sumenep" {{ request('location') === 'sumenep' ? 'selected' : '' }}>Kab. Sumenep (Semua)</option>
                            <optgroup label="27 Kecamatan di Sumenep">
                                @foreach($districts as $dst)
                                    <option value="{{ $dst->name }}" {{ request('location') === $dst->name ? 'selected' : '' }}>Kec. {{ $dst->name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Luar Sumenep & Jatim">
                                <option value="pamekasan" {{ request('location') === 'pamekasan' ? 'selected' : '' }}>Pamekasan</option>
                                <option value="sampang" {{ request('location') === 'sampang' ? 'selected' : '' }}>Sampang</option>
                                <option value="bangkalan" {{ request('location') === 'bangkalan' ? 'selected' : '' }}>Bangkalan</option>
                                <option value="surabaya" {{ request('location') === 'surabaya' ? 'selected' : '' }}>Surabaya</option>
                            </optgroup>
                            <option value="remote" {{ request('location') === 'remote' ? 'selected' : '' }}>Remote / WFH</option>
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bidang / Kategori</label>
                        <select name="category" id="category" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pendidikan -->
                    <div>
                        <label for="education" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pendidikan Minimal</label>
                        <select name="education" id="education" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Jenjang</option>
                            @foreach($educationLevels as $lvl)
                                <option value="{{ $lvl->id }}" {{ request('education') == $lvl->id ? 'selected' : '' }}>{{ $lvl->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Pekerjaan -->
                    <div>
                        <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tipe Kerja</label>
                        <select name="type" id="type" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Tipe</option>
                            <option value="Full-time" {{ request('type') === 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ request('type') === 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Kontrak" {{ request('type') === 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="Freelance" {{ request('type') === 'Freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>

                    <!-- Pengalaman -->
                    <div>
                        <label for="experience" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pengalaman Kerja</label>
                        <select name="experience" id="experience" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Pengalaman</option>
                            <option value="fresh" {{ request('experience') === 'fresh' ? 'selected' : '' }}>Fresh Graduate (0 Tahun)</option>
                            <option value="1-2" {{ request('experience') === '1-2' ? 'selected' : '' }}>1 - 2 Tahun</option>
                            <option value="3+" {{ request('experience') === '3+' ? 'selected' : '' }}>3+ Tahun</option>
                        </select>
                    </div>

                    <!-- Remote Only -->
                    <div class="pt-1">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remote" value="1" {{ request('remote') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <span class="ml-2 text-xs font-bold text-slate-800">Hanya Lowongan Remote</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content (Job Listings) -->
        <div class="lg:col-span-3">
            <!-- Filter Bar Mobile Trigger & Sorting -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
                <div class="text-xs text-slate-600 font-medium flex items-center">
                    <span>Menampilkan <strong class="text-slate-900 font-bold text-sm">{{ $jobs->total() }}</strong> lowongan aktif</span>
                    @if(request('q'))
                        <span class="ml-1 text-slate-500">untuk "<strong class="text-blue-700">{{ request('q') }}</strong>"</span>
                    @endif
                </div>

                <div class="flex items-center justify-between sm:justify-end space-x-3">
                    <!-- Mobile Filter Drawer Trigger Button -->
                    <button @click="filterDrawerOpen = true"
                            type="button"
                            class="lg:hidden inline-flex items-center px-3.5 py-2 rounded-xl bg-blue-50 border border-blue-200 text-xs font-bold text-blue-700 shadow-2xs">
                        <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter & Wilayah
                        @if(request()->hasAny(['q', 'location', 'category', 'education', 'type', 'experience', 'remote']))
                            <span class="w-2 h-2 rounded-full bg-blue-600 ml-1.5"></span>
                        @endif
                    </button>

                    <!-- Sorting Dropdown -->
                    <form action="{{ route('jobs.index') }}" method="GET" class="flex items-center space-x-2 text-xs">
                        @foreach(request()->except('sort') as $k => $v)
                            @if(is_array($v))
                                @foreach($v as $subV)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $subV }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach
                        <label for="sort" class="text-slate-500 font-medium hidden sm:inline">Urutkan:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()"
                                class="py-1.5 px-3 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 focus:bg-white focus:ring-blue-600">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="deadline" {{ request('sort') === 'deadline' ? 'selected' : '' }}>Mendekati Deadline</option>
                            <option value="salary" {{ request('sort') === 'salary' ? 'selected' : '' }}>Gaji Tertinggi</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Job Cards Grid -->
            @if($jobs->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">Tidak Ada Lowongan yang Sesuai</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                        Kriteria filter Anda terlalu spesifik. Coba hapus sebagian filter atau cari lowongan lain di Kabupaten Sumenep.
                    </p>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-blue-700 text-white font-bold text-xs hover:bg-blue-800 transition-colors">
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

    <!-- Mobile Filter Slide-over Drawer (Touch-Friendly Modal) -->
    <div x-show="filterDrawerOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-hidden lg:hidden"
         style="display: none;">
        <!-- Backdrop -->
        <div @click="filterDrawerOpen = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="filterDrawerOpen"
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-sm bg-white shadow-2xl flex flex-col justify-between">
                
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Filter Lowongan</h3>
                        <p class="text-[11px] text-slate-500">Sesuaikan dengan kualifikasi Anda</p>
                    </div>
                    <button @click="filterDrawerOpen = false" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('jobs.index') }}" method="GET" class="p-5 overflow-y-auto space-y-4 flex-1">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kata Kunci</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Posisi atau perusahaan..."
                               class="w-full px-3 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Wilayah / Lokasi</label>
                        <select name="location" class="w-full px-3 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Lokasi</option>
                            <option value="sumenep" {{ request('location') === 'sumenep' ? 'selected' : '' }}>Kab. Sumenep (Semua)</option>
                            <optgroup label="27 Kecamatan di Sumenep">
                                @foreach($districts as $dst)
                                    <option value="{{ $dst->name }}" {{ request('location') === $dst->name ? 'selected' : '' }}>Kec. {{ $dst->name }}</option>
                                @endforeach
                            </optgroup>
                            <option value="remote" {{ request('location') === 'remote' ? 'selected' : '' }}>Remote / WFH</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Pekerjaan</label>
                        <select name="category" class="w-full px-3 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pendidikan Minimal</label>
                        <select name="education" class="w-full px-3 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Tingkat</option>
                            @foreach($educationLevels as $lvl)
                                <option value="{{ $lvl->id }}" {{ request('education') == $lvl->id ? 'selected' : '' }}>{{ $lvl->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pengalaman</label>
                        <select name="experience" class="w-full px-3 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                            <option value="">Semua Pengalaman</option>
                            <option value="fresh" {{ request('experience') === 'fresh' ? 'selected' : '' }}>Fresh Graduate (0 Tahun)</option>
                            <option value="1-2" {{ request('experience') === '1-2' ? 'selected' : '' }}>1 - 2 Tahun</option>
                            <option value="3+" {{ request('experience') === '3+' ? 'selected' : '' }}>3+ Tahun</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remote" value="1" {{ request('remote') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 rounded border-slate-300">
                            <span class="ml-2 text-xs font-bold text-slate-800">Hanya Lowongan Remote</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center space-x-2">
                        <button type="submit" class="flex-1 py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-xs">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('jobs.index') }}" class="py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
