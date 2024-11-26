<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GenrePoster;
use Faker\Factory as Faker;
use App\Models\Poster;
use App\Models\Genre;
class GenrePosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $genres_poster = [
            
       
        ];

        foreach ($genres_poster as $genreposter) {
            GenrePoster::create($genreposter);
        }
        $faker = Faker::create();

        // Получаем все постеры и жанры
        $posters = Poster::all();
        $genres = Genre::all();

        // Проходим по каждому постеру
        foreach ($posters as $poster) {
            // Выбираем случайное количество жанров (от 1 до 3)
            $randomGenres = $faker->randomElements($genres, $faker->numberBetween(1, 3));

            // Связываем постер с жанрами
            foreach ($randomGenres as $genre) {
                GenrePoster::create([
                    'genre_id' => $genre->id,
                    'poster_id' => $poster->id,
                ]);
            }
        }
    }
}
