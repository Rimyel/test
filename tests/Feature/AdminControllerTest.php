<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\models\Poster;
use App\models\Comment;
use App\Models\User;
use App\Models\Parameter;


use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\DB;
class AdminControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testIndex()
    {
        // Отключаем проверку внешних ключей
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Очищаем таблицы перед тестом
        Poster::truncate();
        Comment::truncate(); // Также очищаем таблицу comments
        User::truncate();

        // Включаем проверку внешних ключей обратно
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Создаем администратора
        $admin = User::factory()->create(['is_admin' => true]);

        // Создаем несколько постеров 
        $posters = Poster::factory()->count(16)->create();

        // Выполняем запрос к методу index с авторизацией администратора
        $response = $this->actingAs($admin)->get('/admin');

        // Проверяем, что ответ имеет статус 200
        $response->assertStatus(200);

        // Проверяем, что представление содержит переданные данные
        $response->assertViewHas('posters', function ($viewPosters) use ($posters) {
            return $viewPosters->count() === $posters->count();
        });
    }
    public function testNewPoster()
    {
        // Отключаем проверку внешних ключей
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Очищаем таблицы перед тестом
        Poster::truncate();
        Comment::truncate(); // Также очищаем таблицу comments
        User::truncate();
        Parameter::truncate();

        // Включаем проверку внешних ключей обратно
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Создаем администратора (для авторизации)
        $admin = User::factory()->create(['is_admin' => 1]);

        // Генерируем уникальное название для постера
        $uniqueName = 'Test Poster ' . now()->timestamp;

        // Данные для создания нового постера
        $posterData = [
            'name' => $uniqueName,
            'description' => 'Test Description',
            'photo' => UploadedFile::fake()->image('poster.jpg'), // Фиктивное изображение
            'manufacturer' => Parameter::factory()->create()->id, // Создаем производителя
            'algorithm' => Parameter::factory()->create()->id,   // Создаем алгоритм
            'coin' => Parameter::factory()->create()->id,        // Создаем монету
        ];

        // Выполняем запрос к методу new_poster с авторизацией администратора
        $response = $this->actingAs($admin)->post('/admin/new_poster', $posterData);

        // Проверяем, что ответ имеет статус 302 (перенаправление)
        $response->assertStatus(302);

        // Проверяем, что постер был создан в базе данных
        $this->assertDatabaseHas('posters', [
            'name' => $uniqueName,
            'description' => 'Test Description',
        ]);
    }
    public function testHidePoster()
    {
        // Создаем администратора
        $admin = User::factory()->create(['is_admin' => true]);

        // Создаем постер
        $poster = Poster::factory()->create(['visibility' => 1]);

        // Выполняем запрос к методу hide с авторизацией администратора
        $response = $this->actingAs($admin)->get("/post/{$poster->id}/hide");

        // Проверяем, что ответ имеет статус 302 (перенаправление)
        $response->assertStatus(302);

        // Проверяем, что постер был скрыт (visibility = 0)
        $this->assertDatabaseHas('posters', [
            'id' => $poster->id,
            'visibility' => 0,
        ]);
    }
    public function testRestorePoster()
    {
        // Создаем администратора
        $admin = User::factory()->create(['is_admin' => true]);

        // Создаем скрытый постер
        $poster = Poster::factory()->create(['visibility' => 0]);

        // Выполняем запрос к методу restore с авторизацией администратора
        $response = $this->actingAs($admin)->get("/post/restore/{$poster->id}");

        // Проверяем, что ответ имеет статус 302 (перенаправление)
        $response->assertStatus(302);

        // Проверяем, что постер был восстановлен (visibility = 1)
        $this->assertDatabaseHas('posters', [
            'id' => $poster->id,
            'visibility' => 1,
        ]);
    }
    public function testSaveEditPoster()
    {
        // Создаем администратора
        $admin = User::factory()->create(['is_admin' => true]);

        // Создаем постер
        $poster = Poster::factory()->create();

        // Данные для обновления постера
        $updatedData = [
            'name' => 'Updated Poster Name',
            'description' => 'Updated Description',
        ];

        // Выполняем запрос к методу save_edit с авторизацией администратора
        $response = $this->actingAs($admin)->post("/admin/save_edit/{$poster->id}", $updatedData);

        // Проверяем, что ответ имеет статус 302 (перенаправление)
        $response->assertStatus(302);

        // Проверяем, что постер был обновлен в базе данных
        $this->assertDatabaseHas('posters', [
            'id' => $poster->id,
            'name' => 'Updated Poster Name',
            'description' => 'Updated Description',
        ]);
    }

    public function testUsersCount()
    {
        // Отключаем проверку внешних ключей
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Очищаем базу данных перед тестом
        User::truncate();

        // Включаем проверку внешних ключей
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Создаем администратора
        $admin = User::factory()->create(['is_admin' => true]);

        // Создаем несколько пользователей
        User::factory()->count(25)->create(); // Создаем 25 пользователей, плюс администратор уже создан

        // Проверяем количество пользователей в базе данных
        $usersCount = User::all()->count();

        // Ожидаем 26 пользователей (25 + 1 администратор)
        $this->assertEquals(26, $usersCount);
    }
    
}
