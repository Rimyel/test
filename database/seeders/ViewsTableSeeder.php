<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Poster;
class ViewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Получаем все постеры
        $posters = Poster::all();

        foreach ($posters as $poster) {
            // Увеличиваем количество просмотров на случайное число от 1 до 1000
            $viewsToAdd = rand(1, 1000);
            $poster->increment('views', $viewsToAdd);
        }
    }
}
