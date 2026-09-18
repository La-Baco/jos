@extends('layouts.admin')

@section('title', 'Kelola Pengguna — JOS Admin')
@section('page_title', 'Kelola Akun Pencari Kerja')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Daftar Pencari Kerja Terdaftar</h2>
            <p class="text-xs text-slate-500">Melihat status akun, jumlah keahlian, dan riwayat pendidikan masyarakat.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, nomor HP..."
                       class="w-full py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <select name="status" class="w-full py-2 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Status Akun</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl shadow-xs transition-colors">
                    Saring Pengguna
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Nama / Email</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Domisili</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Pendidikan</th>
                        <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Jumlah Skill</th>
                        <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Status Akun</th>
                        <th class="px-5 py-3.5 text-right font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-slate-900">{{ $u->name }}</div>
                                <span class="text-[11px] text-slate-500 block">{{ $u->email }}</span>
                                @if($u->phone)
                                    <span class="text-[10px] text-slate-400 block">WA: {{ $u->phone }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap">
                                <div>{{ $u->profile && $u->profile->district ? 'Kec. ' . $u->profile->district : '-' }}</div>
                                <span class="text-[10px] text-slate-400">{{ $u->profile ? $u->profile->regency : 'Sumenep' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap">
                                <div>{{ $u->profile && $u->profile->educationLevel ? $u->profile->educationLevel->name : 'Belum diisi' }}</div>
                                @if($u->profile && $u->profile->major)
                                    <span class="text-[10px] text-slate-400 block">{{ $u->profile->major }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800">
                                    {{ $u->skills->count() }} Skill
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                @if($u->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1 whitespace-nowrap">
                                <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Ubah status akun ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg font-bold {{ $u->status === 'active' ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                        {{ $u->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <a href="{{ route('admin.users.show', $u->id) }}" class="px-2.5 py-1 bg-slate-100 text-slate-700 font-bold rounded-lg hover:bg-slate-200">
                                    Lihat Profil
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                Tidak ada data pengguna yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
