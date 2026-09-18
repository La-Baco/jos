@extends('layouts.admin')

@section('title', 'Statistik & Analisis Kesenjangan — JOS Admin')
@section('page_title', 'Statistik & Analisis Kesenjangan Pasar Kerja')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-base font-bold text-slate-900">Analisis Ketenagakerjaan Kabupaten Sumenep</h2>
        <p class="text-xs text-slate-500">Menganalisis perbandingan antara kualifikasi pencari kerja dengan kebutuhan nyata lowongan pekerjaan yang dihimpun sistem.</p>
    </div>

    <!-- PENTING: Analisis Kesenjangan Skill (Skills Gap Analysis) -->
    <div class="bg-white rounded-2xl border-2 border-blue-600 shadow-sm p-6 sm:p-8">
        <div class="flex items-center space-x-3 mb-4 pb-3 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-black">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">Perbandingan Skill Pencari Kerja vs Kebutuhan Lowongan</h3>
                <p class="text-xs text-slate-500">Menunjukkan kesenjangan (gap) antara kompetensi yang dimiliki pelamar dengan skill yang paling dicari perusahaan.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-slate-600 uppercase tracking-wider">Nama Keahlian (Skill)</th>
                        <th class="px-4 py-3 text-center font-bold text-slate-600 uppercase tracking-wider">Dibutuhkan Lowongan</th>
                        <th class="px-4 py-3 text-center font-bold text-slate-600 uppercase tracking-wider">Dikuasai Pencari Kerja</th>
                        <th class="px-4 py-3 text-center font-bold text-slate-600 uppercase tracking-wider">Kesenjangan (Gap)</th>
                        <th class="px-4 py-3 text-right font-bold text-slate-600 uppercase tracking-wider">Status Pasar Kerja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($skillsGapAnalysis as $gap)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $gap['name'] }}</td>
                            <td class="px-4 py-3 text-center font-bold text-blue-700">{{ $gap['demanded'] }} lowongan</td>
                            <td class="px-4 py-3 text-center font-bold text-emerald-700">{{ $gap['supplied'] }} pelamar</td>
                            <td class="px-4 py-3 text-center font-bold {{ $gap['gap'] > 0 ? 'text-red-600' : ($gap['gap'] < 0 ? 'text-amber-600' : 'text-slate-600') }}">
                                {{ $gap['gap'] > 0 ? '+' . $gap['gap'] : $gap['gap'] }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($gap['gap'] > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                        {{ $gap['status'] }}
                                    </span>
                                @elseif($gap['gap'] < 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        {{ $gap['status'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        {{ $gap['status'] }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2 Column Breakdown: Pendidikan & Lokasi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Perbandingan Jenjang Pendidikan: Lowongan vs Pencari Kerja -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                Pendidikan: Syarat Lowongan vs Latar Belakang Pelamar
            </h3>
            <div class="space-y-4 text-xs">
                @foreach($jobsByEducation as $index => $edu)
                    @php
                        $seekerCount = $seekersByEducation[$index]->seekers_count ?? 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-slate-800">{{ $edu->name }}</span>
                            <span class="text-slate-500 text-[11px]">
                                <strong class="text-blue-700">{{ $edu->jobs_count }} Lowongan</strong> vs <strong class="text-emerald-700">{{ $seekerCount }} Pelamar</strong>
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-slate-100 rounded-full h-2 overflow-hidden" title="Lowongan">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, $edu->jobs_count * 15) }}%"></div>
                            </div>
                            <div class="bg-slate-100 rounded-full h-2 overflow-hidden" title="Pelamar">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, $seekerCount * 25) }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sebaran Kategori Lowongan Terbanyak -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                Distribusi Lowongan per Kategori Pekerjaan
            </h3>
            <div class="space-y-3 text-xs">
                @foreach($jobsByCategory as $cat)
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-semibold text-slate-800">{{ $cat->name }}</span>
                        <span class="font-bold text-blue-700">{{ $cat->jobs_count }} Lowongan</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
