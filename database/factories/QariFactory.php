<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Qari>
 */
class QariFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'name_arabic' => fake()->optional()->name(),
            'country' => fake()->country(),
            'description' => fake()->optional()->paragraph(),
            'audio_base_url' => fake()->url(),
            'is_featured' => fake()->boolean(30), // 30% chance of being featured
        ];
    }

    /**
     * Indicate that the qari is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}