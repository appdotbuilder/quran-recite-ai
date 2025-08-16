<?php

namespace Database\Factories;

use App\Models\Surah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Verse>
 */
class VerseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'surah_id' => Surah::factory(),
            'verse_number' => fake()->numberBetween(1, 50),
            'text_arabic' => fake()->sentence(),
            'text_english' => fake()->sentence(),
            'text_indonesian' => fake()->sentence(),
            'transliteration' => fake()->words(5, true),
            'audio_url' => fake()->url(),
        ];
    }
}