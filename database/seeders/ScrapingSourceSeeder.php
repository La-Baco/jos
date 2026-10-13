<?php

namespace Database\Seeders;

use App\Models\ScrapingSource;
use Illuminate\Database\Seeder;

class ScrapingSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'name' => 'Disnaker Kabupaten Sumenep',
                'base_url' => 'https://sumenepkab.go.id',
                'list_url' => 'https://sumenepkab.go.id/berita',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 6,
                'notes' => 'Kanal Resmi Pemerintah Kabupaten Sumenep (Pusat Informasi & Pengumuman Daerah).',
            ],
            [
                'name' => 'Loker Madura Raya',
                'base_url' => 'https://disnakertrans.jatimprov.go.id',
                'list_url' => 'https://disnakertrans.jatimprov.go.id',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 12,
                'notes' => 'Portal publik bursa kerja ketenagakerjaan Madura Raya (Sumenep, Pamekasan, Sampang, Bangkalan).',
            ],
            [
                'name' => 'Bursa Kerja Disnakertrans Jatim',
                'base_url' => 'https://disnakertrans.jatimprov.go.id',
                'list_url' => 'https://disnakertrans.jatimprov.go.id',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 24,
                'notes' => 'Portal resmi Dinas Tenaga Kerja dan Transmigrasi Provinsi Jawa Timur.',
            ],
            [
                'name' => 'KitaLulus',
                'base_url' => 'https://www.kitalulus.com',
                'list_url' => 'https://www.kitalulus.com/lowongan/kota/kabupaten-sumenep',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 12,
                'notes' => 'Sumber scraping lowongan kerja langsung dari direktori resmi KitaLulus Kabupaten Sumenep.',
            ],
            [
                'name' => 'Glints',
                'base_url' => 'https://glints.com',
                'list_url' => 'https://glints.com/id/opportunities/jobs/explore?keyword=Sumenep',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 24,
                'notes' => 'Sumber scraping dari Glints Indonesia untuk lowongan terverifikasi wilayah Sumenep.',
            ],
            [
                'name' => 'JobStreet',
                'base_url' => 'https://www.jobstreet.co.id',
                'list_url' => 'https://www.jobstreet.co.id/id/job-search/sumenep-jobs/',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 24,
                'notes' => 'Sumber scraping dari JobStreet area Sumenep & Jawa Timur.',
            ],
            [
                'name' => 'Pintarnya',
                'base_url' => 'https://pintarnya.com',
                'list_url' => 'https://api.pintarnya.com/api/pk/job?search=sumenep',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 12,
                'notes' => 'Sumber scraping via REST API resmi Pintarnya untuk lowongan kerja wilayah Sumenep.',
            ],
        ];

        foreach ($sources as $src) {
            ScrapingSource::updateOrCreate(
                ['name' => $src['name']],
                $src
            );
        }
    }
}
