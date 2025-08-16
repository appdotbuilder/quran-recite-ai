<?php

namespace Tests\Feature;

use App\Models\Qari;
use App\Models\Surah;
use App\Models\User;
use App\Models\Verse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuranTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_quran_interface(): void
    {
        Surah::factory()->count(3)->create();
        Qari::factory()->featured()->count(2)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('welcome')
                ->has('surahs')
                ->has('qaris')
        );
    }

    public function test_can_view_specific_surah(): void
    {
        $surah = Surah::factory()->create();
        
        // Create verses with unique verse numbers
        for ($i = 1; $i <= 5; $i++) {
            Verse::factory()->for($surah)->create(['verse_number' => $i]);
        }
        
        Qari::factory()->featured()->count(2)->create();

        $response = $this->get("/surah/{$surah->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('welcome')
                ->has('currentSurah')
                ->has('verses', 5)
        );
    }

    public function test_can_get_qaris_list(): void
    {
        Qari::factory()->featured()->count(3)->create();
        Qari::factory()->count(2)->create();

        $response = $this->get('/api/qaris');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function test_recitation_page_requires_authentication(): void
    {
        $response = $this->get('/recitation');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_recitation_page(): void
    {
        $user = User::factory()->create();
        Surah::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/recitation');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('recitation/index')
                ->has('surahs')
        );
    }
}