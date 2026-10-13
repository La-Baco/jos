<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use App\Models\Experience;
use App\Models\JobCategory;
use App\Models\JobSeekerProfile;
use App\Models\Skill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@jos.test'],
            [
                'name' => 'Administrator JOS',
                'phone' => '081234567890',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );

        $defaultPassword = Hash::make('password123');

        // Master Data Caching for fast generation
        $smaLevel = EducationLevel::where('code', 'SMA/SMK')->first();
        $s1Level = EducationLevel::where('code', 'S1')->first();
        $d3Level = EducationLevel::where('code', 'D3')->first();
        $smpLevel = EducationLevel::where('code', 'SMP')->first();

        $eduMap = [
            'SMA' => $smaLevel?->id,
            'S1' => $s1Level?->id,
            'D3' => $d3Level?->id,
            'SMP' => $smpLevel?->id,
        ];

        $categories = JobCategory::pluck('id', 'slug')->toArray();
        $skills = Skill::pluck('id', 'name')->toArray();

        // 27 Kecamatan di Kabupaten Sumenep
        $districts = [
            'Kota Sumenep' => ['Kolor', 'Pabian', 'Bangselok', 'Pangarangan', 'Karangduak', 'Kepanjin', 'Pamolokan'],
            'Batuan' => ['Batuan', 'Torbang', 'Geloran', 'Babat', 'Patian'],
            'Kalianget' => ['Kalianget Timur', 'Kalianget Barat', 'Marengan Laok', 'Kalimook', 'Kertasada'],
            'Talango' => ['Talango', 'Gadir', 'Palasa', 'Padike', 'Essang'],
            'Saronggi' => ['Saronggi', 'Nambakor', 'Tanah Merah', 'Juluk', 'Langsar'],
            'Bluto' => ['Bluto', 'Aengdake', 'Lobuk', 'Sera Tengah', 'Gulukmanjung'],
            'Pragaan' => ['Pragaan Laok', 'Pragaan Daya', 'Prenduan', 'Karduluk', 'Pakamban Laok'],
            'Lenteng' => ['Lenteng Timur', 'Lenteng Barat', 'Ellak Laok', 'Jambu', 'Moncek Tengah'],
            'Ganding' => ['Ganding', 'Bataal Barat', 'Ketawang Larangan', 'Rombiya Barat', 'Gadu Timur'],
            'Guluk-Guluk' => ['Guluk-Guluk', 'Bakeong', 'Payudan Dundang', 'Tambuko', 'Bragung'],
            'Rubaru' => ['Rubaru', 'Banbaru', 'Matanair', 'Pakondang', 'Basoka'],
            'Ambunten' => ['Ambunten Timur', 'Ambunten Tengah', 'Tambakagung', 'Belluk Ares', 'Sogian'],
            'Pasongsongan' => ['Pasongsongan', 'Panaongan', 'Montorna', 'Padangdangan', 'Soddara'],
            'Dasuk' => ['Dasuk Laok', 'Dasuk Barat', 'Kerta Barat', 'Bates', 'Nyapar'],
            'Manding' => ['Manding Laok', 'Manding Daya', 'Jabaan', 'Giring', 'Lanjuk'],
            'Batuputih' => ['Batuputih Laok', 'Batuputih Daya', 'Sergang', 'Larangan Barma', 'Juruan Daya'],
            'Dungkek' => ['Dungkek', 'Lapa Laok', 'Bancamara', 'Candi', 'Romben Guna'],
            'Gapura' => ['Gapura Barat', 'Gapura Timur', 'Braji', 'Poja', 'Banjerang'],
            'Batang-Batang' => ['Batang-Batang Daya', 'Legung Timur', 'Lombang', 'Dapenda', 'Jenangger'],
            'Giligenting' => ['Aeng Anyar', 'Gedugan', 'Banmaling', 'Galas'],
            'Gayam' => ['Gayam', 'Tarebung', 'Pancor', 'Kalowang', 'Gendang Barat'],
            'Nonggunong' => ['Nonggunong', 'Sokarame Pesisir', 'Talaga', 'Tanah Merah'],
            'Raas' => ['Brakas', 'Jungkat', 'Tonduk', 'Alasmalang', 'Ketupat'],
            'Arjasa' => ['Arjasa', 'Kalikatak', 'Pabian', 'Kolo-Kolo', 'Angkatan'],
            'Kangayan' => ['Kangayan', 'Torjek', 'Daandung', 'Timur Jangjang', 'Batuplat'],
            'Sapeken' => ['Sapeken', 'Paliat', 'Sabuntan', 'Sadulang', 'Sakala'],
            'Masalembu' => ['Suka Jeruk', 'Masalima', 'Karamian'],
        ];

        // Template personas for variety and realism
        $maleNames = ['Moh. Farhan', 'Achmad Zaini', 'Faisol Razi', 'Ainur Rofiq', 'Taufik Hidayat', 'Zainal Abidin', 'Hasan Basri', 'Holilur Rahman', 'Dimas Pratama', 'Hendra Wahyudi', 'Wildan Hamdani', 'Rian Kurniawan', 'Fauzi Ridwan', 'Ilham Syafi\'i', 'Dani Saputra', 'Irfan Hakim', 'Badri Munir', 'Rudi Hartono', 'Khoirul Anam', 'Miftahul Huda', 'Zainur Ridho', 'Syamsul Arifin', 'Abdul Hadi', 'Bambang Irawan', 'Alif Ramadhan'];
        $femaleNames = ['Nurul Hidayah', 'Siti Maryam', 'Dewi Lestari', 'Fitri Handayani', 'Anisa Rahmawati', 'Rina Agustina', 'Indah Permatasari', 'Maulida Zahra', 'Ayu Wandira', 'Fatimah Az-Zahra', 'Lailatul Qadariyah', 'Diana Safitri', 'Wardah Maulidya', 'Nabilah Husna', 'Qori\'atul Hasanah', 'Putri Mayangsari', 'Yulia Citra', 'Mega Wulandari', 'Alfiatur Rohmah', 'Siti Aisyah'];

        $majorsByEdu = [
            'SMA' => [
                'Administrasi Perkantoran' => ['skills' => ['Microsoft Excel', 'Microsoft Word', 'Microsoft Office', 'Data Entry'], 'cat' => 'staf-administrasi-keuangan'],
                'Teknik Komputer & Jaringan' => ['skills' => ['IT Support', 'Microsoft Office', 'Komunikasi & Negosiasi'], 'cat' => 'teknik-it-industri-lokal'],
                'Pemasaran / Bisnis Daring' => ['skills' => ['Digital Marketing', 'Penjualan (Sales)', 'Customer Service', 'Komunikasi & Negosiasi'], 'cat' => 'layanan-pelanggan-sales'],
                'Akuntansi SMK' => ['skills' => ['Akuntansi (Accounting)', 'Microsoft Excel', 'Operasional Kasir', 'Data Entry'], 'cat' => 'staf-administrasi-keuangan'],
                'Teknik Sepeda Motor' => ['skills' => ['IT Support', 'Mengemudi (Driver)', 'Komunikasi & Negosiasi'], 'cat' => 'teknik-it-industri-lokal'],
                'Tata Niaga & Kasir' => ['skills' => ['Operasional Kasir', 'Pramuniaga (Shop Assistant)', 'Customer Service'], 'cat' => 'ritel-perdagangan-toko'],
                'SMA IPS Umum' => ['skills' => ['Microsoft Word', 'Customer Service', 'Komunikasi & Negosiasi'], 'cat' => 'ritel-perdagangan-toko'],
            ],
            'D3' => [
                'Manajemen Informatika' => ['skills' => ['PHP', 'MySQL', 'IT Support', 'Microsoft Excel'], 'cat' => 'teknik-it-industri-lokal'],
                'Akuntansi Perbankan' => ['skills' => ['Akuntansi (Accounting)', 'Microsoft Excel', 'Customer Service'], 'cat' => 'staf-administrasi-keuangan'],
                'Manajemen Pemasaran' => ['skills' => ['Penjualan (Sales)', 'Komunikasi & Negosiasi', 'Digital Marketing'], 'cat' => 'layanan-pelanggan-sales'],
            ],
            'S1' => [
                'Teknik Informatika' => ['skills' => ['PHP', 'Laravel', 'JavaScript', 'MySQL'], 'cat' => 'teknik-it-industri-lokal'],
                'Sistem Informasi' => ['skills' => ['IT Support', 'Microsoft Excel', 'Desain Grafis', 'MySQL'], 'cat' => 'teknik-it-industri-lokal'],
                'Akuntansi / Manajemen Keuangan' => ['skills' => ['Akuntansi (Accounting)', 'Microsoft Excel', 'Pengarsipan Dokumen'], 'cat' => 'staf-administrasi-keuangan'],
                'Ilmu Komunikasi' => ['skills' => ['Komunikasi & Negosiasi', 'Customer Service', 'Digital Marketing'], 'cat' => 'layanan-pelanggan-sales'],
                'Manajemen Bisnis' => ['skills' => ['Penjualan (Sales)', 'Komunikasi & Negosiasi', 'Microsoft Office'], 'cat' => 'layanan-pelanggan-sales'],
            ],
            'SMP' => [
                'SMP Sederajat' => ['skills' => ['Mengemudi (Driver)', 'Manajemen Gudang (Warehouse)', 'Pramuniaga (Shop Assistant)'], 'cat' => 'logistik-kurir-operasional-field'],
            ],
        ];

        $targetTotal = 216;
        $usersPerDistrict = 8; // 27 * 8 = 216
        $counter = 1;
        $seededUserIds = [];

        foreach ($districts as $districtName => $villages) {
            for ($k = 1; $k <= $usersPerDistrict; $k++) {
                $userIdx = $counter;
                $customSkills = null;
                $customCat = null;
                $customExp = null;

                // Special primary demo accounts
                if ($districtName === 'Kota Sumenep' && $k === 1) {
                    $email = 'user@jos.test';
                    $name = 'Budi Santoso';
                    $phone = '085231998877';
                    $gender = 'L';
                    $birthDate = '2001-05-14';
                    $eduCode = 'SMA';
                    $majorName = 'Administrasi Perkantoran';
                    $village = 'Kolor';
                    $gradYear = 2020;
                    $salaryMin = 2000000;
                    $prefLocation = 'Sumenep & Pamekasan';
                    $bio = 'Lulusan SMK jurusan Administrasi Perkantoran, teliti, rapi dalam input data dan menguasai Microsoft Office.';
                    $customSkills = [
                        'Microsoft Excel' => 'ahli',
                        'Microsoft Word' => 'ahli',
                        'Microsoft Office' => 'ahli',
                        'Data Entry' => 'menengah',
                    ];
                    $customCat = 'staf-administrasi-keuangan';
                    $customExp = [
                        'job_title' => 'Staf Tata Usaha',
                        'company_name' => 'Koperasi Simpan Pinjam Sumekar',
                        'start_year' => 2022,
                        'end_year' => 2024,
                        'is_current' => false,
                        'description' => 'Menginput data transaksi harian, mengelola pengarsipan dokumen simpanan anggota dan surat menyurat.',
                    ];
                } elseif ($districtName === 'Kalianget' && $k === 1) {
                    $email = 'siti@jos.test';
                    $name = 'Siti Aminah';
                    $phone = '087712345678';
                    $gender = 'P';
                    $birthDate = '1999-11-20';
                    $eduCode = 'S1';
                    $majorName = 'Teknik Informatika';
                    $village = 'Marengan Laok';
                    $gradYear = 2022;
                    $salaryMin = 4500000;
                    $prefLocation = 'Sumenep, Surabaya, Remote';
                    $bio = 'Junior Web Developer menguasai PHP, Laravel, MySQL, dan pembuatan sistem informasi web.';
                    $customSkills = [
                        'PHP' => 'ahli',
                        'Laravel' => 'menengah',
                        'JavaScript' => 'menengah',
                        'MySQL' => 'menengah',
                    ];
                    $customCat = 'teknik-it-industri-lokal';
                    $customExp = [
                        'job_title' => 'Junior Web Developer',
                        'company_name' => 'CV Media Madura Digital',
                        'start_year' => 2023,
                        'end_year' => 2024,
                        'is_current' => false,
                        'description' => 'Mengembangkan aplikasi website berbasis Laravel dan mengelola basis data MySQL.',
                    ];
                } else {
                    $gender = ($k % 2 === 1) ? 'L' : 'P';
                    $namePool = ($gender === 'L') ? $maleNames : $femaleNames;
                    $name = $namePool[($userIdx + $k) % count($namePool)].' '.($gender === 'L' ? 'Madura' : 'Sumenep');
                    $email = 'pencari'.$userIdx.'@jos.test';

                    // Pick education level realistically
                    if ($k === 2 || $k === 6) {
                        $eduCode = 'S1';
                    } elseif ($k === 4) {
                        $eduCode = 'D3';
                    } elseif ($k === 8) {
                        $eduCode = 'SMP';
                    } else {
                        $eduCode = 'SMA';
                    }

                    $majorKeys = array_keys($majorsByEdu[$eduCode]);
                    $majorName = $majorKeys[$userIdx % count($majorKeys)];
                    $village = $villages[($userIdx) % count($villages)];

                    $birthYear = 1996 + ($userIdx % 9);
                    $birthMonth = str_pad(($userIdx % 12) + 1, 2, '0', STR_PAD_LEFT);
                    $birthDay = str_pad(($userIdx % 28) + 1, 2, '0', STR_PAD_LEFT);
                    $birthDate = "{$birthYear}-{$birthMonth}-{$birthDay}";

                    $phone = '0852'.str_pad($userIdx, 4, '0', STR_PAD_LEFT).str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                    $salaryMin = ($eduCode === 'S1') ? 3500000 : (($eduCode === 'D3') ? 2500000 : 2000000);
                    $gradYear = $birthYear + ($eduCode === 'S1' ? 22 : ($eduCode === 'D3' ? 21 : 18));
                    $prefLocation = "Kabupaten Sumenep (Kec. {$districtName})";
                    $bio = "Pencari kerja aktif domisili Kec. {$districtName}, Sumenep. Lulusan {$eduCode} jurusan {$majorName}. Siap bekerja profesional, disiplin, dan berkontribusi untuk perusahaan.";
                }

                $majorData = $majorsByEdu[$eduCode][$majorName];

                // Create or Update User
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'phone' => $phone,
                        'role' => 'job_seeker',
                        'status' => 'active',
                        'password' => $defaultPassword,
                    ]
                );

                $seededUserIds[] = $user->id;

                // Create or Update Profile
                JobSeekerProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'phone' => $phone,
                        'birth_date' => $birthDate,
                        'gender' => $gender,
                        'province' => 'Jawa Timur',
                        'regency' => 'Sumenep',
                        'district' => $districtName,
                        'village' => $village,
                        'education_level_id' => $eduMap[$eduCode] ?? null,
                        'major' => $majorName,
                        'graduation_year' => $gradYear,
                        'willing_outside_sumenep' => ($userIdx % 3 !== 0),
                        'willing_outside_madura' => ($userIdx % 5 === 0),
                        'willing_remote' => true,
                        'expected_salary_min' => $salaryMin,
                        'preferred_location' => $prefLocation,
                        'bio' => $bio,
                    ]
                );

                // Sync Skills
                $skillSync = [];
                if ($customSkills !== null) {
                    foreach ($customSkills as $skName => $prof) {
                        if (isset($skills[$skName])) {
                            $skillSync[$skills[$skName]] = ['proficiency_level' => $prof];
                        }
                    }
                } else {
                    $proficiencyLevels = ['ahli', 'menengah', 'menengah', 'pemula'];
                    foreach ($majorData['skills'] as $sIndex => $skName) {
                        if (isset($skills[$skName])) {
                            $prof = $proficiencyLevels[$sIndex % count($proficiencyLevels)];
                            $skillSync[$skills[$skName]] = ['proficiency_level' => $prof];
                        }
                    }
                }
                if (! empty($skillSync)) {
                    $user->skills()->sync($skillSync);
                }

                // Sync Category Preference
                $catSlug = $customCat ?? $majorData['cat'];
                if (isset($categories[$catSlug])) {
                    $user->preferredCategories()->sync([$categories[$catSlug]]);
                }

                // Sync Experience
                if ($customExp !== null) {
                    Experience::updateOrCreate(
                        ['user_id' => $user->id, 'job_title' => $customExp['job_title']],
                        [
                            'company_name' => $customExp['company_name'],
                            'start_year' => $customExp['start_year'],
                            'end_year' => $customExp['end_year'],
                            'is_current' => $customExp['is_current'],
                            'description' => $customExp['description'],
                        ]
                    );
                } elseif ($userIdx % 2 === 0) {
                    $expTitles = [
                        'Administrasi Perkantoran' => 'Staf Administrasi & Kas',
                        'Akuntansi SMK' => 'Kasir & Pembukuan',
                        'Pemasaran / Bisnis Daring' => 'Sales Counter & Promosi',
                        'Teknik Komputer & Jaringan' => 'Teknisi Komputer & Jaringan',
                        'Teknik Sepeda Motor' => 'Mekanik Sepeda Motor',
                        'Tata Niaga & Kasir' => 'Pramuniaga Toko',
                        'Teknik Informatika' => 'Junior Web Developer',
                        'Akuntansi / Manajemen Keuangan' => 'Staf Keuangan',
                        'SMP Sederajat' => 'Kurir & Checker Gudang',
                    ];
                    $expTitle = $expTitles[$majorName] ?? 'Staf Operasional';

                    Experience::updateOrCreate(
                        ['user_id' => $user->id, 'job_title' => $expTitle],
                        [
                            'company_name' => "Mitra Usaha Lokal Kec. {$districtName}",
                            'start_year' => Carbon::now()->year - 2,
                            'end_year' => Carbon::now()->year - 1,
                            'is_current' => false,
                            'description' => "Bertanggung jawab atas pekerjaan {$expTitle} dengan teliti dan tepat waktu.",
                        ]
                    );
                }

                $counter++;
            }
        }

        // Clean up any stray job_seeker users not in the 216 list
        User::where('role', 'job_seeker')->whereNotIn('id', $seededUserIds)->delete();
    }
}
