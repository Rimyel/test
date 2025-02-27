@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ showSortModal: false }">
    <h2 class="text-2xl font-bold mb-4">Не знаете что посмотреть?</h2>

    <!-- Кнопка для открытия окна сортировки -->
    <button @click="showSortModal = !showSortModal" class="bg-blue-500 text-white px-4 py-2 rounded-lg mb-4">Сортировать по параметрам</button>

    <!-- Окно сортировки -->
    <div x-show="showSortModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-4 bg-white p-6 rounded-lg shadow-lg mb-6">
        <h3 class="text-lg font-bold mb-4">Выберите параметры</h3>
        <form action="{{ route('see') }}" method="GET">
            <div class="flex flex-wrap gap-2 mb-4">
                <!-- Производители -->
                <div class="w-full">
                    <h4 class="font-semibold mb-2">Производители:</h4>
                    @foreach ($parameters->where('attribute', 'manufacturer') as $parameter)
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="parameters[]" value="{{ $parameter->id }}" class="form-checkbox h-5 w-5 text-blue-600 cursor-pointer" @if (in_array($parameter->id, $selectedParameters)) checked @endif>
                            <span class="ml-2 text-gray-700">{{ $parameter->name }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Алгоритмы -->
                <div class="w-full mt-4">
                    <h4 class="font-semibold mb-2">Алгоритмы:</h4>
                    @foreach ($parameters->where('attribute', 'algorithm') as $parameter)
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="parameters[]" value="{{ $parameter->id }}" class="form-checkbox h-5 w-5 text-blue-600 cursor-pointer" @if (in_array($parameter->id, $selectedParameters)) checked @endif>
                            <span class="ml-2 text-gray-700">{{ $parameter->name }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Монеты -->
                <div class="w-full mt-4">
                    <h4 class="font-semibold mb-2">Монеты:</h4>
                    @foreach ($parameters->where('attribute', 'coin') as $parameter)
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="parameters[]" value="{{ $parameter->id }}" class="form-checkbox h-5 w-5 text-blue-600 cursor-pointer" @if (in_array($parameter->id, $selectedParameters)) checked @endif>
                            <span class="ml-2 text-gray-700">{{ $parameter->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Применить</button>
        </form>
        
        <!-- Отображение выбранных параметров -->
        @if (!empty($selectedParameters))
            <div class="mt-4">
                <h4 class="text-lg font-bold mb-2">Выбранные параметры:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach ($selectedParameters as $selectedParameterId)
                        @php
                            $selectedParameter = $parameters->firstWhere('id', $selectedParameterId);
                        @endphp
                        @if ($selectedParameter)
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full">{{ $selectedParameter->name }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
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

<style>
    [x-cloak] { display: none !important; }
</style>