@extends('layouts.admin')

@section('title', "Detail Profil {$user->name} — JOS Admin")
@section('page_title', 'Detail Profil Pencari Kerja')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
        <a href="{{ route('admin.users.index') }}" class="text-blue-700 hover:underline flex items-center">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar Pengguna
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-xl">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">{{ $user->name }}</h2>
                <p class="text-xs text-slate-500">{{ $user->email }} • Terdaftar sejak {{ $user->created_at->isoFormat('D MMMM Y') }}</p>
                <div class="flex items-center space-x-2 mt-1.5">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        Akun {{ ucfirst($user->status) }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800">
                        Profil {{ $user->profileCompletionPercentage() }}% Lengkap
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold {{ $user->status === 'active' ? 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                {{ $user->status === 'active' ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
            </button>
        </form>
    </div>

    <!-- Rincian Profil -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-3.5">
            <h3 class="font-bold text-slate-900 pb-2 border-b border-slate-100 uppercase tracking-wider text-[11px]">
                Data Pribadi & Domisili
            </h3>
            <div>
                <span class="text-slate-400 font-semibold block text-[10px] uppercase">Nomor HP / WhatsApp</span>
                <span class="font-bold text-slate-800">{{ $user->phone ?: ($user->profile?->phone ?: '-') }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block text-[10px] uppercase">Usia & Tanggal Lahir</span>
                <span class="font-bold text-slate-800">
                    {{ $user->profile && $user->profile->birth_date ? $user->profile->birth_date->isoFormat('D MMMM Y') . ' (' . $user->profile->age . ' Tahun)' : 'Belum diisi' }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block text-[10px] uppercase">Jenis Kelamin</span>
                <span class="font-bold text-slate-800">{{ $user->profile?->gender === 'L' ? 'Laki-laki' : ($user->profile?->gender === 'P' ? 'Perempuan' : '-') }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block text-[10px] uppercase">Domisili</span>
                <span class="font-bold text-slate-800">
                    {{ $user->profile?->district ? 'Kec. ' . $user->profile->district : '' }}, {{ $user->profile?->regency ?: 'Sumenep' }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block text-[10px] uppercase">Pendidikan Terakhir</span>
                <span class="font-bold text-slate-800">
                    {{ $user->profile?->educationLevel ? $user->profile->educationLevel->name : 'Belum diisi' }}
                    {{ $user->profile?->major ? '— ' . $user->profile->major : '' }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-3.5">
            <h3 class="font-bold text-slate-900 pb-2 border-b border-slate-100 uppercase tracking-wider text-[11px]">
                Keahlian & Pengalaman
            </h3>
            <div>
                <span class="text-slate-400 font-semibold block text-[10px] uppercase mb-1">Daftar Keahlian ({{ $user->skills->count() }})</span>
                <div class="flex flex-wrap gap-1.5">
                    @forelse($user->skills as $sk)
                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 font-semibold text-[11px]">
                            {{ $sk->name }} ({{ ucfirst($sk->pivot->proficiency_level) }})
                        </span>
                    @empty
                        <span class="text-slate-400">Belum ada skill yang dipilih</span>
                    @endforelse
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <span class="text-slate-400 font-semibold block text-[10px] uppercase mb-1">Riwayat Pengalaman ({{ $user->experiences->count() }})</span>
                <div class="space-y-2">
                    @forelse($user->experiences as $exp)
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                            <span class="font-bold text-slate-800 block">{{ $exp->job_title }}</span>
                            <span class="text-[11px] text-slate-500">{{ $exp->company_name }} ({{ $exp->start_year }} - {{ $exp->is_current ? 'Sekarang' : $exp->end_year }})</span>
                        </div>
                    @empty
                        <span class="text-slate-400">Belum ada pengalaman kerja</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
