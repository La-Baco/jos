@extends('layouts.app')

@section('title', 'Kelola Keahlian & Skill — JOS')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('user.profile.nav-tabs')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Form Tambah Skill -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sticky top-24">
                <div class="flex items-center space-x-2.5 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Tambah Skill</h2>
                </div>

                <form action="{{ route('user.profile.skills.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="skill_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pilih Master Skill <span class="text-red-500">*</span>
                        </label>
                        <select name="skill_id" id="skill_id" required
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            <option value="">-- Cari atau Pilih Skill --</option>
                            @foreach($categories as $cat)
                                <optgroup label="{{ $cat->name }}">
                                    @foreach($cat->skills as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">Daftar skill terstandarisasi berbasis kamus & alias JOS.</p>
                    </div>

                    <div>
                        <label for="proficiency_level" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tingkat Kemahiran <span class="text-red-500">*</span>
                        </label>
                        <select name="proficiency_level" id="proficiency_level" required
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            <option value="pemula">Pemula (Dasar / Mengetahui konsep)</option>
                            <option value="menengah" selected>Menengah (Mampu mengoperasikan mandiri)</option>
                            <option value="ahli">Ahli / Mahir (Sangat terampil & berpengalaman)</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 px-4 bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm rounded-xl shadow-sm transition-colors">
                        + Tambahkan ke Profil
                    </button>
                </form>

                <div class="mt-6 p-3.5 bg-slate-50 rounded-xl border border-slate-200/70 text-xs text-slate-600 space-y-1.5">
                    <div class="font-bold text-slate-800">Catatan Matching Skill:</div>
                    <p class="text-[11px] leading-relaxed">
                        Sistem mencocokkan keahlian Anda dengan persyaratan lowongan secara otomatis menggunakan <strong>kamus sinonim/alias</strong> (Bobot 30%). Contoh: jika lowongan mensyaratkan <em>"Excel"</em> dan Anda memilih <em>"Microsoft Excel"</em>, sistem mengenalinya sebagai kecocokan 100%.
                    </p>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Skill yang Sudah Ditambahkan -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Keahlian Tersimpan ({{ $userSkills->count() }})</h2>
                        <p class="text-xs text-slate-500">Semakin lengkap skill Anda, semakin tinggi akurasi rekomendasi lowongan pekerjaan.</p>
                    </div>
                </div>

                @if($userSkills->isEmpty())
                    <div class="text-center py-12 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Belum Ada Skill yang Ditambahkan</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-4">
                            Tambahkan keahlian seperti Microsoft Excel, Word, Administrasi, atau skill teknis lainnya melalui formulir di sebelah kiri.
                        </p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($userSkills as $skill)
                            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200/90 bg-white hover:border-slate-300 hover:shadow-xs transition-all">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($skill->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-sm font-bold text-slate-900">{{ $skill->name }}</h4>
                                            @if($skill->pivot->proficiency_level === 'ahli')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">Mahir / Ahli</span>
                                            @elseif($skill->pivot->proficiency_level === 'menengah')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">Menengah</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">Pemula</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $skill->category ? $skill->category->name : 'Umum' }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('user.profile.skills.destroy', $skill->id) }}" method="POST" onsubmit="return confirm('Hapus skill {{ $skill->name }} dari profil Anda?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Hapus skill">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
