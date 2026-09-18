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
                'name' => 'KitaLulus',
                'base_url' => 'https://www.kitalulus.com',
                'list_url' => 'https://www.kitalulus.com/lowongan/in-kabupaten-sumenep',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 12,
                'notes' => 'Sumber scraping lowongan kerja dari KitaLulus area Kabupaten Sumenep.',
            ],
            [
                'name' => 'Glints',
                'base_url' => 'https://glints.com',
                'list_url' => 'https://glints.com/id/job-location/indonesia/jawa-timur/kab-sumenep',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 24,
                'notes' => 'Sumber scraping dari Glints untuk area Sumenep.',
            ],
            [
                'name' => 'JobStreet',
                'base_url' => 'https://id.jobstreet.com',
                'list_url' => 'https://id.jobstreet.com/id/sumenep-jobs',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 24,
                'notes' => 'Sumber scraping dari JobStreet area Sumenep.',
            ],
            [
                'name' => 'Pintarnya',
                'base_url' => 'https://pintarnya.com',
                'list_url' => 'https://pintarnya.com/l-kab-sumenep,jawa-timur-lowongan',
                'scraper_type' => 'python_modular',
                'is_active' => true,
                'interval_hours' => 24,
                'notes' => 'Sumber scraping dari Pintarnya area Sumenep.',
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
