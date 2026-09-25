@extends('layouts.app')

@section('title', 'Masuk ke Akun — JOS')
@section('meta_description', 'Masuk ke akun JOS untuk melihat rekomendasi pekerjaan yang cocok dengan profil Anda.')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <img src="{{ asset('assets/images/logo.png') }}" alt="JOS Logo" class="mx-auto h-14 w-auto object-contain mb-4">
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


        </div>
    </div>
</div>
@endsection
