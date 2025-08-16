<?php

namespace Tests\Feature;

use App\Models\RecitationSession;
use App\Models\Surah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_recitation_for_analysis(): void
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        $surah = Surah::factory()->create();
        
        $audioFile = UploadedFile::fake()->create('recitation.wav', 1000, 'audio/wav');

        $response = $this->actingAs($user)
            ->post('/api/recitation', [
                'surah_id' => $surah->id,
                'verse_number' => 1,
                'audio_file' => $audioFile,
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'session' => [
                'id',
                'accuracy_score',
                'ai_feedback',
                'tajwid_errors',
                'pronunciation_errors',
            ],
            'message',
        ]);

        $this->assertDatabaseHas('recitation_sessions', [
            'user_id' => $user->id,
            'surah_id' => $surah->id,
            'verse_number' => 1,
            'status' => 'analyzed',
        ]);

        // Check that a recitation session was created
        $this->assertDatabaseCount('recitation_sessions', 1);
    }

    public function test_recitation_submission_requires_authentication(): void
    {
        $surah = Surah::factory()->create();
        $audioFile = UploadedFile::fake()->create('recitation.wav', 1000, 'audio/wav');

        $response = $this->postJson('/api/recitation', [
            'surah_id' => $surah->id,
            'verse_number' => 1,
            'audio_file' => $audioFile,
        ]);

        $response->assertStatus(401);
    }

    public function test_can_get_recitation_history(): void
    {
        $user = User::factory()->create();
        RecitationSession::factory()->count(5)->for($user)->create();

        $response = $this->actingAs($user)->get('/recitation/history');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'accuracy_score',
                    'status',
                    'created_at',
                    'surah',
                ],
            ],
        ]);
    }

    public function test_recitation_analysis_creates_user_progress(): void
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        $surah = Surah::factory()->create();
        
        $audioFile = UploadedFile::fake()->create('recitation.wav', 1000, 'audio/wav');

        $this->actingAs($user)
            ->post('/api/recitation', [
                'surah_id' => $surah->id,
                'verse_number' => 1,
                'audio_file' => $audioFile,
            ]);

        $this->assertDatabaseHas('user_progress', [
            'user_id' => $user->id,
            'surah_id' => $surah->id,
            'total_sessions' => 1,
        ]);
    }
}