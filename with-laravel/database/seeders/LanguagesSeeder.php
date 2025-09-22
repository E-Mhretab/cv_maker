<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'id' => 1,
                'cv_id' => 1,
                'language_name' => 'English',
                'proficiency' => 'fluent'
            ],
            [
                'id' => 2,
                'cv_id' => 1,
                'language_name' => 'Dutch',
                'proficiency' => 'native'
            ],
            [
                'id' => 3,
                'cv_id' => 1,
                'language_name' => 'Spanish',
                'proficiency' => 'conversational'
            ],
            [
                'id' => 4,
                'cv_id' => 2,
                'language_name' => 'English',
                'proficiency' => 'fluent'
            ],
            [
                'id' => 5,
                'cv_id' => 2,
                'language_name' => 'German',
                'proficiency' => 'basic'
            ]
        ];

        foreach ($languages as $language) {
            \DB::table('languages')->updateOrInsert(
                ['id' => $language['id']],
                $language
            );
        }
    }
}
