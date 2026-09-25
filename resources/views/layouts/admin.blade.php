<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100 overflow-y-scroll overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel — JOS')</title>

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
<body class="h-full text-slate-800 antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-full flex">
        <!-- Off-canvas menu for mobile -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" style="display: none;">
            <div class="fixed inset-0 bg-slate-900/80" @click="sidebarOpen = false"></div>
            <div class="fixed inset-0 flex">
                <div class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="flex flex-col flex-1 bg-slate-900 pt-5 pb-4 px-4 overflow-y-auto">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="JOS Logo" class="h-8 w-auto object-contain">
                                <span class="text-lg font-bold text-white">JOS Admin</span>
                            </div>
                            <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <nav class="mt-6 flex-1 space-y-1">
                            @include('layouts.partials.admin-sidebar')
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-slate-900 border-r border-slate-800">
            <div class="flex items-center h-16 px-6 bg-slate-950 border-b border-slate-800/80 justify-between">
                <div class="flex items-center space-x-2.5">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="JOS Logo" class="h-9 w-auto object-contain">
                    <div>
                        <div class="text-base font-bold text-white tracking-tight">JOS Admin</div>
                        <div class="text-[10px] text-blue-400 font-medium">Kabupaten Sumenep</div>
                    </div>
                </div>
            </div>

            <div class="flex-1 flex flex-col overflow-y-auto p-4 space-y-6">
                @include('layouts.partials.admin-sidebar')
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span>Sistem Aktif</span>
                    </div>
                    <span>v1.0</span>
                </div>
            </div>
        </aside>

        <!-- Main column -->
        <div class="lg:pl-64 flex flex-col flex-1 min-w-0">
            <!-- Top bar -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30 shadow-xs">
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-lg font-bold text-slate-800">@yield('page_title', 'Admin Dashboard')</h1>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Lihat Situs Publik
                    </a>

                    <div class="h-6 w-px bg-slate-200"></div>

                    <div class="flex items-center space-x-2 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 text-white flex items-center justify-center font-bold text-xs">
                            A
                        </div>
                        <span class="text-xs font-semibold text-slate-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Keluar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Alerts -->
            <div class="px-4 sm:px-6 lg:px-8 mt-4">
                @if(session('success'))
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2.5">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2.5">
                            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
