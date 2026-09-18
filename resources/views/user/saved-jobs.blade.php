@extends('layouts.app')

@section('title', 'Lowongan Tersimpan — JOS')

@section('content')
<div class="bg-slate-900 text-white py-8 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Lowongan Tersimpan</h1>
        <p class="text-sm text-slate-300 mt-1">Daftar bookmark lowongan pekerjaan yang Anda simpan untuk dipelajari atau dilamar kemudian.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($savedJobs->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Belum Ada Lowongan Tersimpan</h3>
            <p class="text-xs text-slate-500 mb-6">
                Klik tombol bookmark pada kartu lowongan untuk menyimpannya di halaman ini.
            </p>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-blue-700 text-white font-bold text-xs hover:bg-blue-800">
                Jelajahi Lowongan
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($savedJobs as $job)
                <x-job-card :job="$job" :score="$jobScores[$job->id] ?? null" :isSaved="true" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $savedJobs->links() }}
        </div>
    @endif
</div>
@endsection
