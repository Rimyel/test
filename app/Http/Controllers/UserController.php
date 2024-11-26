<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    /**
     * Отображение формы редактирования пользователя.
     *
     * @param  int  $id
     * @return Response|Factory|View
     */
    public function edit($id)
    {
        $user = User::with(['likes.poster', 'comments.poster'])->findOrFail($id);
        return view('UsersEdit', compact('user'));
    }

    /**
     * Обновление данных пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response|RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Валидация входящих данных
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:users',
                function ($attribute, $value, $fail) {
                    // Удаляем лишние пробелы и проверяем на запрещенные символы
                    $normalizedName = preg_replace('/\s+/', ' ', trim($value));
                    if (preg_match('/[№@<>]/', $normalizedName)) {
                        $fail('Имя содержит запрещенные символы.');
                    }
                },
            ],
            'email' => 'required|string|email|max:100|unique:users,email,' . $id,
        ], [
            'name.required' => 'Имя обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.unique' => 'Это имя пользователя уже занято.', 
            'name.max' => 'Имя не должно превышать 100 символов.',
            'email.required' => 'Email обязателен для заполнения.',
            'email.string' => 'Email должен быть строкой.',
            'email.email' => 'Email должен быть валидным.',
            'email.max' => 'Email не должен превышать 255 символов.',
            'email.unique' => 'Этот email уже занят.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->back()->with('success', 'Пользователь успешно обновлен');
    }

    /**
     * Удаление комментария.
     *
     * @param  int  $id
     * @return Response|RedirectResponse
     */
    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Комментарий успешно удален');
    }
}