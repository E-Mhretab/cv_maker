<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $education = [
            [
                'id' => 1,
                'cv_id' => 1,
                'degree' => 'Bachelor of Computer Science',
                'institution' => 'University of Amsterdam',
                'education_start' => '2018-09-01',
                'education_end' => '2022-06-30',
                'is_current' => 0,
                'description' => 'Specialized in software engineering and database systems. Graduated with honors.'
            ],
            [
                'id' => 2,
                'cv_id' => 2,
                'degree' => 'Master of Software Engineering',
                'institution' => 'Delft University of Technology',
                'education_start' => '2020-09-01',
                'education_end' => null,
                'is_current' => 1,
                'description' => 'Advanced studies in software architecture and distributed systems.'
            ],
            [
                'id' => 3,
                'cv_id' => 2,
                'degree' => 'Bachelor of Information Technology',
                'institution' => 'Eindhoven University of Technology',
                'education_start' => '2016-09-01',
                'education_end' => '2020-06-30',
                'is_current' => 0,
                'description' => 'Foundation in computer science with focus on web technologies.'
            ]
        ];

        foreach ($education as $edu) {
            \DB::table('education')->updateOrInsert(
                ['id' => $edu['id']],
                $edu
            );
        }
    }
}
