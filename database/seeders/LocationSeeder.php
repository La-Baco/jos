<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Remote
        Location::firstOrCreate(
            ['name' => 'Remote / Dari Mana Saja', 'type' => 'remote'],
            ['parent_id' => null]
        );

        // 2. Negara Indonesia
        $indonesia = Location::firstOrCreate(
            ['name' => 'Indonesia', 'type' => 'negara'],
            ['parent_id' => null]
        );

        // 3. Provinsi Jawa Timur
        $jatim = Location::firstOrCreate(
            ['name' => 'Jawa Timur', 'type' => 'provinsi'],
            ['parent_id' => $indonesia->id]
        );

        // 4. Kabupaten Sumenep
        $sumenep = Location::firstOrCreate(
            ['name' => 'Sumenep', 'type' => 'kabupaten'],
            ['parent_id' => $jatim->id]
        );

        // Kabupaten Madura lainnya & Surabaya
        Location::firstOrCreate(['name' => 'Pamekasan', 'type' => 'kabupaten'], ['parent_id' => $jatim->id]);
        Location::firstOrCreate(['name' => 'Sampang', 'type' => 'kabupaten'], ['parent_id' => $jatim->id]);
        Location::firstOrCreate(['name' => 'Bangkalan', 'type' => 'kabupaten'], ['parent_id' => $jatim->id]);
        Location::firstOrCreate(['name' => 'Surabaya', 'type' => 'kabupaten'], ['parent_id' => $jatim->id]);
        Location::firstOrCreate(['name' => 'Sidoarjo', 'type' => 'kabupaten'], ['parent_id' => $jatim->id]);
        Location::firstOrCreate(['name' => 'Malang', 'type' => 'kabupaten'], ['parent_id' => $jatim->id]);

        // 27 Kecamatan di Kabupaten Sumenep
        $kecamatanSumenep = [
            'Kota Sumenep',
            'Kalianget',
            'Talango',
            'Saronggi',
            'Bluto',
            'Pragaan',
            'Lenteng',
            'Ganding',
            'Guluk-Guluk',
            'Rubaru',
            'Ambunten',
            'Pasongsongan',
            'Dasuk',
            'Manding',
            'Batuputih',
            'Dungkek',
            'Gapura',
            'Batang-Batang',
            'Giligenting',
            'Gayam',
            'Nonggunong',
            'Raas',
            'Arjasa',
            'Kangayan',
            'Sapeken',
            'Masalembu',
        ];

        foreach ($kecamatanSumenep as $kec) {
            Location::firstOrCreate(
                ['name' => $kec, 'type' => 'kecamatan'],
                ['parent_id' => $sumenep->id]
            );
        }
    }
}
