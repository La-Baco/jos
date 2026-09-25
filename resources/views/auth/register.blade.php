@extends('layouts.app')

@section('title', 'Daftar Akun Pencari Kerja — JOS')
@section('meta_description', 'Daftar akun pencari kerja JOS gratis untuk mencocokkan kualifikasi Anda dengan ratusan lowongan di Sumenep.')

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <img src="{{ asset('assets/images/logo.png') }}" alt="JOS Logo" class="mx-auto h-14 w-auto object-contain mb-4">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Daftar Pencari Kerja
        </h2>
        <p class="mt-2 text-sm text-slate-600">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold text-blue-700 hover:text-blue-800 underline underline-offset-4">
                Masuk di sini
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 sm:px-10 shadow-xl shadow-slate-200/50 sm:rounded-2xl border border-slate-200/80">
            <form class="space-y-4" action="{{ route('register') }}" method="POST">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700">
                        Nama Lengkap
                    </label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" autocomplete="name" required
                               value="{{ old('name') }}"
                               placeholder="Contoh: Achmad Fauzi"
                               class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl shadow-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm @error('name') border-red-500 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700">
                        Nomor WhatsApp / HP (Opsional)
                    </label>
                    <div class="mt-1">
                        <input id="phone" name="phone" type="tel"
                               value="{{ old('phone') }}"
                               placeholder="081234567890"
                               class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl shadow-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm @error('phone') border-red-500 @enderror">
                    </div>
                    @error('phone')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">
                        Kata Sandi (Minimal 8 Karakter)
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               placeholder="••••••••"
                               class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl shadow-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm @error('password') border-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">
                        Konfirmasi Kata Sandi
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                               placeholder="••••••••"
                               class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl shadow-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-all">
                        Daftar & Lengkapi Profil
                    </button>
                </div>
            </form>

            <div class="mt-5 text-center">
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Dengan mendaftar, Anda menyetujui bahwa data kualifikasi profil Anda akan digunakan untuk menghitung skor rekomendasi lowongan pekerjaan secara otomatis & transparan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
