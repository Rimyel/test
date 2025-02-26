<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parameter;

class ParameterSeeder extends Seeder
{
    use App\Models\Parameter;

public function run()
{
    $parameters = [
        ['attribute' => 'manufacturer', 'name' => 'Bitmain'],
        ['attribute' => 'manufacturer', 'name' => 'Whatsminer'], // Это вызовет ошибку
        ['attribute' => 'algorithm', 'name' => 'Scrypt'],
        ['attribute' => 'algorithm', 'name' => 'SHA-256'],
        ['attribute' => 'algorithm', 'name' => 'X11'],
        ['attribute' => 'coin', 'name' => 'BTC'],
        ['attribute' => 'coin', 'name' => 'Kaspa'],
        ['attribute' => 'coin', 'name' => 'LTC'],
        ['attribute' => 'coin', 'name' => 'BCH'],
    ];

    foreach ($parameters as $parameter) {
        Parameter::updateOrCreate(
            ['attribute' => $parameter['attribute']], // Условие поиска
            ['name' => $parameter['name']]          // Данные для обновления или создания
        );
    }
}
}