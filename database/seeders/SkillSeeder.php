<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use App\Models\Skill;
use App\Models\SkillAlias;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skillsData = [
            // Administrasi
            [
                'name' => 'Microsoft Excel',
                'category' => 'Administrasi & Perkantoran',
                'aliases' => ['Excel', 'MS Excel', 'Microsoft Excel', 'Microsoft Office Excel', 'Spreadsheet', 'Ms. Excel'],
            ],
            [
                'name' => 'Microsoft Word',
                'category' => 'Administrasi & Perkantoran',
                'aliases' => ['Word', 'MS Word', 'Microsoft Word', 'Microsoft Office Word', 'Ms. Word'],
            ],
            [
                'name' => 'Microsoft Office',
                'category' => 'Administrasi & Perkantoran',
                'aliases' => ['MS Office', 'Microsoft Office', 'Aplikasi Perkantoran', 'Office Suite'],
            ],
            [
                'name' => 'Administrasi Perkantoran',
                'category' => 'Administrasi & Perkantoran',
                'aliases' => ['Administrasi', 'Staff Admin', 'Tata Usaha', 'Office Administration', 'Admin'],
            ],
            [
                'name' => 'Data Entry',
                'category' => 'Administrasi & Perkantoran',
                'aliases' => ['Input Data', 'Data Processing', 'Entri Data', 'Ketik Dokumen'],
            ],
            [
                'name' => 'Pengarsipan Dokumen',
                'category' => 'Administrasi & Perkantoran',
                'aliases' => ['Filing', 'Arsip Dokumen', 'Document Control', 'Pengarsipan'],
            ],

            // IT & Komputer
            [
                'name' => 'PHP',
                'category' => 'IT & Komputer',
                'aliases' => ['PHP Programming', 'PHP 8', 'Backend PHP'],
            ],
            [
                'name' => 'Laravel',
                'category' => 'IT & Komputer',
                'aliases' => ['Laravel Framework', 'Laravel PHP', 'Framework Laravel'],
            ],
            [
                'name' => 'JavaScript',
                'category' => 'IT & Komputer',
                'aliases' => ['JS', 'Vanilla JS', 'ES6', 'Javascript'],
            ],
            [
                'name' => 'MySQL',
                'category' => 'IT & Komputer',
                'aliases' => ['SQL', 'Database MySQL', 'Relational Database'],
            ],
            [
                'name' => 'IT Support',
                'category' => 'IT & Komputer',
                'aliases' => ['Teknisi Komputer', 'Helpdesk IT', 'Perbaikan PC', 'Jaringan Komputer', 'Troubleshooting'],
            ],

            // Marketing & Penjualan
            [
                'name' => 'Penjualan (Sales)',
                'category' => 'Marketing & Penjualan',
                'aliases' => ['Sales', 'Direct Selling', 'Canvassing', 'Pemasaran Langsung', 'Penjualan'],
            ],
            [
                'name' => 'Digital Marketing',
                'category' => 'Marketing & Penjualan',
                'aliases' => ['Pemasaran Digital', 'Social Media Marketing', 'Online Marketing', 'FB Ads', 'Tiktok Marketing'],
            ],
            [
                'name' => 'Komunikasi & Negosiasi',
                'category' => 'Marketing & Penjualan',
                'aliases' => ['Communication', 'Public Speaking', 'Negosiasi', 'Kemampuan Komunikasi'],
            ],

            // Keuangan & Kasir
            [
                'name' => 'Akuntansi (Accounting)',
                'category' => 'Keuangan & Akuntansi',
                'aliases' => ['Accounting', 'Pembukuan', 'Laporan Keuangan', 'Bookkeeping'],
            ],
            [
                'name' => 'Operasional Kasir',
                'category' => 'Keuangan & Akuntansi',
                'aliases' => ['Kasir', 'Cashier', 'POS System', 'Point of Sales'],
            ],

            // Layanan Pelanggan
            [
                'name' => 'Customer Service',
                'category' => 'Pelayanan Pelanggan (CS)',
                'aliases' => ['Pelayanan Pelanggan', 'CS', 'Call Center', 'Customer Care'],
            ],

            // Pergudangan
            [
                'name' => 'Manajemen Gudang (Warehouse)',
                'category' => 'Logistik & Pergudangan',
                'aliases' => ['Admin Gudang', 'Stok Barang', 'Stock Opname', 'Pergudangan', 'Packing'],
            ],

            // Transportasi
            [
                'name' => 'Mengemudi (Driver)',
                'category' => 'Transportasi & Driver',
                'aliases' => ['Driver', 'Sopir', 'SIM A', 'SIM B1', 'Pengemudi'],
            ],

            // Desain Grafis
            [
                'name' => 'Desain Grafis',
                'category' => 'Desain Grafis & Kreatif',
                'aliases' => ['Graphic Design', 'CorelDRAW', 'Photoshop', 'Canva', 'Adobe Illustrator'],
            ],

            // Retail
            [
                'name' => 'Pramuniaga (Shop Assistant)',
                'category' => 'Retail & Toko',
                'aliases' => ['Pramuniaga', 'Penjaga Toko', 'Store Crew', 'Pelayan Toko'],
            ],
        ];

        foreach ($skillsData as $sData) {
            $cat = JobCategory::where('name', $sData['category'])->first();

            $skill = Skill::updateOrCreate(
                ['name' => $sData['name']],
                [
                    'job_category_id' => $cat?->id,
                    'slug' => Str::slug($sData['name']),
                    'description' => 'Keahlian terstandarisasi untuk '.$sData['name'],
                ]
            );

            // Always add the skill name itself as an alias
            SkillAlias::firstOrCreate([
                'skill_id' => $skill->id,
                'alias' => $sData['name'],
            ]);

            // Add other aliases
            foreach ($sData['aliases'] as $alias) {
                SkillAlias::firstOrCreate([
                    'skill_id' => $skill->id,
                    'alias' => trim($alias),
                ]);
            }
        }
    }
}
