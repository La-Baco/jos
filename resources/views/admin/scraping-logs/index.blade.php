@extends('layouts.admin')

@section('title', 'Monitoring Scraping — JOS Admin')
@section('page_title', 'Monitoring Log Scraping')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-base font-bold text-slate-900">Riwayat & Log Eksekusi Scraping</h2>
        <p class="text-xs text-slate-500">Memonitor jumlah lowongan yang ditemukan, data baru yang disimpan, pembaruan, dan deteksi duplikasi.</p>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-5 py-3.5 text-left font-bold text-slate-600 uppercase tracking-wider">Sumber</th>
                        <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Ditemukan</th>
                        <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Baru</th>
                        <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Diperbarui</th>
                        <th class="px-5 py-3.5 text-center font-bold text-slate-600 uppercase tracking-wider">Duplikat</th>
                        <th class="px-5 py-3.5 text-right font-bold text-slate-600 uppercase tracking-wider">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($logs as $lg)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($lg->started_at)->isoFormat('D MMM Y, HH:mm:ss') }}</div>
                                <span class="text-[11px] text-slate-400">Durasi: {{ $lg->finished_at ? \Carbon\Carbon::parse($lg->started_at)->diffInSeconds($lg->finished_at) . ' detik' : '-' }}</span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $lg->source ? $lg->source->name : 'Sumber Umum / All' }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($lg->status === 'success')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        BERHASIL
                                    </span>
                                @elseif($lg->status === 'running')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-100 text-blue-800 animate-pulse">
                                        RUNNING
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-100 text-red-800">
                                        GAGAL
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center font-bold text-slate-800">{{ $lg->jobs_found }}</td>
                            <td class="px-5 py-4 text-center font-bold text-emerald-600">+{{ $lg->jobs_new }}</td>
                            <td class="px-5 py-4 text-center font-bold text-blue-600">{{ $lg->jobs_updated }}</td>
                            <td class="px-5 py-4 text-center text-slate-500">{{ $lg->duplicates }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.scraping-logs.show', $lg->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                                    Lihat Log
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-slate-400">
                                Belum ada log eksekusi scraping tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
