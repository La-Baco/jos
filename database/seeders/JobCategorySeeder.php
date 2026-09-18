<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Ritel & Perdagangan / Toko', 'icon' => 'shopping-bag', 'description' => 'Pramuniaga, penjaga toko, kasir, crew store minimarket.'],
            ['name' => 'Logistik, Kurir & Operasional Field', 'icon' => 'truck', 'description' => 'Kurir ekspedisi, admin gudang, driver operasional.'],
            ['name' => 'Staf Administrasi & Keuangan', 'icon' => 'clipboard-document-list', 'description' => 'Staf admin, data entry, pembukuan, finance.'],
            ['name' => 'Layanan Pelanggan & Sales', 'icon' => 'phone', 'description' => 'Customer service, sales executive, promotor.'],
            ['name' => 'Teknik, IT & Industri Lokal', 'icon' => 'wrench-screwdriver', 'description' => 'Teknisi, mekanik, IT support, pekerja industri lokal.'],
        ];

        foreach ($categories as $cat) {
            JobCategory::updateOrCreate(
                ['name' => $cat['name']],
                [
                    'slug' => Str::slug($cat['name']),
                    'icon' => $cat['icon'],
                    'description' => $cat['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
