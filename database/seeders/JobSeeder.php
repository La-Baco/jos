<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\ScrapingSource;
use App\Models\Skill;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = ScrapingSource::all()->keyBy(fn ($s) => strtolower($s->name));
        $eduLevels = EducationLevel::all()->keyBy('code');
        $categories = JobCategory::all()->keyBy('slug');
        $skills = Skill::all()->keyBy(fn ($s) => strtolower($s->name));

        $jsonPath = __DIR__.'/data/jobs.json';
        $jobsList = [];

        if (file_exists($jsonPath)) {
            $jsonContent = file_get_contents($jsonPath);
            $decoded = json_decode($jsonContent, true);
            if (is_array($decoded) && ! empty($decoded)) {
                $jobsList = $decoded;
            }
        }

        // Fallback minimal demo dataset if jobs.json is missing
        if (empty($jobsList)) {
            $jobsList = [
                [
                    'source_name' => 'KitaLulus',
                    'title' => 'Staf Toko & Kasir',
                    'company' => 'Toko Retail Sumenep',
                    'location' => 'Kota Sumenep',
                    'normalized_regency' => 'Sumenep',
                    'normalized_district' => 'Kota Sumenep',
                    'is_remote' => false,
                    'category_slug' => 'ritel-perdagangan-toko',
                    'education_code' => 'SMA/SMK',
                    'employment_type' => 'Full-time',
                    'min_experience_years' => 0,
                    'min_age' => 18,
                    'max_age' => 25,
                    'salary_min' => 1800000,
                    'salary_max' => 2200000,
                    'description' => 'Melayani pelanggan, melakukan transaksi pembayaran dengan mesin kasir, dan merapikan stok barang di toko.',
                    'requirements' => "1. Pria / Wanita usia 18-25 tahun.\n2. Lulusan SMA/SMK sederajat.\n3. Teliti, jujur, dan komunikatif.",
                    'original_url' => 'https://kerja.kitalulus.com/id/lowongan-kerja',
                    'posted_days_ago' => 2,
                    'skills' => ['Operasional Kasir', 'Komunikasi & Negosiasi'],
                ],
                [
                    'source_name' => 'Pintarnya',
                    'title' => 'Kurir Express Field',
                    'company' => 'J&T / Ekspedisi Area Madura',
                    'location' => 'Kalianget, Sumenep',
                    'normalized_regency' => 'Sumenep',
                    'normalized_district' => 'Kalianget',
                    'is_remote' => false,
                    'category_slug' => 'logistik-kurir-operasional-field',
                    'education_code' => 'SMA/SMK',
                    'employment_type' => 'Full-time',
                    'min_experience_years' => 1,
                    'min_age' => 20,
                    'max_age' => 35,
                    'salary_min' => 2000000,
                    'salary_max' => 2500000,
                    'description' => 'Mengirim dan menjemput paket di area Kalianget dan sekitarnya.',
                    'requirements' => "1. Pria usia max 35 tahun.\n2. Memiliki SIM C dan kendaraan pribadi.\n3. Paham area Kalianget.",
                    'original_url' => 'https://pintarnya.com/lowongan-kerja',
                    'posted_days_ago' => 1,
                    'skills' => [],
                ],
                [
                    'source_name' => 'Glints',
                    'title' => 'Sales Executive Ritel',
                    'company' => 'Distributor Consumer Goods',
                    'location' => 'Sumenep',
                    'normalized_regency' => 'Sumenep',
                    'normalized_district' => 'Kota Sumenep',
                    'is_remote' => false,
                    'category_slug' => 'layanan-pelanggan-sales',
                    'education_code' => 'SMA/SMK',
                    'employment_type' => 'Kontrak',
                    'min_experience_years' => 1,
                    'min_age' => 21,
                    'max_age' => 35,
                    'salary_min' => 2500000,
                    'salary_max' => 3500000,
                    'description' => 'Memasarkan produk ke berbagai toko ritel di Kabupaten Sumenep.',
                    'requirements' => "1. Pengalaman sales minimal 1 tahun.\n2. Memiliki SIM C aktif.\n3. Terbiasa dengan target.",
                    'original_url' => 'https://glints.com/id/opportunities/jobs/explore?country=ID',
                    'posted_days_ago' => 4,
                    'skills' => ['Penjualan (Sales)', 'Komunikasi & Negosiasi'],
                ],
                [
                    'source_name' => 'JobStreet',
                    'title' => 'Staf Administrasi Umum & Pembukuan',
                    'company' => 'CV Lokal Sumenep',
                    'location' => 'Kota Sumenep',
                    'normalized_regency' => 'Sumenep',
                    'normalized_district' => 'Kota Sumenep',
                    'is_remote' => false,
                    'category_slug' => 'staf-administrasi-keuangan',
                    'education_code' => 'SMA/SMK',
                    'employment_type' => 'Full-time',
                    'min_experience_years' => 1,
                    'min_age' => 20,
                    'max_age' => 30,
                    'salary_min' => 2000000,
                    'salary_max' => 2800000,
                    'description' => 'Melakukan pencatatan administrasi harian, rekapitulasi data penjualan, dan pembukuan ringan.',
                    'requirements' => "1. Pendidikan min. SMA/SMK sederajat.\n2. Menguasai Microsoft Excel dan Word.\n3. Teliti dalam hal angka.",
                    'original_url' => 'https://www.jobstreet.co.id/id/job-search/sumenep-jobs/',
                    'posted_days_ago' => 2,
                    'skills' => ['Microsoft Excel', 'Microsoft Word', 'Data Entry', 'Microsoft Office'],
                ],
                [
                    'source_name' => 'Glints',
                    'title' => 'Web Developer / IT Support (Remote)',
                    'company' => 'Tech Studio Digital',
                    'location' => 'Remote / WFH',
                    'normalized_regency' => 'Nasional',
                    'normalized_district' => null,
                    'is_remote' => true,
                    'category_slug' => 'teknik-it-industri-lokal',
                    'education_code' => 'S1',
                    'employment_type' => 'Full-time',
                    'min_experience_years' => 1,
                    'min_age' => 20,
                    'max_age' => 35,
                    'salary_min' => 4500000,
                    'salary_max' => 6000000,
                    'description' => 'Mengembangkan aplikasi web modern berbasis Laravel dan integrasi basis data.',
                    'requirements' => "1. Pendidikan min. S1 Teknik Informatika / Sistem Informasi.\n2. Menguasai PHP, Laravel, MySQL, dan JavaScript.\n3. Mampu bekerja secara mandiri dan remote.",
                    'original_url' => 'https://glints.com/id/opportunities/jobs/remote',
                    'posted_days_ago' => 1,
                    'skills' => ['PHP', 'Laravel', 'MySQL', 'JavaScript'],
                ],
            ];
        }

        $defaultSource = ScrapingSource::first();
        $defaultCat = JobCategory::first();
        $defaultEdu = EducationLevel::first();

        foreach ($jobsList as $data) {
            $srcName = strtolower($data['source_name'] ?? '');
            $sourceId = null;
            foreach ($sources as $k => $s) {
                if (str_contains($k, $srcName) || str_contains($srcName, $k)) {
                    $sourceId = $s->id;
                    break;
                }
            }
            if (! $sourceId) {
                $sourceId = $defaultSource?->id;
            }

            $catId = $categories[$data['category_slug'] ?? '']?->id ?? $defaultCat?->id;
            $eduId = $eduLevels[$data['education_code'] ?? '']?->id ?? $defaultEdu?->id;

            $daysAgo = $data['posted_days_ago'] ?? 2;
            $postedAt = Carbon::now()->subDays($daysAgo);
            $deadline = Carbon::now()->addDays(20);

            $job = Job::updateOrCreate(
                ['original_url' => $data['original_url']],
                [
                    'scraping_source_id' => $sourceId,
                    'url_hash' => hash('sha256', $data['original_url']),
                    'content_hash' => hash('sha256', strtolower($data['title'].'|'.$data['company'].'|'.$data['location'])),
                    'title' => $data['title'],
                    'normalized_title' => $data['title'],
                    'company' => $data['company'],
                    'location' => $data['location'],
                    'normalized_regency' => $data['normalized_regency'] ?? 'Sumenep',
                    'normalized_district' => $data['normalized_district'] ?? null,
                    'is_remote' => (bool) ($data['is_remote'] ?? false),
                    'job_category_id' => $catId,
                    'employment_type' => $data['employment_type'] ?? 'Full-time',
                    'education_level_id' => $eduId,
                    'min_experience_years' => $data['min_experience_years'] ?? 0,
                    'min_age' => $data['min_age'] ?? null,
                    'max_age' => $data['max_age'] ?? null,
                    'salary_min' => $data['salary_min'] ?? null,
                    'salary_max' => $data['salary_max'] ?? null,
                    'description' => $data['description'] ?? '',
                    'requirements' => $data['requirements'] ?? '',
                    'posted_at' => $postedAt,
                    'deadline' => $deadline,
                    'status' => 'active',
                    'scraped_at' => Carbon::now(),
                ]
            );

            // Sync skills
            $skillSync = [];
            foreach (($data['skills'] ?? []) as $skName) {
                $skLower = strtolower(trim($skName));
                if (isset($skills[$skLower])) {
                    $skillSync[$skills[$skLower]->id] = ['is_required' => true];
                }
            }
            if (! empty($skillSync)) {
                $job->skills()->sync($skillSync);
            }
        }
    }
}
