@extends('layouts.app')

@section('content')
<div class="max-w-screen-2xl w-full h-auto mx-auto my-0 mb-20">
    <div class="flex justify-between pt-20">
        <div class="flex gap-5">
            <div class="max-w-[380px] min-w-96 h-auto "> <!-- Ограничение ширины до 380 пикселей -->
                <img src="{{ asset($post->image) }}" alt="Изображение продукта"
                    class="object-cover w-full h-auto rounded-lg shadow-md" style="max-width: 100%; height: auto;">
            </div>

            <!-- Другие элементы содержания -->
            <div class="flex flex-col ml-5 text-start color-root-grey-light max-w-4xl">
                <h3 class="font-semibold text-2xl">{{ $post->name }}</h3>
                <p class="text-gray-600">Дата создания: {{ $post->created_at->format('M. j, Y h:m') }}</p>
                <p class="text-gray-600">Просмотры: {{ $post->views }}</p>
                @if ($post->genres->isEmpty())
                    <p class="text-gray-600">Жанр не указан.</p>
                @else
                    <ul class="list-disc pl-5">
                        <li class="text-gray-600">Жанр:
                            @foreach ($post->genres as $genre)
                                {{ $genre->name }}@if (!$loop->last), @endif
                            @endforeach
                        </li>
                    </ul>
                @endif
                <div class="desc mb-5">
                    <div class="title color-root-grey-light mt-5">
                        <h2 class="text-xl font-semibold">Описание</h2>
                    </div>
                    <div class="color-root-grey-light mb-5">
                        <p class="font-semibold text-gray-700">{{ $post->description }}</p>
                    </div>
                    @if ($like)
                        <a href="{{ route('ToLike', ['product_id' => $post->id]) }}"
                            class="w-full md:w-[260px] px-4 py-2 rounded-xl border border-gray-300 text-gray-500 text-center transition duration-300 ease-in-out hover:bg-gray-100">
                            В избранном
                        </a>
                    @else
                        <a href="{{ route('ToLike', ['product_id' => $post->id]) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                            Добавить в избранное
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class=" text-gray-900 h-auto w-3/6 ">
        <div class="flex justify-between items-start">
            <section class="py-8 lg:py-16 w-screen">
                <div class="mx-auto">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-lg lg:text-2xl font-bold text-gray-900">Комментарии
                            ({{ count($comments) }})
                        </h2>
                    </div>
                    <form class="mb-6" method="POST" action="{{ route('newComment', ['id' => $post->id]) }}">
                        @csrf

                        <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200">
                            <label for="comment" class="sr-only">Ваш комментарий</label>
                            <textarea id="comment" rows="6" name="message"
                                class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none"
                                placeholder="Ваш комментарий" required></textarea>
                        </div>
                        <button type="submit"
                            class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center border text-gray-500 bg-primary-900 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
                            Опубликовать
                        </button>
                    </form>
                    @foreach ($comments as $comment)
                        <article class=" mb-5 text-base bg-gray-200 rounded-lg">
                            <footer class="flex justify-between items-center mb-2 w-max">
                                <div class="flex items-center">
                                    <p class="inline-flex items-center mr-3 text-sm text-gray-900">
                                        {{ $comment->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <time pubdate datetime="{{ $comment->created_at->toISOString() }}"
                                            title="{{ $comment->created_at->setTimezone(config('app.timezone'))->format('F j, Y H:i') }}">
                                            {{ $comment->created_at->setTimezone(config('app.timezone'))->format('d.m.Y H:i') }}
                                        </time>
                                    </p>
                                </div>
                            </footer>
                            <p class="text-gray-500">{{ $comment->message }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
    <!-- Форма для добавления оценки -->
    <div class="text-gray-900 h-auto w-3/6">
        <div class="flex justify-between items-start">
            <section class="py-8 lg:py-16 w-screen">
                <div class="mx-auto">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-lg lg:text-2xl font-bold text-gray-900">Оцените фильм</h2>
                    </div>
                    <form class="mb-6" method="POST" action="{{ route('rate', ['post_id' => $post->id]) }}">
                        @csrf

                        <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200">
                            <label for="rank" class="sr-only">Ваша оценка</label>
                            <div class="flex items-center" id="rating-stars">
                                @for ($i = 1; $i <= 10; $i++)
                                    <button type="button" class="star-button" data-value="{{ $i }}">
                                        <svg class="w-8 h-8 text-gray-400 {{ $userRating >= $i ? 'text-yellow-400' : '' }}"
                                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rank" id="rank" value="{{ $userRating }}">
                        </div>
                        <button type="submit"
                            class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center border text-gray-500 bg-primary-900 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
                            Оценить
                        </button>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const stars = document.querySelectorAll('.star-button');
                            const rankInput = document.getElementById('rank');

                            stars.forEach(star => {
                                star.addEventListener('click', function () {
                                    const value = this.getAttribute('data-value');
                                    rankInput.value = value;
                                    updateStars(value);
                                });
                            });

                            function updateStars(value) {
                                stars.forEach(star => {
                                    const starValue = star.getAttribute('data-value');
                                    if (starValue <= value) {
                                        star.querySelector('svg').classList.add('text-yellow-400');
                                        star.querySelector('svg').classList.remove('text-gray-400');
                                    } else {
                                        star.querySelector('svg').classList.add('text-gray-400');
                                        star.querySelector('svg').classList.remove('text-yellow-400');
                                    }
                                });
                            }
                        });
                    </script>
                </div>
            </section>
        </div>
    </div>
    </section>
</div>
</div>

<!-- Отображение текущих оценок -->
<div class="text-gray-900 max-w-screen-2xl w-full h-auto mx-auto my-0 mb-20">
    <div class="flex justify-between items-start">
        <section class="py-8 lg:py-16 w-screen">
            <div class="mx-auto">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-lg lg:text-2xl font-bold text-gray-900">Текущие оценки</h2>
                </div>
                <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200">
                    <p class="text-gray-900">Средняя оценка: {{ number_format($averageRating ?? 0, decimals: 1) }}
                        звезда{{ ($averageRating ?? 0) > 1 ? 's' : '' }}</p>
                    <div class="flex items-center">
                        @for ($i = 1; $i <= 10; $i++)
                            <svg class="w-8 h-8 {{ $averageRating >= $i ? 'text-yellow-400' : 'text-gray-400' }}"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        @endfor
                    </div>
                    <ul>
                        @foreach ($ratings as $rating)
                            <li class="text-gray-900">{{ $rating->user->name }}: {{ $rating->rank }}
                                звезда{{ $rating->rank > 1 ? 's' : '' }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Распределение оценок -->
                <div class="mt-4">
                    @if ($ratings->count() > 0)
                        <p class="text-sm font-medium text-gray-500">{{ $ratings->count() }} global ratings</p>
                        @for ($i = 10; $i >= 1; $i--)
                            <div class="flex items-center mt-4">
                                <a href="#" class="text-sm font-medium text-blue-600 hover:underline">{{ $i }} star</a>
                                <div class="w-2/4 h-5 mx-4 bg-gray-200 rounded">
                                    <div class="h-5 bg-yellow-300 rounded"
                                        style="width: {{ $ratings->where('rank', $i)->count() / $ratings->count() * 100 }}%">
                                    </div>
                                </div>
                                <span
                                    class="text-sm font-medium text-gray-500">{{ number_format($ratings->where('rank', $i)->count() / $ratings->count() * 100, 1) }}%</span>
                            </div>
                        @endfor
                    @else
                        <p class="text-sm font-medium text-gray-500">No ratings available.</p>
                    @endif
                </div>
            </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</section>




@endsection