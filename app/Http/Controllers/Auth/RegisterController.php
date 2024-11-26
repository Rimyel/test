<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Log;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:users', // Добавляем проверку на уникальность имени пользователя
                function ($attribute, $value, $fail) {
                    // Удаляем лишние пробелы и проверяем на запрещенные символы
                    $normalizedName = preg_replace('/\s+/', ' ', trim($value));
                    if (preg_match('/[№@<>]/', $normalizedName)) {
                        $fail('Имя содержит запрещенные символы.');
                    }
                },
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Имя обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать 255 символов.',
            'name.unique' => 'Это имя пользователя уже занято.', 
            'email.required' => 'Email обязателен для заполнения.',
            'email.string' => 'Email должен быть строкой.',
            'email.email' => 'Email должен быть валидным.',
            'email.max' => 'Email не должен превышать 255 символов.',
            'email.unique' => 'Этот email уже занят.',
            'password.required' => 'Пароль обязателен для заполнения.',
            'password.string' => 'Пароль должен быть строкой.',
            'password.min' => 'Пароль должен содержать не менее 8 символов.',
            'password.confirmed' => 'Подтверждение пароля не совпадает.',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            Log::error('Validation failed: ' . $validator->errors()->toJson()); // Логирование ошибок валидации
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = $this->create($request->all());

        return redirect()->route('home')->with('success', 'Регистрация прошла успешно.');
    }

}
