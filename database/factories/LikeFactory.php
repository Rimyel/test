<?php

namespace Database\Factories;
use App\Models\Like;
use App\Models\User;
use App\Models\Poster;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'poster_id' => Poster::factory(),
        ];
    }

    public function testAddLiked()
    {
        $user = User::factory()->create();
        $poster = Poster::factory()->create();

        $like = Like::factory()->create([
            'user_id' => $user->id,
            'poster_id' => $poster->id,
        ]);

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
}