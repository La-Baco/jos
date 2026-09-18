@extends('layouts.app')

@section('title', 'Profil & Pendidikan — JOS')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('user.profile.nav-tabs')

    <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Bagian 1: Data Diri -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Data Pribadi & Domisili</h2>
                    <p class="text-xs text-slate-500">Informasi dasar pencari kerja dan wilayah tempat tinggal Anda di Kabupaten Sumenep.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $user->name) }}"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                </div>

                <!-- Email (Readonly) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <input type="email" disabled
                           value="{{ $user->email }}"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed">
                    <span class="text-[11px] text-slate-400">Email akun digunakan untuk otentikasi login.</span>
                </div>

                <!-- Nomor HP / WA -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor HP / WhatsApp
                    </label>
                    <input type="tel" name="phone" id="phone"
                           value="{{ old('phone', $profile->phone ?? $user->phone) }}"
                           placeholder="Contoh: 081234567890"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="birth_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Lahir {{ $profile->age ? "({$profile->age} Tahun)" : '' }}
                    </label>
                    <input type="date" name="birth_date" id="birth_date"
                           value="{{ old('birth_date', $profile->birth_date ? $profile->birth_date->format('Y-m-d') : '') }}"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    <span class="text-[11px] text-slate-400">Digunakan untuk mencocokkan kriteria usia lowongan (Bobot 5%).</span>
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="gender" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenis Kelamin
                    </label>
                    <select name="gender" id="gender"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('gender', $profile->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $profile->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- Kabupaten -->
                <div>
                    <label for="regency" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kabupaten / Kota <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="regency" id="regency" required
                           value="{{ old('regency', $profile->regency ?? 'Sumenep') }}"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                </div>

                <!-- Kecamatan Sumenep -->
                <div>
                    <label for="district" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kecamatan di Sumenep
                    </label>
                    <select name="district" id="district"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($districts as $d)
                            <option value="{{ $d->name }}" {{ old('district', $profile->district) === $d->name ? 'selected' : '' }}>
                                {{ $d->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-[11px] text-slate-400">Kecamatan sama mendapat prioritas skor lokasi tertinggi (100%).</span>
                </div>

                <!-- Desa / Kelurahan -->
                <div>
                    <label for="village" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Desa / Kelurahan
                    </label>
                    <input type="text" name="village" id="village"
                           value="{{ old('village', $profile->village) }}"
                           placeholder="Contoh: Kolor, Pabian, Marengan"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                </div>
            </div>
        </div>

        <!-- Bagian 2: Pendidikan Terakhir -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Pendidikan Terakhir</h2>
                    <p class="text-xs text-slate-500">Digunakan untuk matching hirarki pendidikan (Bobot 20%). Jenjang lebih tinggi memenuhi syarat jenjang di bawahnya.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Jenjang Pendidikan -->
                <div>
                    <label for="education_level_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tingkat Pendidikan Terakhir
                    </label>
                    <select name="education_level_id" id="education_level_id"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <option value="">-- Pilih Tingkat Pendidikan --</option>
                        @foreach($educationLevels as $lvl)
                            <option value="{{ $lvl->id }}" {{ old('education_level_id', $profile->education_level_id) == $lvl->id ? 'selected' : '' }}>
                                {{ $lvl->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jurusan / Program Studi -->
                <div>
                    <label for="major" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jurusan / Peminatan
                    </label>
                    <input type="text" name="major" id="major"
                           value="{{ old('major', $profile->major) }}"
                           placeholder="Contoh: Administrasi Perkantoran / IPA / TI"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                </div>

                <!-- Tahun Lulus -->
                <div>
                    <label for="graduation_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tahun Kelulusan
                    </label>
                    <input type="number" name="graduation_year" id="graduation_year"
                           value="{{ old('graduation_year', $profile->graduation_year) }}"
                           placeholder="{{ date('Y') }}" min="1970" max="{{ date('Y') + 5 }}"
                           class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                </div>
            </div>
        </div>

        <!-- Bagian 3: Preferensi Pekerjaan & Minat -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Preferensi & Minat Pekerjaan</h2>
                    <p class="text-xs text-slate-500">Mempengaruhi matching minat (Bobot 10%) dan filter kesediaan lokasi kerja.</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Kategori Minat -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Pilih Kategori Pekerjaan yang Diminati (Bisa Lebih Dari Satu)
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($categories as $cat)
                            <label class="flex items-center p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                <input type="checkbox" name="preferred_categories[]" value="{{ $cat->id }}"
                                       {{ in_array($cat->id, old('preferred_categories', $selectedCategories)) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                <span class="ml-2.5 text-xs font-semibold text-slate-800">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Toggles Kesediaan Lokasi & Remote -->
                <div class="pt-4 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                        Kesediaan Mobilitas & Kerja Remote
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <label class="flex items-start p-3.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="willing_outside_sumenep" value="1"
                                   {{ old('willing_outside_sumenep', $profile->willing_outside_sumenep ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 mt-0.5 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <div class="ml-2.5">
                                <span class="block text-xs font-bold text-slate-800">Bersedia di Luar Sumenep</span>
                                <span class="block text-[11px] text-slate-500">Bersedia bekerja di Pamekasan, Sampang, Bangkalan.</span>
                            </div>
                        </label>

                        <label class="flex items-start p-3.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="willing_outside_madura" value="1"
                                   {{ old('willing_outside_madura', $profile->willing_outside_madura ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 mt-0.5 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <div class="ml-2.5">
                                <span class="block text-xs font-bold text-slate-800">Bersedia di Luar Madura</span>
                                <span class="block text-[11px] text-slate-500">Bersedia ditempatkan di Surabaya, Sidoarjo, Jatim.</span>
                            </div>
                        </label>

                        <label class="flex items-start p-3.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="willing_remote" value="1"
                                   {{ old('willing_remote', $profile->willing_remote ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 mt-0.5 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <div class="ml-2.5">
                                <span class="block text-xs font-bold text-slate-800">Bersedia Kerja Remote</span>
                                <span class="block text-[11px] text-slate-500">Bekerja dari rumah (WFH / Online).</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Gaji Minimum yang Diharapkan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    <div>
                        <label for="expected_salary_min" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Ekspektasi Gaji Minimum (Rp)
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="text-slate-500 text-xs font-bold">Rp</span>
                            </div>
                            <input type="number" name="expected_salary_min" id="expected_salary_min" step="50000"
                                   value="{{ old('expected_salary_min', $profile->expected_salary_min ? (int)$profile->expected_salary_min : '') }}"
                                   placeholder="Contoh: 2000000"
                                   class="w-full pl-10 pr-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        </div>
                        <span class="text-[11px] text-slate-400">Opsional. Untuk membantu filter lowongan sesuai harapan Anda.</span>
                    </div>

                    <div>
                        <label for="preferred_location" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Keterangan Lokasi Prioritas
                        </label>
                        <input type="text" name="preferred_location" id="preferred_location"
                               value="{{ old('preferred_location', $profile->preferred_location) }}"
                               placeholder="Contoh: Kota Sumenep & Kalianget"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    </div>
                </div>

                <!-- Bio Singkat -->
                <div>
                    <label for="bio" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Deskripsi Diri / Ringkasan Kualifikasi
                    </label>
                    <textarea name="bio" id="bio" rows="3"
                              placeholder="Ceritakan latar belakang, ketelitian, dan motivasi kerja Anda secara singkat..."
                              class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600">{{ old('bio', $profile->bio) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('user.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50">
                Kembali
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-blue-700 text-white font-bold text-sm hover:bg-blue-800 shadow-md shadow-blue-600/20 transition-all hover:shadow-lg">
                Simpan Profil & Lanjutkan
            </button>
        </div>
    </form>
</div>
@endsection
