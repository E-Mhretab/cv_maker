<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cv;
use App\Models\CvMetadata;
use App\Models\Skill;
use App\Models\Language;
use App\Models\WorkExperience;
use App\Models\Education;
use App\Models\User;

class CvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get a test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'username' => 'testuser',
                'password' => bcrypt('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        // Create sample CVs
        $cv1 = Cv::create([
            'name' => 'Nathan Jethoe',
            'email' => 'nathan@example.com',
            'phone_number' => '+31 6 12345678',
            'address' => 'Amsterdam, Netherlands',
            'profile_summary' => 'Experienced software developer with expertise in Laravel, PHP, and modern web technologies. Passionate about creating efficient and scalable applications.',
            'user_id' => $user->id,
        ]);

        CvMetadata::create([
            'cv_id' => $cv1->id,
            'is_public' => true,
            'published_at' => now(),
            'template_type' => 1,
        ]);

        // Add skills
        Skill::create(['cv_id' => $cv1->id, 'skill_name' => 'Laravel', 'description' => 'Expert level']);
        Skill::create(['cv_id' => $cv1->id, 'skill_name' => 'PHP', 'description' => 'Expert level']);
        Skill::create(['cv_id' => $cv1->id, 'skill_name' => 'JavaScript', 'description' => 'Advanced level']);
        Skill::create(['cv_id' => $cv1->id, 'skill_name' => 'MySQL', 'description' => 'Advanced level']);

        // Add languages
        Language::create(['cv_id' => $cv1->id, 'language_name' => 'English', 'proficiency' => 'native']);
        Language::create(['cv_id' => $cv1->id, 'language_name' => 'Dutch', 'proficiency' => 'fluent']);

        // Add work experience
        WorkExperience::create([
            'cv_id' => $cv1->id,
            'company_name' => 'Tech Company',
            'position' => 'Senior Developer',
            'start_date' => '2022-01-01',
            'end_date' => null,
            'description' => 'Led development of multiple web applications using Laravel and modern frontend technologies.',
        ]);

        // Add education
        Education::create([
            'cv_id' => $cv1->id,
            'institution_name' => 'University of Technology',
            'degree' => 'Bachelor of Computer Science',
            'field_of_study' => 'Software Engineering',
            'start_date' => '2018-09-01',
            'end_date' => '2022-06-01',
        ]);

        // Create second CV
        $cv2 = Cv::create([
            'name' => 'Esey Johnson',
            'email' => 'esey@example.com',
            'phone_number' => '+31 6 87654321',
            'address' => 'Rotterdam, Netherlands',
            'profile_summary' => 'Creative designer and developer with a passion for user experience and modern web design. Expert in creating beautiful and functional interfaces.',
            'user_id' => $user->id,
        ]);

        CvMetadata::create([
            'cv_id' => $cv2->id,
            'is_public' => true,
            'published_at' => now()->subDays(1),
            'template_type' => 2,
        ]);

        // Add skills for second CV
        Skill::create(['cv_id' => $cv2->id, 'skill_name' => 'UI/UX Design', 'description' => 'Expert level']);
        Skill::create(['cv_id' => $cv2->id, 'skill_name' => 'Figma', 'description' => 'Expert level']);
        Skill::create(['cv_id' => $cv2->id, 'skill_name' => 'React', 'description' => 'Advanced level']);
        Skill::create(['cv_id' => $cv2->id, 'skill_name' => 'CSS', 'description' => 'Expert level']);

        // Add languages for second CV
        Language::create(['cv_id' => $cv2->id, 'language_name' => 'English', 'proficiency' => 'native']);
        Language::create(['cv_id' => $cv2->id, 'language_name' => 'Spanish', 'proficiency' => 'conversational']);

        // Add work experience for second CV
        WorkExperience::create([
            'cv_id' => $cv2->id,
            'company_name' => 'Design Studio',
            'position' => 'UI/UX Designer',
            'start_date' => '2021-03-01',
            'end_date' => null,
            'description' => 'Designed user interfaces for web and mobile applications, focusing on user experience and accessibility.',
        ]);

        // Add education for second CV
        Education::create([
            'cv_id' => $cv2->id,
            'institution_name' => 'Design Academy',
            'degree' => 'Bachelor of Design',
            'field_of_study' => 'Digital Design',
            'start_date' => '2017-09-01',
            'end_date' => '2021-06-01',
        ]);
    }
}