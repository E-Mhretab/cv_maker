<?php

namespace Database\Factories;

use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CvMetadata>
 */
class CvMetadataFactory extends Factory
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
            'template_type' => $this->faker->numberBetween(1, 3),
            'is_public' => $this->faker->boolean(),
            'published_at' => $this->faker->optional()->dateTime(),
        ];
    }
}