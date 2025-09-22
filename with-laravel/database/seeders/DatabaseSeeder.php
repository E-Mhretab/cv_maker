<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CvSeeder::class,
            CvMetadataSeeder::class,
            WorkExperienceSeeder::class,
            EducationSeeder::class,
            SkillsSeeder::class,
            LanguagesSeeder::class,
            HobbiesSeeder::class,
        ]);
    }
}
