<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poster;
use App\Models\Comment;
use App\Models\Like;
use App\Models\View;
use App\Models\User;
use App\Models\Rating;
use App\Models\Analytic;
use Closure;
use Illuminate\Support\Facades\DB;
use Auth;

class HomeController extends Controller
{
    // Личный кабинет
    public function index()
    {
        $like = Like::with('poster')->where('user_id', Auth::user()->id)->get();
        // dd($like);
        return view('home', ['like' => $like]);
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



    public function post($post_id)
    {
        $poster = Poster::findOrFail($post_id);
        // Получаем постер по ID


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
        $like = Like::where('user_id', Auth::user()->id)->where('poster_id', $post_id)->first();

        $userRating = $this->getUserRating($post_id); // Получаем текущую оценку пользователя
        $ratings = $poster->ratings()->with('user')->get();
        $averageRating = $poster->ratings()->avg('rank');

        // Возвращаем представление с данными постера и комментариями
        return view('post', [
            'post' => $poster, // Используем уже полученный объект $poster
            //Модель коммент получает из моделей пост и юзер их id, фильтрует (orderBy) от новых к старым
            'comments' => Comment::with(['post', 'user'])->where('poster_id', $post_id)->orderBy('created_at', 'DESC')->get(),
            'userRating' => $userRating,
            'ratings' => $ratings,
            'averageRating' => $averageRating,
            'like' => $like,
        ]);
    }



    // Поиск
    public function search(Request $request)
    {
        // Получаем слово для поиска из запроса
        $word = $request->word;

        // Ищем постеры, где имя или описание содержит слово
        // Результаты сортируются по ID в порядке возрастания
        $results = Poster::where('name', 'like', "%{$word}%")
            ->Where('visibility', '1')
            ->orderBy('id')
            ->get();

        // Возвращаем представление 'search' с найденными постерами
        return view('search', ['posts' => $results]);
    }

    // Страница Что посмотреть
    public function see(Request $request)
    {
        // Получаем все жанры
        $genres = Genre::pluck('name')->unique()->values()->all();

        // Получаем выбранные жанры из запроса и преобразуем их в массив
        $selectedGenres = is_string($request->input('genres')) ? explode(',', $request->input('genres')) : $request->input('genres', []);

        // Если выбраны жанры, фильтруем по ним, иначе выбираем случайные постеры
        if (!empty($selectedGenres)) {
            $posts = Poster::whereHas('genres', function ($query) use ($selectedGenres) {
                $query->whereIn('name', $selectedGenres);
            })->inRandomOrder()->limit(15)->get();
        } else {
            $posts = Poster::inRandomOrder()->limit(15)->get();
        }

        // Возвращаем представление 'see' с полученными постерами и жанрами
        return view('see', [
            'posts' => $posts,
            'genres' => $genres,
            'selectedGenres' => $selectedGenres, // Передаем выбранные жанры
        ]);
    }
    // Страница Рейтинг
    public function rating(Request $request)
    {
        // Определяем сортировку
        $sortBy = $request->input('sort', 'views'); // По умолчанию сортируем по просмотрам

        // Получаем постеры в зависимости от выбранной сортировки
        if ($sortBy === 'rank') {
            $posts = Poster::where('visibility', 1)
                ->withAvg('ratings', 'rank') // Предполагаем, что у вас есть связь с рейтингами
                ->orderBy('ratings_avg_rank', 'desc') // Сортируем по среднему рейтингу
                ->limit(10)
                ->get();
        } else {
            $posts = Poster::where('visibility', 1)
                ->orderBy('views', 'desc') // Сортируем по просмотрам
                ->limit(10)
                ->get();
        }

        return view('rating', ['posts' => $posts, 'sortBy' => $sortBy]);
    }

    // Добавление нового комментария
    public function new_comment($id, Request $request)
    {
        // Создаем новый комментарий с ID пользователя, ID постера и текстом сообщения
        Comment::create([
            'user_id' => Auth::user()->id, // Получаем ID текущего аутентифицированного пользователя
            'poster_id' => $id,             // ID постера, к которому добавляется комментарий
            'message' => $request->message  // Сообщение из инпута
        ]);


        return redirect()->back();
    }

    // Добавление в избранное (лайк)
    public function add_liked($product_id)
    {
        // Проверяем, добавлен ли в избранное от текущего пользователя к данному постеру
        $status = Like::where('user_id', Auth::user()->id)
            ->where('poster_id', $product_id)
            ->first();

        if ($status) {
            // Если в избранном уже существует, удаляем его
            Like::where('id', $status->id)->delete();
        } else {
            // Если в избранном нет, добавляем в избранное с ID пользователя и постера
            $data = [
                'user_id' => Auth::user()->id,
                'poster_id' => $product_id,
            ];
            Like::create($data);
        }

        return redirect()->back();
    }
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
    public function store(Request $request, $poster_id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'poster_id' => 'required|integer',
            'rank' => 'required|integer',
            'rating' => 'required|integer', // Добавьте валидацию для поля rating
        ]);

        $request->validate([
            'rank' => 'required|integer|min:1|max:10',
        ]);

        $rank = Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'poster_id' => $poster_id],
            [
                'rank' => $request->rank,
                'rating' => $request->rating, // Добавьте значение для поля rating
            ]
        );

        return redirect()->back()->with('success', 'Ваша оценка сохранена.');
    }

    public function show($poster_id)
    {
        $poster = Poster::findOrFail($poster_id);
        // Передаем переменные в представление post.blade.php
        return view('post', compact('poster'));
    }

    private function getUserRating($poster_id)
    {
        if (Auth::check()) {
            $userRating = Rating::where('user_id', Auth::id())
                ->where('poster_id', $poster_id)
                ->first();
            return $userRating ? $userRating->rating : null;
        }
        return null;
    }
    public function handle(Request $request, Closure $next)
    {
        // Регистрируем событие просмотра страницы
        Analytic::create([
            'event_type' => 'page_view',
            'url' => $request->fullUrl(),
            'user_id' => auth()->id(), // Если пользователь авторизован
        ]);

        return $next($request);
    }
}