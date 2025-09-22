<?php

namespace Database\Factories;

use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hobby>
 */
class HobbyFactory extends Factory
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
            'hobby_name' => $this->faker->randomElement([
                'Photography', 'Hiking', 'Reading', 'Cooking', 'Gardening',
                'Music', 'Sports', 'Travel', 'Painting', 'Writing',
                'Chess', 'Gaming', 'Volunteering', 'Dancing', 'Swimming'
            ]),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}