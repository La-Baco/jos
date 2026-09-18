<div class="mb-8 border-b border-slate-200">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Profil Pencari Kerja</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi kualifikasi Anda agar algoritma rekomendasi JOS dapat mencocokkan lowongan dengan akurasi optimal.</p>
        </div>
        <div class="flex items-center space-x-2 bg-blue-50 border border-blue-200/80 px-3.5 py-2 rounded-xl shrink-0">
            <div class="text-xs">
                <span class="font-bold text-blue-900">Kelengkapan Profil:</span>
                <span class="font-black text-blue-700 ml-1">{{ auth()->user()->profileCompletionPercentage() }}%</span>
            </div>
            <div class="w-16 bg-blue-200 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ auth()->user()->profileCompletionPercentage() }}%"></div>
            </div>
        </div>
    </div>

    <!-- Sub Nav Tabs -->
    <nav class="flex space-x-2 sm:space-x-8 overflow-x-auto" aria-label="Tabs">
        <a href="{{ route('user.profile.index') }}"
           class="whitespace-nowrap pb-3 px-1 border-b-2 font-semibold text-sm transition-colors {{ request()->routeIs('user.profile.index') ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
            1. Data Diri & Pendidikan
        </a>
        <a href="{{ route('user.profile.skills') }}"
           class="whitespace-nowrap pb-3 px-1 border-b-2 font-semibold text-sm transition-colors {{ request()->routeIs('user.profile.skills') ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
            2. Master Skill & Keahlian
            <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs font-bold {{ auth()->user()->skills->count() > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                {{ auth()->user()->skills->count() }}
            </span>
        </a>
        <a href="{{ route('user.profile.experiences') }}"
           class="whitespace-nowrap pb-3 px-1 border-b-2 font-semibold text-sm transition-colors {{ request()->routeIs('user.profile.experiences') ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
            3. Pengalaman Kerja
            <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs font-bold {{ auth()->user()->experiences->count() > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                {{ auth()->user()->experiences->count() }}
            </span>
        </a>
    </nav>
</div>
