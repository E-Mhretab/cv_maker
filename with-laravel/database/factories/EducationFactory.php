<?php

namespace Database\Factories;

use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-10 years', '-2 years');
        $endDate = $this->faker->dateTimeBetween($startDate, 'now');
        
        return [
            'cv_id' => Cv::factory(),
            'degree' => $this->faker->randomElement([
                'Bachelor of Computer Science',
                'Master of Software Engineering',
                'Bachelor of Information Technology',
                'Master of Data Science',
                'Bachelor of Engineering',
            ]),
            'institution' => $this->faker->randomElement([
                'University of Technology',
                'State University',
                'Technical Institute',
                'International University',
                'Community College',
            ]),
            'education_start' => $startDate,
            'education_end' => $endDate,
            'description' => $this->faker->paragraph(),
            'is_current' => $this->faker->boolean(10), // 10% chance of being current
        ];
    }
}