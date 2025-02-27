@extends('layouts.app')

@section('content')
<div class="max-w-screen-2xl w-full h-auto mx-auto my-0 mb-20">
    <div class="flex justify-between pt-20">
        <div class="flex gap-5">
            <div class="max-w-[380px] min-w-96 h-auto">
                <img src="{{ asset($post->image) }}" alt="Изображение продукта"
                    class="object-cover w-full h-auto rounded-lg shadow-md" style="max-width: 100%; height: auto;">
            </div>

            <!-- Другие элементы содержания -->
            <div class="flex flex-col ml-5 text-start color-root-grey-light max-w-4xl">
                <h3 class="font-semibold text-2xl">{{ $post->name }}</h3>
                <p class="text-gray-600">Дата создания: {{ $post->created_at->format('M. j, Y h:m') }}</p>

                <!-- Отображение параметров -->
                @if (optional($post->parameters)->isEmpty())
                    <p class="text-gray-600">Параметры не указаны.</p>
                @else
                    <ul class="list-disc pl-5">
                        @foreach ($post->parameters as $parameter)
                            @if ($parameter->attribute === 'manufacturer')
                                <li class="text-gray-600"><span class="font-semibold">Производитель:</span> {{ $parameter->name }}</li>
                            @elseif ($parameter->attribute === 'algorithm')
                                <li class="text-gray-600"><span class="font-semibold">Алгоритм:</span> {{ $parameter->name }}</li>
                            @elseif ($parameter->attribute === 'coin')
                                <li class="text-gray-600"><span class="font-semibold">Монета:</span> {{ $parameter->name }}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif

                <div class="desc mb-5">
                    <div class="title color-root-grey-light mt-5">
                        <h2 class="text-xl font-semibold">Описание</h2>
                    </div>
                    <div class="color-root-grey-light mb-5">
                        <p class="font-semibold text-gray-700">{{ $post->description }}</p>
                    </div>
                    
                    <!-- Форма для добавления/удаления из избранного -->
                    <form action="{{ route('ToLike', ['product_id' => $post->id]) }}" method="POST">
                        @csrf
                        @if ($like)
                            <button type="submit"
                                class="w-full md:w-[260px] px-4 py-2 rounded-xl border border-gray-300 text-gray-500 text-center transition duration-300 ease-in-out hover:bg-gray-100">
                                В избранном
                            </button>
                        @else
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                                Добавить в избранное
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="text-gray-900 h-auto w-3/6">
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
                        <article class="mb-5 text-base bg-gray-200 rounded-lg">
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
</div>
@endsection