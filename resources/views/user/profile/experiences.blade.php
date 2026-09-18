@extends('layouts.app')

@section('title', 'Riwayat Pengalaman Kerja — JOS')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('user.profile.nav-tabs')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Form Tambah Pengalaman -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sticky top-24"
                 x-data="{ isCurrent: false }">
                <div class="flex items-center space-x-2.5 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Tambah Pengalaman</h2>
                </div>

                <form action="{{ route('user.profile.experiences.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="job_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Posisi / Jabatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="job_title" id="job_title" required
                               placeholder="Contoh: Staff Administrasi"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    </div>

                    <div>
                        <label for="company_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Perusahaan / Instansi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_name" id="company_name" required
                               placeholder="Contoh: Koperasi Simpan Pinjam / Toko ABC"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="start_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tahun Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="start_year" id="start_year" required
                                   min="1980" max="{{ date('Y') }}" placeholder="2022"
                                   class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        </div>

                        <div>
                            <label for="end_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5"
                                   :class="{ 'opacity-50': isCurrent }">
                                Tahun Selesai
                            </label>
                            <input type="number" name="end_year" id="end_year"
                                   :disabled="isCurrent" min="1980" max="{{ date('Y') + 1 }}" placeholder="2024"
                                   class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 disabled:bg-slate-100 disabled:cursor-not-allowed">
                        </div>
                    </div>

                    <div class="flex items-center pt-1">
                        <input type="checkbox" name="is_current" id="is_current" value="1"
                               x-model="isCurrent"
                               class="h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <label for="is_current" class="ml-2 text-xs font-medium text-slate-700">
                            Masih bekerja di posisi ini saat ini
                        </label>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Deskripsi Tanggung Jawab (Opsional)
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  placeholder="Jelaskan ringkasan tugas harian atau pencapaian Anda..."
                                  class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600"></textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 px-4 bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm rounded-xl shadow-sm transition-colors">
                        + Simpan Pengalaman
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Pengalaman Kerja -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Riwayat Pengalaman ({{ $experiences->count() }})</h2>
                        <p class="text-xs text-slate-500">Kalkulasi total durasi kerja digunakan untuk matching syarat pengalaman lowongan (Bobot 15%).</p>
                    </div>
                </div>

                @if($experiences->isEmpty())
                    <div class="text-center py-12 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Belum Ada Riwayat Pengalaman</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-4">
                            Jika Anda baru lulus (Fresh Graduate), sistem tetap dapat mencocokkan lowongan yang tidak mensyaratkan pengalaman.
                        </p>
                    </div>
                @else
                    <div class="relative border-l-2 border-slate-200 ml-4 space-y-6">
                        @foreach($experiences as $exp)
                            <div class="relative pl-6">
                                <!-- Marker Dot -->
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-600 border-4 border-white shadow-xs"></div>

                                <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 hover:border-slate-300 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900">{{ $exp->job_title }}</h4>
                                            <p class="text-xs font-semibold text-blue-700 mt-0.5">{{ $exp->company_name }}</p>
                                            <div class="flex items-center space-x-2 text-[11px] text-slate-500 mt-1">
                                                <span>{{ $exp->start_year }} — {{ $exp->is_current ? 'Sekarang' : $exp->end_year }}</span>
                                                <span>•</span>
                                                <span class="font-medium text-slate-600">± {{ round($exp->duration_years, 1) }} tahun</span>
                                            </div>
                                        </div>

                                        <form action="{{ route('user.profile.experiences.destroy', $exp->id) }}" method="POST" onsubmit="return confirm('Hapus pengalaman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>

                                    @if($exp->description)
                                        <p class="text-xs text-slate-600 mt-3 pt-2.5 border-t border-slate-200/60 leading-relaxed">
                                            {{ $exp->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
