<div class="space-y-6">
    <!-- Main Group -->
    <div>
        <div class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Utama</div>
        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.statistics') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.statistics*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Statistik & Analisis
            </a>
        </div>
    </div>

    <!-- Job Vacancies -->
    <div>
        <div class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Data Lowongan</div>
        <div class="space-y-1">
            <a href="{{ route('admin.jobs.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.jobs.index') && !request('status') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Semua Lowongan
            </a>
            <a href="{{ route('admin.jobs.index', ['status' => 'active']) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request('status') === 'active' ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-3"></span>
                Lowongan Aktif
            </a>
            <a href="{{ route('admin.jobs.index', ['status' => 'expired']) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request('status') === 'expired' ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span class="w-2 h-2 rounded-full bg-red-400 mr-3"></span>
                Lowongan Expired
            </a>
        </div>
    </div>

    <!-- Web Scraping -->
    <div>
        <div class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Web Scraping</div>
        <div class="space-y-1">
            <a href="{{ route('admin.scraping-sources.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.scraping-sources*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Sumber Scraping
            </a>
            <a href="{{ route('admin.scraping-logs.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.scraping-logs*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Monitoring Log
            </a>
        </div>
    </div>

    <!-- Master Data -->
    <div>
        <div class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Master Data</div>
        <div class="space-y-1">
            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.categories*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kategori Pekerjaan
            </a>
            <a href="{{ route('admin.skills.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.skills*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Master Skill & Alias
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.users*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Kelola Pengguna
            </a>
        </div>
    </div>
</div>
