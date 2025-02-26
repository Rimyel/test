<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poster;
use App\Models\Parameter; // Используем модель Parameter вместо Genre
use App\Models\User;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\Analytic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Отображение списка постеров
    public function index()
    {
        $parameters = Parameter::all(); // Получаем все параметры
        $posters = Poster::orderBy('created_at', 'DESC')->get();
        return view('adminPanel', ['posters' => $posters], compact('parameters'));
    }

    // Форма создания постера
    public function showForm()
    {
        $parameters = Parameter::all(); // Получаем все параметры
        return view('posters.create', compact('parameters'));
    }

    // Создание нового постера
    public function new_poster(Request $request)
    {
        // Валидация входящих данных
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $normalizedName = preg_replace('/\s+/', ' ', trim(strtolower($value)));
                    $existingPoster = Poster::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [str_replace(' ', '', $normalizedName)])->first();
                    if ($existingPoster) {
                        $fail('Постер с таким названием уже существует.');
                    }
                },
            ],
            'description' => 'required|string',
            'photo' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
            'manufacturer' => 'required|exists:parameters,id', // Производитель
            'algorithm' => 'required|exists:parameters,id',   // Алгоритм
            'coin' => 'required|exists:parameters,id',        // Монета
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
            // Привязываем параметры к постеру
            $poster->parameters()->sync([
                $request->manufacturer, // Производитель
                $request->algorithm,   // Алгоритм
                $request->coin,        // Монета
            ]);
            return redirect()->back()->with('success', 'Постер успешно создан');
        } else {
            return redirect()->back()->withErrors(['error' => 'Такой постер уже существует']);
        }
    }

    // Скрытие постера
    public function hide($id)
    {
        $post = Poster::find($id);

        if (!$post) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $post->visibility = 0;
        $post->save();

        return redirect()->back();
    }

    // Восстановление постера
    public function restore($id)
    {
        $post = Poster::find($id);

        if (!$post) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $post->visibility = 1;
        $post->save();

        return redirect()->back();
    }

    // Форма редактирования постера
    public function edit_poster($post_id)
    {
        $poster = Poster::where('id', $post_id)->first();
        $parameters = Parameter::all(); // Получаем все параметры
        return view('editPost', ['poster' => $poster, 'parameters' => $parameters]);
    }

    // Сохранение изменений постера
    public function save_edit($poster_id, Request $request)
    {
        // Валидация входящих данных
        $request->validate([
            'name' => [
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($poster_id) {
                    $normalizedName = preg_replace('/\s+/', ' ', trim(strtolower($value)));
                    $existingPoster = Poster::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [str_replace(' ', '', $normalizedName)])
                        ->where('id', '!=', $poster_id)
                        ->first();

                    if ($existingPoster) {
                        $fail('Постер с таким названием уже существует.');
                    }
                },
            ],
            'description' => 'string',
            'manufacturer' => 'required|exists:parameters,id', // Производитель
            'algorithm' => 'required|exists:parameters,id',     // Алгоритм
            'coin' => 'required|exists:parameters,id',          // Монета
        ]);

        // Находим постер по ID
        $poster = Poster::find($poster_id);

        if (!$poster) {
            return redirect()->back()->with('error', 'Постер не найден.');
        }

        // Обновляем поля
        if ($request->has('name')) {
            $poster->name = $request->name;
        }

        if ($request->has('description')) {
            $poster->description = $request->description;
        }

        $poster->save();

        // Обновляем параметры
        $poster->parameters()->sync([
            $request->manufacturer, // Производитель
            $request->algorithm,   // Алгоритм
            $request->coin,        // Монета
        ]);

        return redirect()->back()->with('success', 'Постер успешно обновлён.');
    }

    // Аналитика
    public function stat()
    {
        $totalPosts = Poster::count();
        $totalComments = Comment::count();
        $totalUsers = User::count();
        $totalViews = Poster::sum('views');
        $averageRating = Rating::avg('rank');

        $postsByMonth = $this->getPostsByMonth();
        $commentsByMonth = $this->getCommentsByMonth();
        $analyticsData = $this->getAnalyticsData();
        $lastLoginByDay = $this->getLastLoginByDay();

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

   
}
