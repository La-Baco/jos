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

class OfficialAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        // Master Data Cache
        $s1Level = EducationLevel::where('code', 'S1')->first();
        $skills = Skill::all()->keyBy(fn ($s) => strtolower($s->name));
        $categories = JobCategory::all()->keyBy('slug');

        // =========================================================================
        // 1. Akun Admin Resmi Synvora
        // =========================================================================
        $synvoraAdminEmails = [
            'synvora@gmail.com',
            'admin@synvora.com',
        ];

        foreach ($synvoraAdminEmails as $adminEmail) {
            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Administrator Synvora',
                    'phone' => '085231000001',
                    'role' => 'admin',
                    'status' => 'active',
                    'password' => $defaultPassword,
                ]
            );
        }

        // =========================================================================
        // 2. Akun Pencari Kerja: Danur Wenda (asal Kec. Raas, Sumenep)
        // =========================================================================
        $danurEmails = ['danurwenda@gmail.com', 'danur@synvora.com'];
        foreach ($danurEmails as $dEmail) {
            $danur = User::updateOrCreate(
                ['email' => $dEmail],
                [
                    'name' => 'Danur Wenda',
                    'phone' => '085231889901',
                    'role' => 'job_seeker',
                    'status' => 'active',
                    'password' => $defaultPassword,
                ]
            );

            JobSeekerProfile::updateOrCreate(
                ['user_id' => $danur->id],
                [
                    'phone' => '085231889901',
                    'birth_date' => '2000-08-17',
                    'gender' => 'L',
                    'province' => 'Jawa Timur',
                    'regency' => 'Sumenep',
                    'district' => 'Raas',
                    'village' => 'Ketupat',
                    'education_level_id' => $s1Level?->id,
                    'major' => 'Teknik Informatika',
                    'graduation_year' => 2023,
                    'willing_outside_sumenep' => true,
                    'willing_outside_madura' => true,
                    'willing_remote' => true,
                    'expected_salary_min' => 4500000,
                    'preferred_location' => 'Sumenep, Surabaya, Remote',
                    'bio' => 'Software Engineer & Full Stack Web Developer asal Kec. Raas, Kabupaten Sumenep. Berpengalaman dalam pengembangan aplikasi web modern berbasis Laravel, MySQL, JavaScript, dan integrasi API.',
                ]
            );

            // Skills Danur Wenda
            $danurSkills = [
                'php' => 'ahli',
                'laravel' => 'ahli',
                'mysql' => 'ahli',
                'javascript' => 'menengah',
                'it support' => 'menengah',
                'microsoft excel' => 'menengah',
            ];

            $syncSkills = [];
            foreach ($danurSkills as $skName => $level) {
                if (isset($skills[$skName])) {
                    $syncSkills[$skills[$skName]->id] = ['proficiency_level' => $level];
                }
            }
            if (! empty($syncSkills)) {
                $danur->skills()->sync($syncSkills);
            }

            // Category preference
            if (isset($categories['teknik-it-industri-lokal'])) {
                $danur->preferredCategories()->sync([$categories['teknik-it-industri-lokal']->id]);
            }

            // Experience
            Experience::updateOrCreate(
                ['user_id' => $danur->id, 'job_title' => 'Full Stack Web Developer'],
                [
                    'company_name' => 'Synvora Tech Solution',
                    'start_year' => 2022,
                    'end_year' => Carbon::now()->year,
                    'is_current' => true,
                    'description' => 'Mengembangkan sistem informasi web, arsitektur database, dan RESTful API untuk platform digital lokal dan instansi.',
                ]
            );
        }

        // =========================================================================
        // 3. Akun Pencari Kerja: Ilham Maulana (asal Kec. Bluto, Sumenep)
        // =========================================================================
        $ilhamEmails = ['ilhammaulana@gmail.com', 'ilham@synvora.com'];
        foreach ($ilhamEmails as $iEmail) {
            $ilham = User::updateOrCreate(
                ['email' => $iEmail],
                [
                    'name' => 'Ilham Maulana',
                    'phone' => '085231889902',
                    'role' => 'job_seeker',
                    'status' => 'active',
                    'password' => $defaultPassword,
                ]
            );

            JobSeekerProfile::updateOrCreate(
                ['user_id' => $ilham->id],
                [
                    'phone' => '085231889902',
                    'birth_date' => '2001-03-25',
                    'gender' => 'L',
                    'province' => 'Jawa Timur',
                    'regency' => 'Sumenep',
                    'district' => 'Bluto',
                    'village' => 'Bluto',
                    'education_level_id' => $s1Level?->id,
                    'major' => 'Sistem Informasi',
                    'graduation_year' => 2023,
                    'willing_outside_sumenep' => true,
                    'willing_outside_madura' => true,
                    'willing_remote' => true,
                    'expected_salary_min' => 3500000,
                    'preferred_location' => 'Sumenep, Madura Raya, Remote',
                    'bio' => 'Pencari kerja aktif domisili Kec. Bluto, Kabupaten Sumenep. Terampil dalam bidang IT support, administrasi sistem, pengolahan data, dan komunikasi profesional.',
                ]
            );

            // Skills Ilham Maulana
            $ilhamSkills = [
                'it support' => 'ahli',
                'microsoft office' => 'ahli',
                'microsoft excel' => 'menengah',
                'microsoft word' => 'menengah',
                'komunikasi & negosiasi' => 'menengah',
                'administrasi perkantoran' => 'menengah',
            ];

            $syncSkills = [];
            foreach ($ilhamSkills as $skName => $level) {
                if (isset($skills[$skName])) {
                    $syncSkills[$skills[$skName]->id] = ['proficiency_level' => $level];
                }
            }
            if (! empty($syncSkills)) {
                $ilham->skills()->sync($syncSkills);
            }

            // Category preference
            $prefCats = [];
            if (isset($categories['teknik-it-industri-lokal'])) {
                $prefCats[] = $categories['teknik-it-industri-lokal']->id;
            }
            if (isset($categories['staf-administrasi-keuangan'])) {
                $prefCats[] = $categories['staf-administrasi-keuangan']->id;
            }
            if (! empty($prefCats)) {
                $ilham->preferredCategories()->sync($prefCats);
            }

            // Experience
            Experience::updateOrCreate(
                ['user_id' => $ilham->id, 'job_title' => 'IT Support & Staf Operasional'],
                [
                    'company_name' => 'Mitra Usaha Bluto Sumenep',
                    'start_year' => 2022,
                    'end_year' => 2024,
                    'is_current' => false,
                    'description' => 'Bertanggung jawab atas pemeliharaan perangkat keras dan jaringan, rekapitulasi data operasional, dan pelayanan sistem.',
                ]
            );
        }
    }
}
