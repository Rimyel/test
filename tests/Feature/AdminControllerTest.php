<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\models\Poster;
use App\models\Genre;
use App\Models\User;

use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\DB;
class AdminControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testIndex()
    {
        // Создаем администратора
        $admin = User::factory()->create(['is_admin' => true]);

        // Создаем несколько постеров и жанров
        $posters = Poster::factory()->count(16)->create();
        $genres = Genre::factory()->count(3)->create();

        // Выполняем запрос к методу index с авторизацией администратора
        $response = $this->actingAs($admin)->get('/admin');

        // Проверяем, что ответ имеет статус 200
        $response->assertStatus(200);

        // Проверяем, что представление содержит переданные данные
        $response->assertViewHas('posters', $posters);
        $response->assertViewHas('genres', $genres);
    }
    public function testNewPoster()
    {
        // Создаем администратора
        $admin = User::factory()->create(['is_admin' => true]);

        // Создаем жанры
        $genres = Genre::factory()->count(3)->create();

        // Данные для создания нового постера
        $posterData = [
            'name' => 'Test Poster',
            'description' => 'Test Description',
            'photo' => UploadedFile::fake()->image('poster.jpg'),
            'genres' => $genres->pluck('id')->implode(','),
        ];

        // Выполняем запрос к методу new_poster с авторизацией администратора
        $response = $this->actingAs($admin)->post('/admin/new_poster', $posterData);

        // Проверяем, что ответ имеет статус 302 (перенаправление)
        $response->assertStatus(302);

        // Проверяем, что постер был создан в базе данных
        $this->assertDatabaseHas('posters', [
            'name' => 'Test Poster',
            'description' => 'Test Description',
        ]);

        // Проверяем, что жанры были привязаны к постеру
        $poster = Poster::where('name', 'Test Poster')->first();
        $this->assertCount(3, $poster->genres);
    }
    public function testHidePoster()
    {
        // Создаем администратора
        $admin = User::factory()->create(['is_admin' => true]);

        // Создаем постер
        $poster = Poster::factory()->create(['visibility' => 1]);

        // Выполняем запрос к методу hide с авторизацией администратора
        $response = $this->actingAs($admin)->get("/post/post/{$poster->id}/hide");

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

    public function testUsers()
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
        $users = User::factory()->count(25)->create(); // Создаем 25 пользователей, так как администратор уже создан

        // Выполняем запрос к методу users с авторизацией администратора
        $response = $this->actingAs($admin)->get('/users');

        // Проверяем, что ответ имеет статус 200
        $response->assertStatus(200);

        // Проверяем, что представление содержит ключ 'users'
        $response->assertViewHas('users');

        // Получаем пользователей из представления
        $viewUsers = $response->viewData('users');

        // Проверяем количество пользователей в представлении
        $this->assertCount(26, $viewUsers); // Ожидаем 26 пользователей (25 + 1 администратор)
    }
    public function testToggleBlockUser()
    {
        // Создаем администратора
        $admin = User::factory()->create(['is_admin' => true]);

        // Создаем пользователя
        $user = User::factory()->create(['blocked' => false]);

        // Выполняем запрос к методу toggleBlockUser с авторизацией администратора
        $response = $this->actingAs($admin)->put("/users/{$user->id}/toggle-block");

        // Проверяем, что ответ имеет статус 302 (перенаправление)
        $response->assertStatus(302);

        // Проверяем, что пользователь был заблокирован (blocked = true)
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'blocked' => true,
        ]);

        // Выполняем запрос к методу toggleBlockUser еще раз
        $response = $this->actingAs($admin)->put("/users/{$user->id}/toggle-block");

        // Проверяем, что пользователь был разблокирован (blocked = false)
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'blocked' => false,
        ]);
    }
}
