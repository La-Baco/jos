<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EducationLevelSeeder::class,
            LocationSeeder::class,
            JobCategorySeeder::class,
            SkillSeeder::class,
            ScrapingSourceSeeder::class,
            UserSeeder::class,
            OfficialAccountSeeder::class,
            JobSeeder::class,
        ]);
    }
}
