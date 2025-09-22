<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HobbiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hobbies = [
            [
                'id' => 1,
                'cv_id' => 1,
                'hobby_name' => 'Programming',
                'description' => 'Open source contributions and personal projects'
            ],
            [
                'id' => 2,
                'cv_id' => 1,
                'hobby_name' => 'Photography',
                'description' => 'Digital photography and photo editing'
            ],
            [
                'id' => 3,
                'cv_id' => 1,
                'hobby_name' => 'Fitness',
                'description' => 'Regular gym workouts and outdoor activities'
            ],
            [
                'id' => 4,
                'cv_id' => 2,
                'hobby_name' => 'Reading',
                'description' => 'Technical books and software development blogs'
            ],
            [
                'id' => 5,
                'cv_id' => 2,
                'hobby_name' => 'Hiking',
                'description' => 'Weekend hiking trips and nature photography'
            ]
        ];

        foreach ($hobbies as $hobby) {
            \DB::table('hobbies')->updateOrInsert(
                ['id' => $hobby['id']],
                $hobby
            );
        }
    }
}
