<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Surah>
 */
class SurahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->unique()->numberBetween(1, 114),
            'name_arabic' => fake()->word(),
            'name_english' => fake()->words(2, true),
            'name_indonesian' => fake()->words(2, true),
            'verses_count' => fake()->numberBetween(3, 286),
            'revelation_type' => fake()->randomElement(['meccan', 'medinan']),
            'description_english' => fake()->sentence(),
            'description_indonesian' => fake()->sentence(),
        ];
    }
}