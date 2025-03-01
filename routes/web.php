<?php
use App\Http\Controllers\ExportController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'welcome'])->name('welcome');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth'])->name('home');
Route::get('/see/what', [App\Http\Controllers\HomeController::class, 'see'])->name('see');
Route::get('/rating', [App\Http\Controllers\HomeController::class, 'rating'])->name('rating');

Route::get('/post/{post_id}', [App\Http\Controllers\HomeController::class, 'post'])->middleware(['auth'])->name('Post');
Route::post('/rate/{post_id}', [App\Http\Controllers\RatingController::class, 'store'])->middleware(['auth'])->name('rate');
Route::get('/post/{post_id}/ratings', [App\Http\Controllers\RatingController::class, 'show'])->name('post.ratings');

Route::middleware([IsAdmin::class])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        // Админка
        Route::get('/admin', 'index')->name('admin');
        // Статистика
        Route::get('/stats', 'stat')->name('stat');

        // Маршруты для управления пользователями
        Route::get('/users', 'users')->name('users'); // Страница управления клиентами

        Route::put('/users/{id}/toggle-block', 'toggleBlockUser')->name('users.toggleBlock'); // Заблокировать клиента

        // Маршруты для управления постами
        Route::get('/post/{post_id}/hide', 'hide')->name('posthide'); // Скрыть постер
        Route::get('/post/restore/{id}', 'restore')->name('postrestore'); // Показать постер
        Route::post('/admin/new_poster', 'new_poster')->name('NewPoster'); // Создать новый постер
        Route::get('/new_poster', 'showForm')->name('NewPosterForm'); // Модальное окно для создания постера
        Route::get('/admin/edit_poster/{post_id}', 'edit_poster')->name('editPosts'); // Редактирование постера
        Route::post('/admin/save_edit/{poster_id}', 'save_edit')->name('save_posts'); // Сохранить изменения постера
        Route::get('/admin/requests', 'showRequests')->name('admin.requests');
        
    });
});

Route::post('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('Search');
Route::post('/video/{id}/newComment', [App\Http\Controllers\HomeController::class, 'new_comment'])->name('newComment');
Route::delete('/remove-from-favorites/{like_id}', [App\Http\Controllers\HomeController::class, 'removeFromFavorites'])->name('removeFromFavorites');

Route::get('/users/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('UsersEdit');
Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('UsersUpdate');
Route::delete('/comments/{id}', [App\Http\Controllers\UserController::class, 'destroyComment'])->name('comments.destroy');


Route::post('/liked/add/{product_id}', [App\Http\Controllers\HomeController::class, 'add_liked'])->name('ToLike')->middleware(['auth', 'verified']);
Route::get('login/yandex', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'yandex'])->name('yandex');
Route::get('login/yandex/redirect', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'yandexRedirect'])->name('yandexRedirect');

Route::patch('/repair/{id}/status', [App\Http\Controllers\RepairController::class, 'updateStatus'])->name('repair.update-status');

Route::post('/contact-request', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.request');
Route::post('/repair-request', [App\Http\Controllers\RepairController::class, 'store'])->name('repair.request');
Route::get('/export/word', [ExportController::class, 'exportWord'])->name('export.word');
Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');