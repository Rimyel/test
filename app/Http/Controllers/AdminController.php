<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Poster;
use App\models\Genre;
use App\Models\User;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\Analytic;
use Carbon\Carbon;
use App\Models\GenrePoster;
use Illuminate\Support\Facades\DB;
class AdminController extends Controller
{
    // создание поста
    public function index()
    {
        $genres = Genre::all();
        $posters = Poster::orderBy('created_at', 'DESC')->get();
        return view('adminPanel', ['posters' => $posters], compact('genres'));
    }

    public function showForm()
    {
        $genres = Genre::all(); // Получаем все жанры
        return view('posters.create', compact('genres'));
    }
    public function new_poster(Request $request)
    {
        // Валидация входящих данных
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Удаляем лишние пробелы и приводим к нижнему регистру
                    $normalizedName = preg_replace('/\s+/', ' ', trim(strtolower($value)));

                    // Проверяем, существует ли уже постер с таким названием
                    $existingPoster = Poster::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [str_replace(' ', '', $normalizedName)])->first();

                    if ($existingPoster) {
                        $fail('Постер с таким названием уже существует.');
                    }
                },
            ],
            'description' => 'required|string',
            'photo' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
            'genres' => 'required',
        ]);

        // Сохраняем изображение
        $name = time() . "." . $request->photo->extension();
        $destination = 'public/';
        $path = $request->photo->storeAs($destination, $name);

        // Подготавливаем данные для создания постера
        $info = [
            'name' => $request->name,
            'description' => $request->description,
            'image' => 'storage/' . $name,
        ];

        // Создаем или получаем существующий постер
        $poster = Poster::firstOrCreate(['name' => $info['name']], $info);

        if ($poster->wasRecentlyCreated) {
            // Преобразуем строку жанров в массив
            $genresArray = explode(',', $request->genres);
            $poster->genres()->sync($genresArray);
            return redirect()->back()->with('success', 'Постер успешно создан');
        } else {
            return redirect()->back()->withErrors(['error' => 'Такой постер уже существует']);
        }
    }
    // Функция скрытия 
    public function hide($id)
    {
        $post = Poster::find($id);

        if (!$post) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Устанавливаем visibility в 0
        $post->visibility = 0;
        $post->save();

        return redirect()->back();
    }
    // Функция востановления
    public function restore($id)
    {
        $post = Poster::find($id);

        if (!$post) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Устанавливаем visibility в 1
        $post->visibility = 1;
        $post->save();

        return redirect()->back();
    }
    // Редактирование постера
    // Редактирование поста
    public function edit_poster($post_id)
    {
        $poster = Poster::where('id', $post_id)->first();
        $genres = Genre::all(); // Получаем все жанры для выпадающего списка
        return view('editPost', ['poster' => $poster, 'genres' => $genres]);
    }

    // Сохранение изменений
    public function save_edit($poster_id, Request $request)
    {
        // Валидация входящих данных
        $request->validate([
            'name' => [
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($poster_id) {
                    // Удаляем лишние пробелы и приводим к нижнему регистру
                    $normalizedName = preg_replace('/\s+/', ' ', trim(strtolower($value)));

                    // Проверяем, существует ли уже постер с таким названием
                    $existingPoster = Poster::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [str_replace(' ', '', $normalizedName)])
                        ->where('id', '!=', $poster_id)
                        ->first();

                    if ($existingPoster) {
                        $fail('Постер с таким названием уже существует.');
                    }
                },
            ],
            'description' => 'string',
            'genres' => 'required|array', // Валидация жанра
            'genres.*' => 'exists:genres,id', // Валидация каждого жанра
        ], [
            'genres.required' => 'Выберите хотя бы один жанр.', // Добавляем сообщение об ошибке для жанра
        ]);

        // Находим постер по ID
        $poster = Poster::find($poster_id);

        // Проверяем, существует ли постер
        if (!$poster) {
            return redirect()->back()->with('error', 'Постер не найден.');
        }

        // Обновляем поля только если они присутствуют в запросе
        if ($request->has('name')) {
            $poster->name = $request->name;
        }

        if ($request->has('description')) {
            $poster->description = $request->description;
        }

        // Сохраняем изменения
        $poster->save();

        // Обновляем жанры
        $genreIds = $request->input('genres');
        $poster->genres()->sync($genreIds);

        // Возвращаемся на предыдущую страницу с сообщением об успехе
        return redirect()->back()->with('success', 'Постер успешно обновлён.');
    }
    //контроллер Аналитики
    public function stat()
    {
        $totalPosts = Poster::count();
        $totalComments = Comment::count();
        $totalUsers = User::count();
        $totalViews = Poster::sum('views');
        $averageRating = Rating::avg('rank');

        // Данные для графиков
        $postsByMonth = $this->getPostsByMonth();
        $commentsByMonth = $this->getCommentsByMonth();
        $analyticsData = $this->getAnalyticsData();
        $lastLoginByDay = $this->getLastLoginByDay();

        // Проверка данных
        if (empty($postsByMonth['labels']) || empty($postsByMonth['data'])) {
            $postsByMonth = ['labels' => [], 'data' => []];
        }

        if (empty($commentsByMonth['labels']) || empty($commentsByMonth['data'])) {
            $commentsByMonth = ['labels' => [], 'data' => []];
        }

        if (empty($analyticsData['pageViews']) && empty($analyticsData['linkClicks']) && empty($analyticsData['timeOnSite'])) {
            $analyticsData = ['pageViews' => 0, 'linkClicks' => 0, 'timeOnSite' => 0];
        }

        return view('stat', compact(
            'totalPosts',
            'totalComments',
            'totalUsers',
            'totalViews',
            'averageRating',
            'postsByMonth',
            'commentsByMonth',
            'analyticsData',
            'lastLoginByDay'
        ));
    }
    // посты за месяц
    private function getPostsByMonth()
    {
        $posts = Poster::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $posts->pluck('month'),
            'data' => $posts->pluck('count')
        ];
    }
    // коменты за месяц
    private function getCommentsByMonth()
    {
        $comments = Comment::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $comments->pluck('month'),
            'data' => $comments->pluck('count')
        ];
    }
    // Аналитика просмотров
    private function getAnalyticsData()
    {
        // Группируем просмотры по датам
        $pageViewsByDate = Analytic::where('event_type', 'page_view')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $pageViews = $pageViewsByDate->sum('views'); // Общее количество просмотров
        $linkClicks = Analytic::where('event_type', 'link_click')->count();
        $timeOnSite = Analytic::where('event_type', 'time_on_site')->count();

        // Преобразуем данные для графиков
        $pageViewsData = $pageViewsByDate->pluck('views')->toArray();
        $pageViewsLabels = $pageViewsByDate->pluck('date')->toArray();

        return [
            'pageViews' => $pageViews,
            'pageViewsData' => $pageViewsData,
            'pageViewsLabels' => $pageViewsLabels,
            'linkClicks' => $linkClicks,
            'timeOnSite' => $timeOnSite
        ];
    }
    // Контроллер функции последнего входа
    private function getLastLoginByDay()
    {
        $lastLoginByDay = User::select(
            DB::raw('DATE(last_login_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('last_login_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $lastLoginByDayLabels = [];
        $lastLoginByDayData = [];

        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $lastLoginByDayLabels[] = $date;
            $lastLoginByDayData[] = $lastLoginByDay->firstWhere('date', $date)->count ?? 0;
        }

        return [
            'labels' => array_reverse($lastLoginByDayLabels),
            'data' => array_reverse($lastLoginByDayData),
        ];
    }
    public function users(Request $request)
    {
        $query = User::query();

        // Поиск по имени, email или ID
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('id', 'like', "%$search%");
            });
        }

        // Сортировка
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $direction = $request->input('direction', 'asc');
            $query->orderBy($sort, $direction);
        }

        $users = $query->with(['comments', 'likes'])->get();

        return view('users', compact('users'));
    }

    public function toggleBlockUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users')->with('error', 'Пользователь не найден');
        }

        // Переключаем статус блокировки
        $user->blocked = !$user->blocked;
        $user->save();

        // Определяем сообщение в зависимости от действия
        $message = $user->blocked ? 'Пользователь успешно заблокирован' : 'Пользователь успешно разблокирован';

        return redirect()->route('users')->with('success', $message);
    }
    public function showUserDetails($id)
    {
        $user = User::findOrFail($id);
        // Получение дополнительной информации о пользователе (комментарии, избранные фильмы и т.д.)
        return view('admin.user_details', compact('user'));
    }


    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'User updated successfully']);
    }
}
