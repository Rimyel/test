<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poster;
use App\Models\Comment;
use App\Models\Like;
use App\Models\View;
use App\Models\User;
use App\Models\Analytic;
use App\Models\Parameter; // Используем модель Parameter
use Closure;
use Illuminate\Support\Facades\DB;
use Auth;

class HomeController extends Controller
{
    // Личный кабинет
    public function index()
    {
        $user = Auth::user(); // Получаем текущего пользователя
        $like = Like::with('poster')->where('user_id', $user->id)->get();
        $consultations = $user->contactRequests()->latest()->get();
        $repairs = $user->repairRequests()->latest()->get(); // Добавляем коллекцию ремонтов

        return view('home', [
            'like' => $like,
            'consultations' => $consultations,
            'repairs' => $repairs, // Передаем в представление
        ]);
    }

    public function authenticated(Request $request, $user)
    {
        $user->update([
            'last_login_at' => now(),
        ]);
    }

    // Показ главной страницы
    public function welcome()
    {
        $posts = Poster::where('visibility', 1)->get();
        return view('welcome', ['posts' => $posts]);
    }

    // Показ деталей постера (майнера)
    public function post($post_id)
    {
        $poster = Poster::findOrFail($post_id);

        // Получаем уникальный идентификатор пользователя (ID или IP-адрес)
        $userId = Auth::check() ? Auth::user()->id : request()->ip();

        // Проверяем, был ли уже просмотр
        if (!View::where('poster_id', $poster->id)->where('user_id', $userId)->exists()) {
            // Добавляем новый просмотр
            View::create([
                'poster_id' => $poster->id,
                'user_id' => $userId,
            ]);

            // Увеличиваем счётчик просмотров
            $poster->increment('views');
        }

        // Получаем лайк пользователя для данного постера
        $like = null;
        if (Auth::check()) {
            $like = Like::where('user_id', Auth::user()->id)
                ->where('poster_id', $post_id)
                ->first();
        }

        // Возвращаем представление с данными постера и комментариями
        return view('post', [
            'post' => $poster,
            'comments' => Comment::with(['post', 'user'])->where('poster_id', $post_id)->orderBy('created_at', 'DESC')->get(),
            'like' => $like, // Передаем переменную $like в представление
        ]);
    }

    // Поиск
    public function search(Request $request)
    {
        // Получаем слово для поиска из запроса
        $word = $request->word;

        // Ищем постеры, где имя или описание содержит слово
        $results = Poster::where('name', 'like', "%{$word}%")
            ->where('visibility', '1')
            ->orderBy('id')
            ->get();

        // Возвращаем представление 'search' с найденными постерами
        return view('search', ['posts' => $results]);
    }

    // Страница "Что посмотреть"
    public function see(Request $request)
    {
        // Получаем все параметры из базы данных
        $parameters = Parameter::all();

        // Получаем выбранные параметры из запроса
        $selectedParameters = $request->input('parameters', []);

        // Получаем посты (майнеры) с учетом выбранных параметров
        $posts = Poster::when(!empty($selectedParameters), function ($query) use ($selectedParameters) {
            $query->whereHas('parameters', function ($q) use ($selectedParameters) {
                $q->whereIn('parameters.id', $selectedParameters);
            });
        })->get();

        // Передаем данные в представление
        return view('see', [
            'parameters' => $parameters,
            'selectedParameters' => $selectedParameters,
            'posts' => $posts,
        ]);
    }



    // Добавление нового комментария
    public function new_comment($id, Request $request)
    {
        // Создаем новый комментарий
        Comment::create([
            'user_id' => Auth::user()->id,
            'poster_id' => $id,
            'message' => $request->message,
        ]);

        return redirect()->back();
    }

    // Добавление в избранное (лайк)
    public function add_liked($product_id)
    {
        // Проверяем, авторизован ли пользователь
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Войдите в систему, чтобы добавить в избранное.');
        }

        // Проверяем, добавлен ли в избранное от текущего пользователя к данному постеру
        $like = Like::where('user_id', Auth::user()->id)
            ->where('poster_id', $product_id)
            ->first();

        if ($like) {
            // Если в избранном уже существует, удаляем его
            $like->delete();
            return redirect()->back()->with('success', 'Майнер удален из избранного.');
        } else {
            // Если в избранном нет, добавляем в избранное
            Like::create([
                'user_id' => Auth::user()->id,
                'poster_id' => $product_id,
            ]);
            return redirect()->back()->with('success', 'Майнер добавлен в избранное.');
        }
    }

    // Удаление из избранного
    public function removeFromFavorites(Request $request, $like_id)
    {
        $like = Like::findOrFail($like_id);

        // Проверяем, что лайк принадлежит текущему пользователю
        if ($like->user_id == Auth::user()->id) {
            $like->delete();
            return redirect()->back()->with('success', 'Фильм удален из избранного.');
        } else {
            return redirect()->back()->with('error', 'У вас нет прав на удаление этого фильма из избранного.');
        }
    }



    // Показ деталей постера
    public function show($poster_id)
    {
        $poster = Poster::findOrFail($poster_id);
        return view('post', compact('poster'));
    }


    // Обработка аналитики
    public function handle(Request $request, Closure $next)
    {
        // Регистрируем событие просмотра страницы
        Analytic::create([
            'event_type' => 'page_view',
            'url' => $request->fullUrl(),
            'user_id' => auth()->id(),
        ]);

        return $next($request);
    }
}