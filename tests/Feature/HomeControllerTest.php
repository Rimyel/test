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

    public function testIndex()
    {
        $user = User::factory()->create();
        $like = Like::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertViewHas('like', function ($likes) use ($like) {
            return $likes->contains($like);
        });
    }
    public function testWelcome()
    {
        $poster = Poster::factory()->create(['visibility' => 1]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('posts', function ($posts) use ($poster) {
            return $posts->contains($poster);
        });
    }
    public function testPost()
    {
        $user = User::factory()->create();
        $poster = Poster::factory()->create();

        $response = $this->actingAs($user)->get("/post/{$poster->id}");

        $response->assertStatus(200);
        $response->assertViewHas('post', $poster);
        $response->assertViewHas('comments');
        $response->assertViewHas('userRating');
        $response->assertViewHas('ratings');
        $response->assertViewHas('averageRating');
        $response->assertViewHas('like');
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
    public function testSee()
    {
        $genre = Genre::factory()->create();
        $poster = Poster::factory()->create();
        $poster->genres()->attach($genre);

        $response = $this->get('/see/what', ['genres' => [$genre->name]]);

        $response->assertStatus(200);
        $response->assertViewHas('posts', function ($posts) use ($poster) {
            return $posts->contains($poster);
        });
        $response->assertViewHas('genres');
        $response->assertViewHas('selectedGenres');
    }
    public function testRating()
    {
        $poster = Poster::factory()->create(['visibility' => 1]);

        $response = $this->get('/rating');

        $response->assertStatus(200);
        $response->assertViewHas('posts');
        $response->assertViewHas('sortBy');
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
    public function testAddLiked()
    {
        $user = User::factory()->create();
        $poster = Poster::factory()->create();

        $response = $this->actingAs($user)->get("/liked/add/{$poster->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'poster_id' => $poster->id,
        ]);

        // Тест на удаление лайка
        $response = $this->actingAs($user)->get("/liked/add/{$poster->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'poster_id' => $poster->id,
        ]);
    }


    /**
     * Тест на валидацию данных.
     *
     * @return void
     */
    public function test_validation_fails()
    {
        // Создаем пользователя и авторизуем его
        $user = User::factory()->create();
        $this->actingAs($user);

        // Данные для запроса с невалидными данными
        $data = [
            'user_id' => $user->id,
            'poster_id' => 1,
            'rank' => 11, // Невалидное значение для rank
            'rating' => 5,
        ];

        // Отправляем POST-запрос
        $response = $this->post(route('rate', ['post_id' => 1]), $data);

        // Проверяем, что редирект не произошел и ошибка валидации присутствует
        $response->assertSessionHasErrors(['rank']);
    }
    public function testHandle()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $this->assertDatabaseHas('analytics', [
            'event_type' => 'page_view',
            'url' => 'http://localhost',
            'user_id' => $user->id,
        ]);
    }
}