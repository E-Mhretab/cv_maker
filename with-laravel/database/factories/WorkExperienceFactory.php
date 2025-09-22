<?php

namespace Database\Factories;

use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkExperience>
 */
class WorkExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-5 years', '-1 year');
        $endDate = $this->faker->dateTimeBetween($startDate, 'now');
        
        return [
            'cv_id' => Cv::factory(),
            'job_title' => $this->faker->jobTitle(),
            'company_name' => $this->faker->company(),
            'work_start' => $startDate,
            'work_end' => $endDate,
            'description' => $this->faker->paragraph(),
            'is_current' => $this->faker->boolean(20), // 20% chance of being current
        ];
    }
}