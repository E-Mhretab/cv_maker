<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            [
                'id' => 1,
                'cv_id' => 1,
                'skill_name' => 'PHP',
                'description' => 'Backend development with PHP and MySQL'
            ],
            [
                'id' => 2,
                'cv_id' => 1,
                'skill_name' => 'JavaScript',
                'description' => 'Frontend development with vanilla JS and frameworks'
            ],
            [
                'id' => 3,
                'cv_id' => 1,
                'skill_name' => 'HTML/CSS',
                'description' => 'Responsive web design and modern CSS techniques'
            ],
            [
                'id' => 4,
                'cv_id' => 2,
                'skill_name' => 'React',
                'description' => 'Frontend development with React and Redux'
            ],
            [
                'id' => 5,
                'cv_id' => 2,
                'skill_name' => 'Node.js',
                'description' => 'Backend development with Node.js and Express'
            ],
            [
                'id' => 6,
                'cv_id' => 2,
                'skill_name' => 'TypeScript',
                'description' => 'Type-safe JavaScript development'
            ]
        ];

        foreach ($skills as $skill) {
            \DB::table('skills')->updateOrInsert(
                ['id' => $skill['id']],
                $skill
            );
        }
    }
}
