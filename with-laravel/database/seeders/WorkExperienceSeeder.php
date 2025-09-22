<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workExperience = [
            [
                'id' => 1,
                'cv_id' => 1,
                'job_title' => 'Senior Software Developer',
                'company_name' => 'TechCorp Solutions',
                'work_start' => '2022-01-01',
                'work_end' => null,
                'description' => 'Leading development of web applications using React, Node.js, and PostgreSQL. Mentoring junior developers and implementing best practices.',
                'is_current' => 1
            ],
            [
                'id' => 2,
                'cv_id' => 1,
                'job_title' => 'Full Stack Developer',
                'company_name' => 'Digital Innovations',
                'work_start' => '2020-06-01',
                'work_end' => '2021-12-31',
                'description' => 'Developed and maintained web applications using PHP, MySQL, and JavaScript. Collaborated with design team to create user-friendly interfaces.',
                'is_current' => 0
            ],
            [
                'id' => 3,
                'cv_id' => 2,
                'job_title' => 'Frontend Developer',
                'company_name' => 'WebStudio Pro',
                'work_start' => '2021-03-01',
                'work_end' => null,
                'description' => 'Creating responsive web applications with React and TypeScript. Focus on performance optimization and user experience.',
                'is_current' => 1
            ]
        ];

        foreach ($workExperience as $work) {
            \DB::table('work_experience')->updateOrInsert(
                ['id' => $work['id']],
                $work
            );
        }
    }
}
