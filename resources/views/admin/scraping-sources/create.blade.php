@extends('layouts.admin')

@section('title', 'Tambah Sumber Scraping — JOS Admin')
@section('page_title', 'Tambah Sumber Scraping Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <div class="mb-6 pb-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">Formulir Konfigurasi Sumber Scraping</h2>
            <p class="text-xs text-slate-500">Hanya gunakan URL publik yang tidak memblokir akses atau membutuhkan verifikasi captcha.</p>
        </div>

        <form action="{{ route('admin.scraping-sources.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div>
                <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Sumber <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Contoh: Portal Disnaker Sumenep"
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label for="base_url" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Base URL Website <span class="text-red-500">*</span></label>
                <input type="url" name="base_url" id="base_url" required value="{{ old('base_url') }}" placeholder="https://disnaker.sumenepkab.go.id"
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label for="list_url" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">URL Halaman Daftar Lowongan <span class="text-red-500">*</span></label>
                <input type="url" name="list_url" id="list_url" required value="{{ old('list_url') }}" placeholder="https://disnaker.sumenepkab.go.id/lowongan"
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="scraper_type" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Scraper</label>
                    <input type="text" name="scraper_type" id="scraper_type" required value="{{ old('scraper_type', 'python_modular') }}"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="interval_hours" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Interval Scraping (Jam)</label>
                    <input type="number" name="interval_hours" id="interval_hours" required value="{{ old('interval_hours', 24) }}" min="1" max="168"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <div>
                <label for="notes" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan / Keterangan Sumber</label>
                <textarea name="notes" id="notes" rows="3" placeholder="Informasi format halaman, kontak instansi, atau jadwal update..."
                          class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">{{ old('notes') }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                    <span class="ml-2 font-bold text-slate-800">Aktifkan Sumber Ini untuk Scraping Otomatis</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.scraping-sources.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold">
                    Simpan Sumber
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
