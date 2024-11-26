<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="flex w-4/5 max-w-4xl h-96 shadow-lg">
            <div class="flex flex-col justify-center w-1/2 p-10 bg-black text-white">
                <h1 class="text-4xl font-bold">Библиотека</h1>
                <p class="mt-4">Приглашаем вас прочиать новую книгу</p>
                <a href="{{ route('login') }}">
                    <button class="px-4 py-2 mt-6 text-black bg-white rounded">Войти</button>
                </a>
            </div>
            <div class="flex flex-col justify-center w-1/2 p-10 bg-white">
                <h2 class="text-3xl font-bold">Регистрация</h2>
                <p class="mt-2">Открой справочник книг</p>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-4">
                        <input id="name" placeholder="Имя" type="text" class="w-full px-4 py-2 border rounded form-control @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="text-red-500 text-sm" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <input id="email" type="email" placeholder="Email" class="w-full px-4 py-2 border rounded form-control @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="text-red-500 text-sm" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <input id="password" type="password" placeholder="Пароль" class="w-full px-4 py-2 border rounded form-control @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                            <span class="text-red-500 text-sm" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <input id="password-confirm" type="password" placeholder="Подтвердите пароль" class="w-full px-4 py-2 border rounded form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="w-full px-4 py-2 text-white bg-black rounded">
                            {{ __('Зарегистрироваться') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>