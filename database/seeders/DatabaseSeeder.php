<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Poster;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'root',
        //     'email' => 'root@gmail.com',
        //     'is_admin' => 1,
        //     'password' => Hash::make('rootroot'),
        // ]);
        
        // $faker = Faker::create();

        // for ($i = 1800; $i < 2000; $i++) {
        //     $created_at = $faker; // Генерируем случайное значение видимости

        //     Poster::create([
        //         'created_at' =>$created_at
        //     ]);
        // }
        $this->call(ParameterSeeder::class);
        
    }
    
}
