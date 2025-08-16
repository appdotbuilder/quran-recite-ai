<?php

namespace Database\Factories;

use App\Models\Surah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecitationSession>
 */
class RecitationSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $accuracyScore = fake()->numberBetween(60, 100);
        
        return [
            'user_id' => User::factory(),
            'surah_id' => Surah::factory(),
            'verse_number' => fake()->numberBetween(1, 10),
            'audio_file_path' => 'public/recitations/' . fake()->uuid() . '.wav',
            'ai_feedback' => [
                'overall' => 'Good recitation! Keep practicing to improve your tajwid.',
                'strengths' => ['Clear pronunciation', 'Good rhythm'],
                'improvements' => ['Work on elongation', 'Focus on letter exits'],
            ],
            'accuracy_score' => $accuracyScore,
            'tajwid_errors' => $accuracyScore < 85 ? [
                ['type' => 'madd', 'position' => '0:15', 'description' => 'Elongation too short'],
            ] : [],
            'pronunciation_errors' => $accuracyScore < 75 ? [
                ['type' => 'makhraj', 'position' => '0:28', 'description' => 'Letter exit point incorrect'],
            ] : [],
            'status' => fake()->randomElement(['pending', 'analyzed', 'reviewed']),
        ];
    }
}