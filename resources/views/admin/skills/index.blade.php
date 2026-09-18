@extends('layouts.admin')

@section('title', 'Master Skill & Alias — JOS Admin')
@section('page_title', 'Master Skill & Kamus Alias')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Tambah Skill & Alias -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs sticky top-24">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                + Tambah Master Skill Baru
            </h3>
            <form action="{{ route('admin.skills.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Standar Skill <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required placeholder="Contoh: Microsoft Excel"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="job_category_id" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Terkait</label>
                    <select name="job_category_id" id="job_category_id" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Umum / Tanpa Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="aliases" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Daftar Alias / Sinonim</label>
                    <textarea name="aliases" id="aliases" rows="3" placeholder="Pisahkan dengan koma: Excel, MS Excel, Spreadsheet"
                              class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600"></textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Digunakan untuk matching rule-based otomatis saat ekstraksi scraper.</p>
                </div>

                <div>
                    <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <input type="text" name="description" id="description" placeholder="Penjelasan kompetensi..."
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <button type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl shadow-xs transition-colors">
                    Simpan Master Skill
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Master Skill -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Kamus Master Skill & Alias</h3>
                <p class="text-xs text-slate-500">Kamus alias memastikan variasi penulisan skill publik terpetakan 100% tanpa bantuan AI.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Nama Standar</th>
                            <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Kamus Alias / Sinonim</th>
                            <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Kebutuhan Lowongan</th>
                            <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Pencari Kerja</th>
                            <th class="px-5 py-3.5 text-right font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($skills as $sk)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-900">{{ $sk->name }}</div>
                                    <span class="text-[11px] text-slate-400">{{ $sk->category ? $sk->category->name : 'Umum' }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap gap-1 max-w-sm">
                                        @foreach($sk->aliases as $al)
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-medium">
                                                {{ $al->alias }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-center font-bold text-blue-700">
                                    {{ $sk->jobs_count }}
                                </td>
                                <td class="px-5 py-3.5 text-center font-bold text-emerald-700">
                                    {{ $sk->users_count }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <form action="{{ route('admin.skills.destroy', $sk->id) }}" method="POST" onsubmit="return confirm('Hapus master skill {{ $sk->name }}?')">
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
                                    Belum ada data skill terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $skills->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
