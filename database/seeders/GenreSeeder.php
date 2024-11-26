<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;
class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $genres = [
            ['name' => 'Боевик'],
            ['name' => 'Комедия'],
            ['name' => 'Драма'],
            ['name' => 'Ужасы'],
            ['name' => 'Фантастика'],
            ['name' => 'Приключения'],
            ['name' => 'Мелодрама'],
            ['name' => 'Документальный'],
            ['name' => 'Анимация'],
            ['name' => 'Триллер'],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
