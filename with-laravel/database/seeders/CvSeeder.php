<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cvs = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'address' => '123 Main Street, Amsterdam, Netherlands',
                'phone_number' => '+31 6 12345678',
                'email' => 'john.doe@example.com',
                'date_of_birth' => '1995-03-15',
                'linkedin_profile' => 'https://linkedin.com/in/john-doe',
                'portfolio' => 'https://johndoe.dev',
                'profile_summary' => 'Experienced software developer with 5+ years in web development. Passionate about creating efficient and user-friendly applications using modern technologies.',
                'user_id' => 2
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'address' => '456 Oak Avenue, Rotterdam, Netherlands',
                'phone_number' => '+31 6 87654321',
                'email' => 'jane.smith@example.com',
                'date_of_birth' => '1992-07-22',
                'linkedin_profile' => 'https://linkedin.com/in/jane-smith',
                'portfolio' => 'https://janesmith.dev',
                'profile_summary' => 'Full-stack developer specializing in React and Node.js. Strong background in database design and API development. Always eager to learn new technologies.',
                'user_id' => 3
            ]
        ];

        foreach ($cvs as $cv) {
            \DB::table('cv')->updateOrInsert(
                ['id' => $cv['id']],
                $cv
            );
        }
    }
}
