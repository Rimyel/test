@extends('layouts.app')
@section('title')
Главная страница
@endsection

@section('content')
<title>Beautiful Slider</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


<body class="bg-gray-100">

    <!-- Movies Grid -->
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">10 главных книг которые есть у всех</h2>
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