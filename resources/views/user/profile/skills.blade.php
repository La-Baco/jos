@extends('layouts.app')

@section('title', 'Kelola Keahlian & Skill — JOS')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{
         searchQuery: '',
         selectedSkillId: '{{ old('skill_id', '') }}',
         selectedSkillName: '',
         level: '{{ old('proficiency_level', 'menengah') }}',
         selectSkill(id, name) {
             this.selectedSkillId = id;
             this.selectedSkillName = name;
         }
     }">
    @include('user.profile.nav-tabs')

    <!-- Banner Motivasi Skill (Bobot 30%) -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-2xl text-white p-5 mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-500/30 text-blue-200 border border-blue-400/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-white">Skill Memiliki Bobot Terbesar (30%) dalam Rekomendasi</h3>
                <p class="text-xs text-blue-200 mt-0.5">Sistem menggunakan kamus sinonim otomatis (contoh: 'Ms. Excel' = 'Excel'). Semakin lengkap skill Anda, semakin tinggi skor kecocokan Anda.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Form Tambah Skill -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6 sticky top-24">
                <div class="flex items-center space-x-2.5 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Tambah Keahlian Baru</h2>
                </div>

                <form action="{{ route('user.profile.skills.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="skill_id" :value="selectedSkillId">

                    <!-- Search / Select Skill -->
                    <div>
                        <label for="skill_select" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pilih Keahlian <span class="text-red-500">*</span>
                        </label>
                        <select id="skill_select"
                                x-model="selectedSkillId"
                                required
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-white">
                            <option value="">-- Pilih dari Daftar Keahlian --</option>
                            @foreach($categories as $cat)
                                <optgroup label="Bidang: {{ $cat->name }}">
                                    @foreach($cat->skills as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilihan Tingkat Kemahiran (Radio Tiles Ramah Pengguna) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tingkat Kemahiran <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2 text-xs">
                            <label class="cursor-pointer">
                                <input type="radio" name="proficiency_level" value="pemula" x-model="level" class="sr-only">
                                <div class="p-2.5 text-center rounded-xl border transition-all"
                                     :class="level === 'pemula' ? 'bg-blue-50 border-blue-600 text-blue-700 font-bold shadow-2xs' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                    <span class="block text-[11px] font-bold">Pemula</span>
                                    <span class="text-[9px] text-slate-400 block mt-0.5">Dasar</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="proficiency_level" value="menengah" x-model="level" class="sr-only">
                                <div class="p-2.5 text-center rounded-xl border transition-all"
                                     :class="level === 'menengah' ? 'bg-blue-50 border-blue-600 text-blue-700 font-bold shadow-2xs' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                    <span class="block text-[11px] font-bold">Menengah</span>
                                    <span class="text-[9px] text-slate-400 block mt-0.5">Mandiri</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="proficiency_level" value="ahli" x-model="level" class="sr-only">
                                <div class="p-2.5 text-center rounded-xl border transition-all"
                                     :class="level === 'ahli' ? 'bg-blue-50 border-blue-600 text-blue-700 font-bold shadow-2xs' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                    <span class="block text-[11px] font-bold">Mahir</span>
                                    <span class="text-[9px] text-slate-400 block mt-0.5">Ahli</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                            :disabled="!selectedSkillId"
                            class="w-full py-2.5 px-4 bg-blue-700 hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Simpan ke Profil
                    </button>
                </form>

                <!-- Rekomendasi Pintas Skill Populer di Sumenep -->
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2.5">
                        Pintasan Skill Populer:
                    </span>
                    <div class="flex flex-wrap gap-1.5">
                        @php
                            $userSkillIds = $userSkills->pluck('id')->toArray();
                            $popularList = collect();
                            foreach($categories as $cat) {
                                foreach($cat->skills as $s) {
                                    if(!in_array($s->id, $userSkillIds)) {
                                        $popularList->push($s);
                                    }
                                }
                            }
                        @endphp

                        @foreach($popularList->take(8) as $popSkill)
                            <button type="button"
                                    @click="selectSkill('{{ $popSkill->id }}', '{{ $popSkill->name }}')"
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 hover:bg-blue-100 hover:text-blue-800 text-slate-700 transition-colors"
                                    :class="selectedSkillId == '{{ $popSkill->id }}' ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white' : ''">
                                + {{ $popSkill->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Skill yang Sudah Ditambahkan -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-6 border-b border-slate-100 gap-2">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center">
                            Keahlian yang Anda Kuasai
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-black bg-blue-100 text-blue-800">
                                {{ $userSkills->count() }}
                            </span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Skill ini langsung digunakan oleh algoritma pencocokan lowongan.</p>
                    </div>

                    @if($userSkills->count() < 3)
                        <div class="text-[11px] text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl font-medium">
                            Tips: Tambahkan minimal 3 skill untuk akurasi rekomendasi terbaik.
                        </div>
                    @endif
                </div>

                @if($userSkills->isEmpty())
                    <div class="text-center py-12 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Belum Ada Keahlian yang Ditambahkan</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-6 leading-relaxed">
                            Pilih keahlian seperti Microsoft Excel, Administrasi, Layanan Pelanggan, atau skill lainnya melalui panel di sebelah kiri.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($userSkills as $skill)
                            <div class="p-4 rounded-xl border border-slate-200/90 bg-white hover:border-blue-300 hover:shadow-2xs transition-all flex items-center justify-between group">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($skill->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center space-x-1.5">
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $skill->name }}</h4>
                                        </div>
                                        <div class="flex items-center space-x-1.5 mt-1">
                                            @if($skill->pivot->proficiency_level === 'ahli')
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">Mahir / Ahli</span>
                                            @elseif($skill->pivot->proficiency_level === 'menengah')
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">Menengah</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">Pemula</span>
                                            @endif
                                            <span class="text-[10px] text-slate-400 truncate">{{ $skill->category ? $skill->category->name : 'Umum' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('user.profile.skills.destroy', $skill->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus keahlian {{ $skill->name }} dari profil Anda?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                                            title="Hapus skill">
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
