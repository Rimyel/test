@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="container">
        <div class="container mx-5 mb-5">
            <div class="flex justify-center">
                <div class="w-full md:w-full">
                    <div class="bg-white shadow-md rounded px-5 py-5 flex">
                        <img src="{{ asset($poster->image) }}" alt="" class="rounded w-96 min-h-48 float-left" height="200">
                        <div class="ml-5 w-full">
                            <form action="{{ route('save_posts', ['poster_id' => $poster->id]) }}" method="POST">
                                @csrf
                                <input type="text" name="name" id="name" value="{{ $poster->name }}"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    required />
                                <textarea id="description" name="description" rows="4"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder="Введите описание" required>{{ $poster->description }}</textarea>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Жанры:</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach ($genres as $genre)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                                                    class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                    id="genre-{{ $genre->id }}"
                                                    {{ in_array($genre->id, $poster->genres->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                <label for="genre-{{ $genre->id }}" class="ml-2 block text-sm text-gray-900 cursor-pointer">{{ $genre->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit"
                                    class="mt-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Сохранить</button>
                            </form>
                            @if (session('success'))
                                <div class="bg-green-500 text-white p-4 rounded mb-4">
                                    {{ session('success') }}
                                </div>  
                            @endif
                            @if ($errors->any())
                                <div class="bg-red-500 text-white p-4 rounded">
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
</div>

@endsection