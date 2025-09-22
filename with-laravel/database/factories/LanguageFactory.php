<?php

namespace Database\Factories;

use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
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
            'language_name' => $this->faker->randomElement([
                'English', 'Spanish', 'French', 'German', 'Italian',
                'Portuguese', 'Dutch', 'Chinese', 'Japanese', 'Arabic'
            ]),
            'proficiency' => $this->faker->randomElement([
                'basic', 'conversational', 'fluent', 'native'
            ]),
        ];
    }
}