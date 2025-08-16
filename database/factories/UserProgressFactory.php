<?php

namespace Database\Factories;

use App\Models\Surah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProgress>
 */
class UserProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $versesCompleted = fake()->numberBetween(1, 10);
        $totalSessions = fake()->numberBetween($versesCompleted, $versesCompleted * 3);
        
        return [
            'user_id' => User::factory(),
            'surah_id' => Surah::factory(),
            'verses_completed' => $versesCompleted,
            'average_accuracy' => fake()->numberBetween(70, 95),
            'total_sessions' => $totalSessions,
            'weak_areas' => fake()->randomElements(['madd', 'ghunnah', 'makhraj', 'waqf'], fake()->numberBetween(0, 3)),
            'last_practiced_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}