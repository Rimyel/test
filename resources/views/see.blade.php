@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ showSortModal: false }">
    <h2 class="text-2xl font-bold mb-4">Не знаете что посмотреть?</h2>

    <!-- Кнопка для открытия окна сортировки -->
    <button @click="showSortModal = !showSortModal" class="bg-blue-500 text-white px-4 py-2 rounded-lg mb-4">Сортировать по жанру</button>

    <!-- Окно сортировки -->
    <div x-show="showSortModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-4 bg-white p-6 rounded-lg shadow-lg mb-6">
        <h3 class="text-lg font-bold mb-4">Выберите жанры</h3>
        <form action="{{ route('see') }}" method="GET">
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach ($genres as $genre)
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="genres[]" value="{{ $genre }}" class="form-checkbox h-5 w-5 text-blue-600 cursor-pointer" @if (in_array($genre, $selectedGenres)) checked @endif>
                        <span class="ml-2 text-gray-700">{{ $genre }}</span>
                    </label>
                @endforeach
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Применить</button>
        </form>
        
        <!-- Отображение выбранных жанров -->
        @if (!empty($selectedGenres))
            <div class="mt-4">
                <h4 class="text-lg font-bold mb-2">Выбранные жанры:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach ($selectedGenres as $selectedGenre)
                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full">{{ $selectedGenre }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach ($posts as $post)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition-transform hover:scale-105">
                <a href="{{ route('Post', ['post_id' => $post->id]) }}">
                    <img src="{{ asset($post->image) }}" alt="Movie 1" class="w-full h-96 object-cover">
                </a>
                <div class="p-4">
                    <h3 class="text-lg font-bold mb-2">{{ $post->name }}</h3>
                    <div class="text-sm text-gray-500 mb-2">
                        @foreach ($post->genres as $genre)
                            {{ $genre->name }}@if (!$loop->last), @endif
                        @endforeach
                    </div>
                    <a href="{{ route('Post', ['post_id' => $post->id]) }}"
                        class="text-blue-500 hover:underline">Подробнее</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

<style>
    [x-cloak] { display: none !important; }
</style>