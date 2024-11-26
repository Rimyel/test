<?php

namespace Database\Factories;

use App\Models\Poster;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Poster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'visibility' => 1,
            'views' => $this->faker->numberBetween(0, 1000),
            'image' => $this->faker->imageUrl(), // Добавляем значение для столбца image
        ];
    }
}

