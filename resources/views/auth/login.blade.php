@extends('layouts.auth')
@section('title') Вход @endsection

@section('content')
<body class="bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <div class="p-8">
                <!-- Заголовок -->
                <div class="mb-8 text-center">
                    <h1 class="text-4xl font-bold text-blue-600 mb-2 transform hover:scale-105 transition-transform">
                        <i class="fas fa-book-open mr-2"></i>Криптомир
                    </h1>
                    <p class="text-gray-600">Добро пожаловать назад!</p>
                </div>

                <!-- Форма -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Поле Email -->
                    <div class="relative">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Email" required autocomplete="email" autofocus>
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
                               placeholder="Пароль" required autocomplete="current-password">
                        <i class="fas fa-lock absolute right-4 top-4 text-gray-400"></i>
                        @error('password')
                            <span class="text-red-500 text-sm mt-1 block">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Кнопка входа -->
                    <button type="submit" 
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300 transform hover:scale-[1.02]">
                        <i class="fas fa-sign-in-alt mr-2"></i>Войти
                    </button>

                    <!-- Кнопка Yandex -->
                    <a href="{{ route('yandex') }}" 
                       class="w-full flex items-center justify-center py-2.5 bg-[#FFCC00] hover:bg-[#FFD633] text-black font-semibold rounded-lg transition-all duration-300 transform hover:scale-[1.02]">
                        <i class="fab fa-yandex mr-2"></i>Войти через Yandex
                    </a>
                </form>

                <!-- Ссылка на регистрацию -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">Ещё нет аккаунта?
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors">
                            Зарегистрируйтесь
                        </a>
                    </p>
                </div>

                <!-- Отображение ошибок -->
                @if(session('error'))
                    <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
@endsection