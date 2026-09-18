@extends('layouts.admin')

@section('title', "Detail Log Scraping #{$log->id} — JOS Admin")
@section('page_title', "Detail Log Eksekusi #{$log->id}")

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
        <a href="{{ route('admin.scraping-logs.index') }}" class="text-blue-700 hover:underline flex items-center">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar Log
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6 text-xs">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Sumber Scraping</span>
                <h2 class="text-base font-bold text-slate-900">{{ $log->source ? $log->source->name : 'Sumber Tidak Diketahui' }}</h2>
            </div>
            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $log->status === 'success' ? 'bg-emerald-100 text-emerald-800' : ($log->status === 'running' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                {{ strtoupper($log->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100 text-center">
            <div>
                <span class="text-slate-500 text-[11px] block">Total Ditemukan</span>
                <span class="text-lg font-black text-slate-800 mt-0.5 block">{{ $log->jobs_found }}</span>
            </div>
            <div>
                <span class="text-slate-500 text-[11px] block">Lowongan Baru</span>
                <span class="text-lg font-black text-emerald-600 mt-0.5 block">+{{ $log->jobs_new }}</span>
            </div>
            <div>
                <span class="text-slate-500 text-[11px] block">Data Diperbarui</span>
                <span class="text-lg font-black text-blue-600 mt-0.5 block">{{ $log->jobs_updated }}</span>
            </div>
            <div>
                <span class="text-slate-500 text-[11px] block">Duplikasi Terdeteksi</span>
                <span class="text-lg font-black text-amber-600 mt-0.5 block">{{ $log->duplicates }}</span>
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <span class="font-bold text-slate-500 uppercase tracking-wider text-[11px] block">Waktu Mulai:</span>
                <span class="text-slate-800 font-semibold">{{ \Carbon\Carbon::parse($log->started_at)->isoFormat('dddd, D MMMM Y — HH:mm:ss') }}</span>
            </div>

            <div>
                <span class="font-bold text-slate-500 uppercase tracking-wider text-[11px] block">Waktu Selesai:</span>
                <span class="text-slate-800 font-semibold">{{ $log->finished_at ? \Carbon\Carbon::parse($log->finished_at)->isoFormat('dddd, D MMMM Y — HH:mm:ss') : 'Masih berjalan...' }}</span>
            </div>

            @if($log->error_message)
                <div>
                    <span class="font-bold text-red-600 uppercase tracking-wider text-[11px] block mb-1">Pesan Kesalahan (Error Trace):</span>
                    <div class="p-3 bg-red-50 rounded-xl border border-red-200 text-red-800 font-mono text-[11px] whitespace-pre-wrap">
                        {{ $log->error_message }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
