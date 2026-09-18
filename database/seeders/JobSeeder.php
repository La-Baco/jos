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
        $kitalulus = ScrapingSource::where('name', 'like', '%KitaLulus%')->first();
        $pintarnya = ScrapingSource::where('name', 'like', '%Pintarnya%')->first();
        $glints = ScrapingSource::where('name', 'like', '%Glints%')->first();
        $jobstreet = ScrapingSource::where('name', 'like', '%JobStreet%')->first();

        $sma = EducationLevel::where('code', 'SMA/SMK')->first();
        $d3 = EducationLevel::where('code', 'D3')->first();
        $s1 = EducationLevel::where('code', 'S1')->first();

        $ritelCat = JobCategory::where('slug', 'ritel-perdagangan-toko')->first();
        $logistikCat = JobCategory::where('slug', 'logistik-kurir-operasional-field')->first();
        $adminCat = JobCategory::where('slug', 'staf-administrasi-keuangan')->first();
        $salesCat = JobCategory::where('slug', 'layanan-pelanggan-sales')->first();
        $teknikCat = JobCategory::where('slug', 'teknik-it-industri-lokal')->first();

        $excel = Skill::where('name', 'Microsoft Excel')->first();
        $word = Skill::where('name', 'Microsoft Word')->first();
        $office = Skill::where('name', 'Microsoft Office')->first();
        $dataEntry = Skill::where('name', 'Data Entry')->first();
        $php = Skill::where('name', 'PHP')->first();
        $laravel = Skill::where('name', 'Laravel')->first();
        $js = Skill::where('name', 'JavaScript')->first();
        $sql = Skill::where('name', 'MySQL')->first();
        $sales = Skill::where('name', 'Penjualan (Sales)')->first();
        $comm = Skill::where('name', 'Komunikasi & Negosiasi')->first();
        $cs = Skill::where('name', 'Customer Service')->first();
        $warehouse = Skill::where('name', 'Manajemen Gudang (Warehouse)')->first();
        $kasir = Skill::where('name', 'Operasional Kasir')->first();

        $demoJobs = [
            // 1. "Staf Toko & Kasir" (KitaLulus)
            [
                'title' => 'Staf Toko & Kasir',
                'company' => 'Toko Retail Sumenep',
                'location' => 'Kota Sumenep',
                'normalized_regency' => 'Sumenep',
                'normalized_district' => 'Kota Sumenep',
                'is_remote' => false,
                'source_id' => $kitalulus?->id,
                'original_url' => 'https://www.kitalulus.com/lowongan/in-kabupaten-sumenep/staf-toko',
                'category_id' => $ritelCat?->id,
                'education_id' => $sma?->id,
                'employment_type' => 'Full-time',
                'min_experience_years' => 0,
                'min_age' => 18,
                'max_age' => 25,
                'salary_min' => 1800000,
                'salary_max' => 2200000,
                'description' => 'Melayani pelanggan, melakukan transaksi pembayaran dengan mesin kasir, dan merapikan stok barang di toko.',
                'requirements' => "1. Pria / Wanita usia 18-25 tahun.\n2. Lulusan SMA/SMK sederajat.\n3. Teliti, jujur, dan komunikatif.",
                'posted_at' => Carbon::now()->subDays(2),
                'deadline' => Carbon::now()->addDays(14),
                'status' => 'active',
                'skills' => [$kasir?->id, $comm?->id],
            ],

            // 2. "Kurir Express Field" (Pintarnya)
            [
                'title' => 'Kurir Express Field',
                'company' => 'J&T / Ekspedisi Area Madura',
                'location' => 'Kalianget, Sumenep',
                'normalized_regency' => 'Sumenep',
                'normalized_district' => 'Kalianget',
                'is_remote' => false,
                'source_id' => $pintarnya?->id,
                'original_url' => 'https://pintarnya.com/l-kab-sumenep,jawa-timur-lowongan/kurir-express',
                'category_id' => $logistikCat?->id,
                'education_id' => $sma?->id,
                'employment_type' => 'Full-time',
                'min_experience_years' => 1,
                'min_age' => 20,
                'max_age' => 35,
                'salary_min' => 2000000,
                'salary_max' => 2500000,
                'description' => 'Mengirim dan menjemput paket di area Kalianget dan sekitarnya.',
                'requirements' => "1. Pria usia max 35 tahun.\n2. Memiliki SIM C dan kendaraan pribadi.\n3. Paham area Kalianget.",
                'posted_at' => Carbon::now()->subDays(1),
                'deadline' => Carbon::now()->addDays(20),
                'status' => 'active',
                'skills' => [],
            ],

            // 3. "Sales Executive Ritel" (Glints)
            [
                'title' => 'Sales Executive Ritel',
                'company' => 'Distributor Consumer Goods',
                'location' => 'Sumenep',
                'normalized_regency' => 'Sumenep',
                'normalized_district' => 'Kota Sumenep',
                'is_remote' => false,
                'source_id' => $glints?->id,
                'original_url' => 'https://glints.com/id/job-location/indonesia/jawa-timur/kab-sumenep/sales-ritel',
                'category_id' => $salesCat?->id,
                'education_id' => $sma?->id,
                'employment_type' => 'Kontrak',
                'min_experience_years' => 1,
                'min_age' => 21,
                'max_age' => 35,
                'salary_min' => 2500000,
                'salary_max' => 3500000,
                'description' => 'Memasarkan produk ke berbagai toko ritel di Kabupaten Sumenep.',
                'requirements' => "1. Pengalaman sales minimal 1 tahun.\n2. Memiliki SIM C aktif.\n3. Terbiasa dengan target.",
                'posted_at' => Carbon::now()->subDays(4),
                'deadline' => Carbon::now()->addDays(15),
                'status' => 'active',
                'skills' => [$sales?->id, $comm?->id],
            ],

            // 4. "Staf Administrasi & Pembukuan" (JobStreet)
            [
                'title' => 'Staf Administrasi & Pembukuan',
                'company' => 'CV Lokal Sumenep',
                'location' => 'Kota Sumenep',
                'normalized_regency' => 'Sumenep',
                'normalized_district' => 'Kota Sumenep',
                'is_remote' => false,
                'source_id' => $jobstreet?->id,
                'original_url' => 'https://id.jobstreet.com/id/sumenep-jobs/admin-pembukuan',
                'category_id' => $adminCat?->id,
                'education_id' => $d3?->id,
                'employment_type' => 'Full-time',
                'min_experience_years' => 1,
                'min_age' => 20,
                'max_age' => 30,
                'salary_min' => 2000000,
                'salary_max' => 2800000,
                'description' => 'Melakukan pencatatan administrasi harian, rekapitulasi data penjualan, dan pembukuan ringan.',
                'requirements' => "1. Pendidikan min. D3.\n2. Menguasai Microsoft Excel dan Word.\n3. Teliti dalam hal angka.",
                'posted_at' => Carbon::now()->subDays(2),
                'deadline' => Carbon::now()->addDays(30),
                'status' => 'active',
                'skills' => [$excel?->id, $word?->id, $dataEntry?->id],
            ],
        ];

        foreach ($demoJobs as $data) {
            $skills = $data['skills'];
            unset($data['skills']);

            $job = Job::updateOrCreate(
                ['original_url' => $data['original_url']],
                [
                    'scraping_source_id' => $data['source_id'],
                    'url_hash' => hash('sha256', $data['original_url']),
                    'content_hash' => hash('sha256', strtolower($data['title'].'|'.$data['company'].'|'.$data['location'])),
                    'title' => $data['title'],
                    'normalized_title' => $data['title'],
                    'company' => $data['company'],
                    'location' => $data['location'],
                    'normalized_regency' => $data['normalized_regency'],
                    'normalized_district' => $data['normalized_district'],
                    'is_remote' => $data['is_remote'],
                    'job_category_id' => $data['category_id'],
                    'employment_type' => $data['employment_type'],
                    'education_level_id' => $data['education_id'],
                    'min_experience_years' => $data['min_experience_years'],
                    'min_age' => $data['min_age'],
                    'max_age' => $data['max_age'],
                    'salary_min' => $data['salary_min'],
                    'salary_max' => $data['salary_max'],
                    'description' => $data['description'],
                    'requirements' => $data['requirements'],
                    'posted_at' => $data['posted_at'],
                    'deadline' => $data['deadline'],
                    'status' => $data['status'],
                    'scraped_at' => Carbon::now(),
                ]
            );

            $validSkills = array_filter($skills);
            if (! empty($validSkills)) {
                $syncData = [];
                foreach ($validSkills as $skId) {
                    $syncData[$skId] = ['is_required' => true];
                }
                $job->skills()->sync($syncData);
            }
        }
    }
}
