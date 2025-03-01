<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация | Криптомир</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <div class="p-8">
                <!-- Заголовок -->
                <div class="mb-8 text-center">
                    <h1 class="text-4xl font-bold text-blue-600 mb-2 transform hover:scale-105 transition-transform">
                        <i class=" mr-2"></i>Криптомир
                    </h1>
                    <p class="text-gray-600">Создайте аккаунт для доступа к справочнику</p>
                </div>

                <!-- Форма -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Поле Имя -->
                    <div class="relative">
                        <input id="name" type="text" name="name" value="{{ old('name') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Имя" required autocomplete="name" autofocus>
                        <i class="fas fa-user absolute right-4 top-4 text-gray-400"></i>
                        @error('name')
                            <span class="text-red-500 text-sm mt-1 block">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Поле Email -->
                    <div class="relative">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Email" required autocomplete="email">
                        <i class="fas fa-envelope absolute right-4 top-4 text-gray-400"></i>
                        @error('email')
                            <span class="text-red-500 text-sm mt-1 block">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Поле Пароль -->
                    <div class="relative">
                        <input id="password" type="password" name="password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Пароль" required autocomplete="new-password">
                        <i class="fas fa-lock absolute right-4 top-4 text-gray-400"></i>
                        @error('password')
                            <span class="text-red-500 text-sm mt-1 block">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Подтверждение пароля -->
                    <div class="relative">
                        <input id="password-confirm" type="password" name="password_confirmation" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Подтвердите пароль" required autocomplete="new-password">
                        <i class="fas fa-check-circle absolute right-4 top-4 text-gray-400"></i>
                    </div>

                    <!-- Кнопка регистрации -->
                    <button type="submit" 
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300 transform hover:scale-[1.02]">
                        <i class="fas fa-user-plus mr-2"></i>Зарегистрироваться
                    </button>
                </form>

                <!-- Ссылка на вход -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">Уже есть аккаунт?
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors">
                            Войти здесь
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>