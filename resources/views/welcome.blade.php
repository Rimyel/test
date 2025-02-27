@extends('layouts.app')
@section('title')
Главная страница
@endsection

@section('content')
<title>Каталог Asic майнеров</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<body class="bg-gray-100">

    <!-- Asic Grid -->
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Каталог Asic майнеров</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition-transform hover:scale-105">
                    <a href="{{ route('Post', ['post_id' => $post->id]) }}">
                        <img src="{{ asset($post->image) }}" alt="Asic Miner" class="w-full h-96 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">{{ $post->name }}</h3>
                        <div class="text-sm text-gray-500 mb-2">
                            @if ($post->parameters)
                                @foreach ($post->parameters as $parameter)
                                    @if ($parameter->attribute === 'manufacturer')
                                        <span class="font-semibold">Производитель:</span> {{ $parameter->name }}<br>
                                    @elseif ($parameter->attribute === 'algorithm')
                                        <span class="font-semibold">Алгоритм:</span> {{ $parameter->name }}<br>
                                    @elseif ($parameter->attribute === 'coin')
                                        <span class="font-semibold">Монета:</span> {{ $parameter->name }}<br>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-gray-400">Параметры не указаны</span>
                            @endif
                        </div>
                        <a href="{{ route('Post', ['post_id' => $post->id]) }}"
                            class="text-blue-500 hover:underline">Подробнее</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection