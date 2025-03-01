@extends('layouts.app')

@section('title', 'Редактирование объявления')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Блок с изображением -->
                    <div class="w-full md:w-1/3">
                        <div class="relative group">
                            <img src="{{ asset($poster->image) }}" alt="Изображение постера" 
                                 class="rounded-lg shadow-md w-full h-64 object-cover transition-transform duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <label class="cursor-pointer text-white">
                                    <input type="file" class="hidden" name="image">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Форма редактирования -->
                    <div class="w-full md:w-2/3">
                        <form action="{{ route('save_posts', ['poster_id' => $poster->id]) }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Основные поля -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Название</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $poster->name) }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required>{{ old('description', $poster->description) }}</textarea>
                            </div>

                            <!-- Параметры -->
                            <div class="space-y-6">
                                <!-- Производитель -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Производитель</label>
                                    <select name="parameters[manufacturer]" 
                                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                        <option value="">Выберите производителя</option>
                                        @foreach($parameters['manufacturer'] ?? [] as $manufacturer)
                                            <option value="{{ $manufacturer->id }}" 
                                                {{ $poster->parameters->contains($manufacturer->id) ? 'selected' : '' }}>
                                                {{ $manufacturer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Алгоритм -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Алгоритм</label>
                                    <select name="parameters[algorithm]" 
                                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                        <option value="">Выберите алгоритм</option>
                                        @foreach($parameters['algorithm'] ?? [] as $algorithm)
                                            <option value="{{ $algorithm->id }}" 
                                                {{ $poster->parameters->contains($algorithm->id) ? 'selected' : '' }}>
                                                {{ $algorithm->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Монета -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Монета</label>
                                    <select name="parameters[coin]" 
                                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                        <option value="">Выберите монету</option>
                                        @foreach($parameters['coin'] ?? [] as $coin)
                                            <option value="{{ $coin->id }}" 
                                                {{ $poster->parameters->contains($coin->id) ? 'selected' : '' }}>
                                                {{ $coin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                
                            </div>

                            <!-- Сообщения об ошибках -->
                            @if($errors->any())
                                <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                                    <ul class="list-disc pl-5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Сообщение об успехе -->
                            @if(session('success'))
                                <div class="mt-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Кнопка сохранения -->
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02]">
                                Сохранить изменения
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection