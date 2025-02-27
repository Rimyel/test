<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parameter; // Импортируем класс Parameter

class ParameterSeeder extends Seeder
{
    public function run()
{
    $parameters = [
        ['attribute' => 'manufacturer', 'name' => 'Bitmain'],
        ['attribute' => 'manufacturer', 'name' => 'Whatsminer'],
        ['attribute' => 'algorithm', 'name' => 'Scrypt'],
        ['attribute' => 'algorithm', 'name' => 'SHA-256'],
        ['attribute' => 'algorithm', 'name' => 'X11'],
        ['attribute' => 'coin', 'name' => 'BTC'],
        ['attribute' => 'coin', 'name' => 'Kaspa'],
        ['attribute' => 'coin', 'name' => 'LTC'],
        ['attribute' => 'coin', 'name' => 'BCH'],
    ];

    foreach ($parameters as $parameter) {
        Parameter::firstOrCreate(
            [
                'attribute' => $parameter['attribute'], // Условие поиска по attribute
                'name' => $parameter['name']           // Условие поиска по name
            ],
            $parameter // Данные для создания, если запись не найдена
        );
    }
}
}