<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use App\Models\Experience;
use App\Models\JobCategory;
use App\Models\JobSeekerProfile;
use App\Models\Skill;
use App\Models\User;
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

        // 2. Primary Demo User: Budi Santoso (SMA, Sumenep, Skills: Excel, Word, Administrasi)
        $smaLevel = EducationLevel::where('code', 'SMA/SMK')->first();
        $s1Level = EducationLevel::where('code', 'S1')->first();
        $d3Level = EducationLevel::where('code', 'D3')->first();

        $adminCat = JobCategory::where('slug', 'staf-administrasi-keuangan')->first();
        $itCat = JobCategory::where('slug', 'teknik-it-industri-lokal')->first();
        $salesCat = JobCategory::where('slug', 'layanan-pelanggan-sales')->first();

        $user1 = User::updateOrCreate(
            ['email' => 'user@jos.test'],
            [
                'name' => 'Budi Santoso',
                'phone' => '085231998877',
                'role' => 'job_seeker',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );

        JobSeekerProfile::updateOrCreate(
            ['user_id' => $user1->id],
            [
                'phone' => '085231998877',
                'birth_date' => '2001-05-14', // 23-25 years old
                'gender' => 'L',
                'province' => 'Jawa Timur',
                'regency' => 'Sumenep',
                'district' => 'Kota Sumenep',
                'village' => 'Kolor',
                'education_level_id' => $smaLevel?->id,
                'major' => 'Administrasi Perkantoran',
                'graduation_year' => 2020,
                'willing_outside_sumenep' => true,
                'willing_outside_madura' => false,
                'willing_remote' => true,
                'expected_salary_min' => 2000000,
                'preferred_location' => 'Sumenep & Pamekasan',
                'bio' => 'Lulusan SMK jurusan Administrasi Perkantoran, teliti, rapi dalam input data dan menguasai Microsoft Office.',
            ]
        );

        // Skills for user1
        $excelSkill = Skill::where('name', 'Microsoft Excel')->first();
        $wordSkill = Skill::where('name', 'Microsoft Word')->first();
        $officeSkill = Skill::where('name', 'Microsoft Office')->first();
        $dataEntrySkill = Skill::where('name', 'Data Entry')->first();

        if ($excelSkill) {
            $user1->skills()->syncWithoutDetaching([$excelSkill->id => ['proficiency_level' => 'ahli']]);
        }
        if ($wordSkill) {
            $user1->skills()->syncWithoutDetaching([$wordSkill->id => ['proficiency_level' => 'ahli']]);
        }
        if ($officeSkill) {
            $user1->skills()->syncWithoutDetaching([$officeSkill->id => ['proficiency_level' => 'ahli']]);
        }
        if ($dataEntrySkill) {
            $user1->skills()->syncWithoutDetaching([$dataEntrySkill->id => ['proficiency_level' => 'menengah']]);
        }

        // Experience for user1 (1.5 years experience)
        Experience::updateOrCreate(
            ['user_id' => $user1->id, 'job_title' => 'Staf Tata Usaha'],
            [
                'company_name' => 'Koperasi Simpan Pinjam Sumekar',
                'start_year' => 2022,
                'end_year' => 2024,
                'is_current' => false,
                'description' => 'Menginput data transaksi harian, mengelola pengarsipan dokumen simpanan anggota dan surat menyurat.',
            ]
        );

        if ($adminCat) {
            $user1->preferredCategories()->syncWithoutDetaching([$adminCat->id]);
        }

        // 3. Demo User 2: Siti Aminah (S1 IT, Programmer, Kota Sumenep)
        $user2 = User::updateOrCreate(
            ['email' => 'siti@jos.test'],
            [
                'name' => 'Siti Aminah',
                'phone' => '087712345678',
                'role' => 'job_seeker',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );

        JobSeekerProfile::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'phone' => '087712345678',
                'birth_date' => '1999-11-20',
                'gender' => 'P',
                'province' => 'Jawa Timur',
                'regency' => 'Sumenep',
                'district' => 'Kalianget',
                'village' => 'Marengan Laok',
                'education_level_id' => $s1Level?->id,
                'major' => 'Teknik Informatika',
                'graduation_year' => 2022,
                'willing_outside_sumenep' => true,
                'willing_outside_madura' => true,
                'willing_remote' => true,
                'expected_salary_min' => 4500000,
                'preferred_location' => 'Sumenep, Surabaya, Remote',
                'bio' => 'Junior Web Developer menguasai PHP, Laravel, MySQL, dan pembuatan sistem informasi web.',
            ]
        );

        $phpSkill = Skill::where('name', 'PHP')->first();
        $laravelSkill = Skill::where('name', 'Laravel')->first();
        $jsSkill = Skill::where('name', 'JavaScript')->first();
        $sqlSkill = Skill::where('name', 'MySQL')->first();

        if ($phpSkill) {
            $user2->skills()->syncWithoutDetaching([$phpSkill->id => ['proficiency_level' => 'ahli']]);
        }
        if ($laravelSkill) {
            $user2->skills()->syncWithoutDetaching([$laravelSkill->id => ['proficiency_level' => 'menengah']]);
        }
        if ($jsSkill) {
            $user2->skills()->syncWithoutDetaching([$jsSkill->id => ['proficiency_level' => 'menengah']]);
        }
        if ($sqlSkill) {
            $user2->skills()->syncWithoutDetaching([$sqlSkill->id => ['proficiency_level' => 'menengah']]);
        }

        if ($itCat) {
            $user2->preferredCategories()->syncWithoutDetaching([$itCat->id]);
        }
    }
}
