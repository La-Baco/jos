@extends('layouts.admin')

@section('title', 'Kategori Pekerjaan — JOS Admin')
@section('page_title', 'Master Kategori Pekerjaan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Tambah Kategori -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs sticky top-24">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                + Tambah Kategori Baru
            </h3>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required placeholder="Contoh: Administrasi & Perkantoran"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
                    <textarea name="description" id="description" rows="3" placeholder="Penjelasan cakupan bidang pekerjaan..."
                              class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl shadow-xs transition-colors">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Kategori -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Daftar Kategori Pekerjaan</h3>
                    <p class="text-xs text-slate-500">Digunakan untuk klasifikasi lowongan publik dan preferensi pencari kerja.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Nama Kategori</th>
                            <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Slug</th>
                            <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Lowongan</th>
                            <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-right font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-900">{{ $cat->name }}</div>
                                    @if($cat->description)
                                        <p class="text-[11px] text-slate-500 truncate max-w-xs">{{ $cat->description }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-mono text-slate-500 text-[11px]">
                                    {{ $cat->slug }}
                                </td>
                                <td class="px-5 py-3.5 text-center font-bold text-blue-700">
                                    {{ $cat->jobs_count }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if($cat->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada kategori yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
