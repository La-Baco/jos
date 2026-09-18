@extends('layouts.app')

@section('title', 'Masuk ke Akun — JOS')
@section('meta_description', 'Masuk ke akun JOS untuk melihat rekomendasi pekerjaan yang cocok dengan profil Anda.')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-700 text-white font-extrabold text-2xl shadow-lg shadow-blue-600/30 mb-4">
            J
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Masuk ke Akun JOS
        </h2>
        <p class="mt-2 text-sm text-slate-600">
            Atau
            <a href="{{ route('register') }}" class="font-semibold text-blue-700 hover:text-blue-800 underline underline-offset-4">
                daftar akun baru pencari kerja gratis
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 sm:px-10 shadow-xl shadow-slate-200/50 sm:rounded-2xl border border-slate-200/80">
            <form class="space-y-5" action="{{ route('login') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">
                        Alamat Email
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl shadow-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm @error('email') border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">
                        Kata Sandi (Password)
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               placeholder="••••••••"
                               class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl shadow-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm @error('password') border-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                        <label for="remember" class="ml-2 block text-xs font-medium text-slate-700">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-all">
                        Masuk ke Akun
                    </button>
                </div>
            </form>

            <div class="mt-6 border-t border-slate-100 pt-5">
                <div class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-200/70">
                    <div class="font-semibold text-slate-700 mb-1">Akun Demo Coba Cepat:</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <span class="text-blue-700 font-semibold block">Admin:</span>
                            <span class="text-[11px] block">admin@jos.test</span>
                            <span class="text-[11px] text-slate-600">pass: password123</span>
                        </div>
                        <div>
                            <span class="text-emerald-700 font-semibold block">Pencari Kerja:</span>
                            <span class="text-[11px] block">user@jos.test</span>
                            <span class="text-[11px] text-slate-600">pass: password123</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
