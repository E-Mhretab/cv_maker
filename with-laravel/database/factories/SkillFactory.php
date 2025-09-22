<?php

namespace Database\Factories;

use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cv_id' => Cv::factory(),
            'skill_name' => $this->faker->randomElement([
                'PHP', 'JavaScript', 'Python', 'Java', 'C#',
                'Laravel', 'React', 'Vue.js', 'Angular',
                'MySQL', 'PostgreSQL', 'MongoDB',
                'Git', 'Docker', 'AWS', 'Linux'
            ]),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}