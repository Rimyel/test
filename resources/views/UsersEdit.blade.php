@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="container">
        <div class="container mx-5 mb-5">
            <div class="flex justify-center">
                <div class="w-full md:w-full">
                    <div class="bg-white shadow-md rounded px-5 py-5 flex">
                        <div class="ml-5 w-full">
                            <h1 class="text-3xl font-bold text-gray-900 mb-5">Редактирование пользователя</h1>
                            <form action="{{ route('UsersUpdate', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="relative z-0 w-full mb-5 group">
                                    <input type="text" name="name" id="name" value="{{ $user->name }}"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        required />
                                    <label for="name"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Имя</label>
                                </div>
                                <div class="relative z-0 w-full mb-5 group">
                                    <input type="email" name="email" id="email" value="{{ $user->email }}"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        required />
                                    <label for="email"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                                </div>
                                <button type="submit"
                                    class="mt-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Сохранить</button>
                            </form>
                            @if (session('success'))
                                <div class="bg-green-500 text-white p-4 rounded mb-4 mt-4">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="bg-red-500 text-white p-4 rounded mt-4">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Информация о пользователе -->
    <div class="container mx-5 mb-5">
        <div class="flex justify-center">
            <div class="w-full md:w-full">
                <div class="bg-white shadow-md rounded px-5 py-5">
                    <h2 class="text-2xl font-bold text-gray-900 mb-5">Информация о пользователе</h2>
                    <div class="mb-4">
                        <strong>Последний вход:</strong>
                        {{ $user->last_login_at ? $user->last_login_at->format('d.m.Y H:i') : 'Нет данных' }}
                    </div>
                    <div class="mb-4">
                        <strong>Избранные фильмы:</strong>
                        <ul>
                            @foreach($user->likes as $like)
                                <li>{{ $like->poster->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mb-4">
                        <strong>Комментарии:</strong>
                        @foreach($user->comments as $comment)
                            <div class="bg-gray-100 p-4 rounded mb-4">
                                <strong>Фильм:</strong> {{ $comment->poster->name }}<br>
                                <strong>Комментарий:</strong> {{ $comment->message }}<br>
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Удалить</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection