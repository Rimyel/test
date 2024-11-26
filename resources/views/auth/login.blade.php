@extends('layouts.auth')
@section('title')
    Вход
@endsection

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="flex w-4/5 max-w-4xl h-96 shadow-lg">
            <div class="flex flex-col justify-center w-1/2 p-10 bg-black text-white">
                <h1 class="text-4xl font-bold">Библиотека</h1>
                <p class="mt-4">Приглашаем вас прочитать новую книгу</p>
                <div class="px-4 py-2 mt-6 text-black bg-white rounded w-1/2"><a
                        href="{{ route('register') }}"><button >Зарегестрироваться</button></a></div>
                        <form action="{{ route('yandex') }}">
            <button type="submit"
                            class="my-1.5 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Войти через Yandex
                        </button>
            </form>  
            </div>
            <form method="POST" action="{{ route('login') }}" class="content-center flex items-center mx-auto my-0">
                @csrf
                <div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') is-invalid @enderror"
                            required autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Пароль:</label>
                        <input type="password" id="password" name="password"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') is-invalid @enderror"
                            required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between mb-4">

                    </div>
                    <div class="flex items-center justify-center">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Войти
                        </button>
                    </div>
                    @if(session('error'))
                        <div class="mt-4 text-red-500 text-center">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </form>
            
        </div>
    </div>
@endsection