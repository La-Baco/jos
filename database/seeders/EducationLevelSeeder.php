<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['name' => 'SD / Sederajat', 'code' => 'SD', 'hierarchy_order' => 1],
            ['name' => 'SMP / MTs Sederajat', 'code' => 'SMP', 'hierarchy_order' => 2],
            ['name' => 'SMA / SMK / MA Sederajat', 'code' => 'SMA/SMK', 'hierarchy_order' => 3],
            ['name' => 'Diploma 1 (D1)', 'code' => 'D1', 'hierarchy_order' => 4],
            ['name' => 'Diploma 2 (D2)', 'code' => 'D2', 'hierarchy_order' => 5],
            ['name' => 'Diploma 3 (D3)', 'code' => 'D3', 'hierarchy_order' => 6],
            ['name' => 'Diploma 4 (D4)', 'code' => 'D4', 'hierarchy_order' => 7],
            ['name' => 'Sarjana (S1)', 'code' => 'S1', 'hierarchy_order' => 8],
            ['name' => 'Magister (S2)', 'code' => 'S2', 'hierarchy_order' => 9],
            ['name' => 'Doktor (S3)', 'code' => 'S3', 'hierarchy_order' => 10],
        ];

        foreach ($levels as $lvl) {
            EducationLevel::updateOrCreate(
                ['code' => $lvl['code']],
                ['name' => $lvl['name'], 'hierarchy_order' => $lvl['hierarchy_order']]
            );
        }
    }
}
