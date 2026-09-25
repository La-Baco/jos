<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 overflow-y-scroll overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JOS — Job Opportunity Sumenep | Temukan Peluang Kerjamu')</title>
    <meta name="description" content="@yield('meta_description', 'Platform agregator dan rekomendasi lowongan pekerjaan dari berbagai sumber publik untuk masyarakat Kabupaten Sumenep.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased" x-data="{ mobileMenuOpen: false }">
    <!-- Top Bar / Announcement -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center space-x-2 text-center sm:text-left">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-600/30 text-blue-300 border border-blue-500/30">
                    KABUPATEN SUMENEP
                </span>
                <span>Platform Agregasi Lowongan Kerja Publik Resmi untuk Warga Sumenep & Madura</span>
            </div>
            <div class="text-[11px] text-slate-400">
                100% Gratis • Tanpa Perantara • Diarahkan Langsung ke Sumber Asli
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="JOS Logo" class="h-10 w-auto object-contain group-hover:scale-105 transition-transform">
                        <div>
                            <div class="flex items-center space-x-1.5">
                                <span class="text-xl font-bold tracking-tight text-slate-900">JOS</span>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200">Sumenep</span>
                            </div>
                            <p class="text-[11px] text-slate-500 hidden sm:block font-medium">Job Opportunity Sumenep</p>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex md:ml-8 md:space-x-1 lg:space-x-2">
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                            Beranda
                        </a>
                        <a href="{{ route('jobs.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('jobs.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                            Semua Lowongan
                        </a>
                        @auth
                            @if(auth()->user()->isJobSeeker())
                                <a href="{{ route('user.recommendations') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ request()->routeIs('user.recommendations') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                    Rekomendasi
                                    <span class="inline-flex items-center justify-center px-1.5 py-0.2 text-[10px] font-bold leading-none text-white bg-emerald-600 rounded-full ml-1">Baru</span>
                                </a>
                                <a href="{{ route('user.saved-jobs') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('user.saved-jobs') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                    Lowongan Tersimpan
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Action Buttons / User Menu -->
                <div class="hidden md:flex md:items-center md:space-x-3">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 px-3 py-2 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl shadow-xs transition-all hover:shadow-md hover:-translate-y-0.5">
                            Daftar Sekarang
                        </a>
                    @else
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 transition-colors">
                                Panel Admin
                            </a>
                        @endif

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" type="button" class="flex items-center space-x-2 text-sm font-medium text-slate-700 hover:text-slate-900 p-1.5 rounded-xl border border-slate-200 hover:border-slate-300 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left hidden lg:block">
                                    <div class="text-xs font-semibold text-slate-900 leading-tight">{{ auth()->user()->name }}</div>
                                    <div class="text-[10px] text-slate-500">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Pencari Kerja' }}</div>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black/5 divide-y divide-slate-100 focus:outline-none z-50">
                                <div class="px-4 py-3">
                                    <p class="text-xs text-slate-500 font-medium">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    @if(auth()->user()->isJobSeeker())
                                        <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                                            <svg class="w-4 h-4 mr-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('user.profile.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                                            <svg class="w-4 h-4 mr-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            Profil Lengkap
                                        </a>
                                        <a href="{{ route('user.profile.skills') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                                            <svg class="w-4 h-4 mr-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Kelola Skill
                                        </a>
                                        <a href="{{ route('user.profile.experiences') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                                            <svg class="w-4 h-4 mr-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            Riwayat Pengalaman
                                        </a>
                                    @else
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                                            Dashboard Admin
                                        </a>
                                    @endif
                                </div>
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4 mr-2.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Keluar (Logout)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                        <svg class="h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg class="h-6 w-6" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden border-b border-slate-200 bg-white" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                    Beranda
                </a>
                <a href="{{ route('jobs.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('jobs.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                    Semua Lowongan
                </a>
                @auth
                    @if(auth()->user()->isJobSeeker())
                        <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            Dashboard Saya
                        </a>
                        <a href="{{ route('user.recommendations') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('user.recommendations') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            Rekomendasi Pekerjaan
                        </a>
                        <a href="{{ route('user.saved-jobs') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('user.saved-jobs') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            Lowongan Tersimpan
                        </a>
                        <a href="{{ route('user.profile.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('user.profile.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            Profil & Preferensi
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-purple-700 bg-purple-50">
                            Masuk Panel Admin
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-100">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-base font-medium text-red-600 hover:bg-red-50">
                            Keluar (Logout)
                        </button>
                    </form>
                @else
                    <div class="pt-3 pb-2 border-t border-slate-200 flex flex-col space-y-2">
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50">
                            Masuk ke Akun
                        </a>
                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2.5 rounded-xl bg-blue-700 text-white font-semibold text-sm hover:bg-blue-800">
                            Daftar Sekarang
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Global Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start space-x-3 mb-4">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1 font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 flex items-start space-x-3 mb-4">
                <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1 font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="rounded-xl bg-blue-50 border border-blue-200 p-4 text-sm text-blue-800 flex items-start space-x-3 mb-4">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1 font-medium">{{ session('info') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-800 flex items-start space-x-3 mb-4">
                <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div class="flex-1">
                    <p class="font-semibold mb-1">Periksa kembali data yang Anda masukkan:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-16 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-extrabold text-lg">
                            J
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-white">JOS</span>
                            <span class="text-xs text-blue-400 ml-1 font-semibold">Job Opportunity Sumenep</span>
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm max-w-md leading-relaxed">
                        JOS adalah platform agregator dan rekomendasi lowongan pekerjaan yang mengumpulkan info kerja publik dari internet untuk membantu masyarakat Kabupaten Sumenep menemukan peluang karir yang sesuai kualifikasi.
                    </p>
                    <div class="text-xs text-slate-500 bg-slate-800/60 p-3 rounded-xl border border-slate-800">
                        <strong class="text-slate-300">Pemberitahuan:</strong> JOS bukan marketplace lowongan kerja. Kami tidak memungut biaya apapun dan selalu mengarahkan pelamar ke halaman sumber publik asli penyedia lowongan.
                    </div>
                </div>

                <!-- Col 2: Navigasi -->
                <div>
                    <h3 class="text-white font-semibold mb-3 text-sm">Navigasi</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition-colors">Daftar Lowongan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar Pencari Kerja</a></li>
                    </ul>
                </div>

                <!-- Col 3: Wilayah Prioritas -->
                <div>
                    <h3 class="text-white font-semibold mb-3 text-sm">Cakupan Wilayah</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-3">
                        Mencakup 27 Kecamatan di Kabupaten Sumenep (Daratan & Kepulauan), Madura Raya, Jawa Timur, hingga peluang Remote.
                    </p>
                    <div class="flex flex-wrap gap-1.5 text-[11px]">
                        <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300">Kota Sumenep</span>
                        <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300">Kalianget</span>
                        <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300">Saronggi</span>
                        <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300">Bluto</span>
                        <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300">Pamekasan</span>
                        <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300">Surabaya</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} JOS — Job Opportunity Sumenep. Dibuat untuk Kemajuan Tenaga Kerja Sumenep.</p>
                <p class="mt-2 sm:mt-0">Algoritma Rule-Based Matching • Transparan & Tanpa AI</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
