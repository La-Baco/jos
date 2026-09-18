@extends('layouts.admin')

@section('title', 'Manajemen Lowongan — JOS Admin')
@section('page_title', 'Kelola Data Lowongan Pekerjaan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Data Lowongan Terkumpul</h2>
            <p class="text-xs text-slate-500">Koreksi normalisasi kualifikasi, ubah status aktif/expired, dan tinjau sumber publik asli.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.jobs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 text-xs">
            <div>
                <label for="q" class="block font-bold text-slate-600 uppercase tracking-wider mb-1">Cari Posisi / Perusahaan</label>
                <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Ketik kata kunci..."
                       class="w-full py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label for="status" class="block font-bold text-slate-600 uppercase tracking-wider mb-1">Status</label>
                <select name="status" id="status" class="w-full py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div>
                <label for="source_id" class="block font-bold text-slate-600 uppercase tracking-wider mb-1">Sumber Scraping</label>
                <select name="source_id" id="source_id" class="w-full py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Sumber</option>
                    @foreach($sources as $src)
                        <option value="{{ $src->id }}" {{ request('source_id') == $src->id ? 'selected' : '' }}>{{ $src->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="category_id" class="block font-bold text-slate-600 uppercase tracking-wider mb-1">Kategori</label>
                <select name="category_id" id="category_id" class="w-full py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl shadow-xs transition-colors">
                    Saring Data
                </button>
            </div>
        </form>
    </div>

    <!-- Jobs Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Posisi / Perusahaan</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Lokasi</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Pendidikan</th>
                        <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-right font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-slate-900 max-w-xs truncate">{{ $job->title }}</div>
                                <span class="text-slate-500 font-semibold">{{ $job->company }}</span>
                                @if($job->source)
                                    <span class="text-[10px] text-blue-600 block mt-0.5">Sumber: {{ $job->source->name }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap">
                                <div>{{ $job->normalized_district ?: $job->location }}</div>
                                <span class="text-[10px] text-slate-400">{{ $job->normalized_regency ?: 'Sumenep' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                                {{ $job->category ? $job->category->name : 'Belum terkategori' }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                                {{ $job->educationLevel ? $job->educationLevel->name : 'Semua Jenjang' }}
                            </td>
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                @if($job->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @elseif($job->status === 'expired')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1 whitespace-nowrap">
                                <!-- Status Toggle Buttons -->
                                @if($job->status !== 'active')
                                    <form action="{{ route('admin.jobs.status', $job->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="px-2 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-lg hover:bg-emerald-100" title="Aktifkan">
                                            Aktifkan
                                        </button>
                                    </form>
                                @endif

                                @if($job->status !== 'expired')
                                    <form action="{{ route('admin.jobs.status', $job->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="expired">
                                        <button type="submit" class="px-2 py-1 bg-red-50 text-red-700 font-bold rounded-lg hover:bg-red-100" title="Tandai Expired">
                                            Expired
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.jobs.edit', $job->id) }}" class="px-2.5 py-1 bg-slate-100 text-slate-700 font-bold rounded-lg hover:bg-slate-200">
                                    Edit
                                </a>
                                <a href="{{ route('jobs.show', $job->id) }}" target="_blank" class="px-2.5 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg hover:bg-blue-100">
                                    Pratinjau
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                Tidak ada data lowongan yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@endsection
