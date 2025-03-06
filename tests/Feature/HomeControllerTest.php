<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Like;
use App\Models\Poster;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testWelcome()
    {
        $poster = Poster::factory()->create(['visibility' => 1]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('posts', function ($posts) use ($poster) {
            return $posts->contains($poster);
        });
    }
   
    public function testSearch()
    {
        $poster = Poster::factory()->create(['name' => 'Test Poster', 'visibility' => 1]);

        $response = $this->post('/search', ['word' => 'Test']);

        $response->assertStatus(200);
        $response->assertViewHas('posts', function ($posts) use ($poster) {
            return $posts->contains($poster);
        });
    }

    public function testNewComment()
    {
        $user = User::factory()->create();
        $poster = Poster::factory()->create();

        $response = $this->actingAs($user)->post("/video/{$poster->id}/newComment", ['message' => 'Test Comment']);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'poster_id' => $poster->id,
            'message' => 'Test Comment',
        ]);
    }
   
}