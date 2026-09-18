@extends('layouts.admin')

@section('title', "Koreksi Lowongan {$job->title} — JOS Admin")
@section('page_title', 'Koreksi Data Lowongan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
        <a href="{{ route('admin.jobs.index') }}" class="text-blue-700 hover:underline flex items-center">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar Lowongan
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-start justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Perbaiki & Koreksi Hasil Normalisasi Scraping</h2>
                <p class="text-xs text-slate-500">Sesuaikan kualifikasi, mapping kategori, pendidikan, atau wilayah kerja.</p>
            </div>
            <a href="{{ $job->original_url }}" target="_blank" rel="noopener noreferrer"
               class="text-xs font-bold text-blue-700 hover:underline flex items-center">
                Lihat Sumber Asli
                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        <form action="{{ route('admin.jobs.update', $job->id) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="title" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Lowongan <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $job->title) }}"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="company" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Perusahaan / Instansi <span class="text-red-500">*</span></label>
                    <input type="text" name="company" id="company" required value="{{ old('company', $job->company) }}"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="location" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lokasi Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="location" id="location" required value="{{ old('location', $job->location) }}"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="normalized_regency" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Normalisasi Kabupaten</label>
                    <input type="text" name="normalized_regency" id="normalized_regency" value="{{ old('normalized_regency', $job->normalized_regency) }}" placeholder="Sumenep / Pamekasan / Surabaya"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="normalized_district" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Normalisasi Kecamatan</label>
                    <input type="text" name="normalized_district" id="normalized_district" value="{{ old('normalized_district', $job->normalized_district) }}" placeholder="Kota Sumenep / Kalianget / Saronggi"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="job_category_id" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Pekerjaan</label>
                    <select name="job_category_id" id="job_category_id" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('job_category_id', $job->job_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="education_level_id" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tingkat Pendidikan Minimal</label>
                    <select name="education_level_id" id="education_level_id" class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Semua Jenjang --</option>
                        @foreach($educationLevels as $lvl)
                            <option value="{{ $lvl->id }}" {{ old('education_level_id', $job->education_level_id) == $lvl->id ? 'selected' : '' }}>
                                {{ $lvl->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="employment_type" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Pekerjaan</label>
                    <input type="text" name="employment_type" id="employment_type" value="{{ old('employment_type', $job->employment_type) }}" placeholder="Full-time / Part-time / Kontrak"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="min_experience_years" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Syarat Minimal Pengalaman (Tahun)</label>
                    <input type="number" name="min_experience_years" id="min_experience_years" min="0" max="30" value="{{ old('min_experience_years', $job->min_experience_years) }}"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="min_age" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Usia Min.</label>
                        <input type="number" name="min_age" id="min_age" min="17" max="65" value="{{ old('min_age', $job->min_age) }}" placeholder="18"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    </div>
                    <div>
                        <label for="max_age" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Usia Maks.</label>
                        <input type="number" name="max_age" id="max_age" min="18" max="70" value="{{ old('max_age', $job->max_age) }}" placeholder="30"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div>
                    <label for="salary_min" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gaji Minimal (Rp)</label>
                    <input type="number" name="salary_min" id="salary_min" value="{{ old('salary_min', $job->salary_min ? (int)$job->salary_min : '') }}" placeholder="2000000"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="salary_max" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gaji Maksimal (Rp)</label>
                    <input type="number" name="salary_max" id="salary_max" value="{{ old('salary_max', $job->salary_max ? (int)$job->salary_max : '') }}" placeholder="3000000"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="deadline" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Batas Waktu (Deadline)</label>
                    <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $job->deadline ? $job->deadline->format('Y-m-d') : '') }}"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="status" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Tayang Lowongan <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                        <option value="active" {{ old('status', $job->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="expired" {{ old('status', $job->status) === 'expired' ? 'selected' : '' }}>Expired (Kedaluwarsa)</option>
                        <option value="inactive" {{ old('status', $job->status) === 'inactive' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Keahlian (Skills) Terkait Lowongan Ini:</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-h-48 overflow-y-auto p-3 border border-slate-200 rounded-xl bg-slate-50">
                    @foreach($allSkills as $sk)
                        <label class="flex items-center text-slate-700">
                            <input type="checkbox" name="skills[]" value="{{ $sk->id }}"
                                   {{ in_array($sk->id, old('skills', $selectedSkillIds)) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <span class="ml-2 font-medium truncate">{{ $sk->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Pekerjaan</label>
                <textarea name="description" id="description" rows="5"
                          class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">{{ old('description', $job->description) }}</textarea>
            </div>

            <div>
                <label for="requirements" class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Persyaratan & Kualifikasi</label>
                <textarea name="requirements" id="requirements" rows="5"
                          class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">{{ old('requirements', $job->requirements) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_remote" value="1" {{ old('is_remote', $job->is_remote) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                    <span class="ml-2 font-bold text-slate-800">Pekerjaan Remote / Work From Home (WFH)</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.jobs.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
