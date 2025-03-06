@extends('layouts.app')
@section('title')
Профиль
@endsection

@section('content')
<div class="py-8">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="user_info flex flex-col">
                    <div class="info">
                        <div class="font-bold text-2xl mb-5">
                            <p>{{ Auth::user()->name }}</p>
                        </div>
                        <div class="text-1xl mb-5">
                            <p class="mb-5">{{ Auth::user()->email }}</p>
                            <p class="mb-5">Дата регистрации: {{ Auth::user()->created_at->format('M. j, Y') }}</p>
                            <p class="mb-5">
                                Последний вход:
                                @if(Auth::user()->last_login_at)
                                    {{ Auth::user()->last_login_at->timezone(config('app.timezone'))->format('M. j, Y H:i') }}
                                @else
                                    Информация недоступна
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"
                            class="bg-transparent hover:bg-black text-black-700 font-semibold hover:text-white py-2 px-4 border border-black hover:border-transparent rounded">
                            Выйти
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </div>
                    <div class="mt-4 space-y-3">
        <button onclick="openRepairModal()"
                class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded transition-all">
            <i class="fas fa-tools mr-2"></i>Заявка на ремонт
        </button>
    </div>
    <!-- Заявки на консультации -->
    <div class="bg-white shadow-md rounded p-4">
                            <h3 class="text-xl font-bold mb-4">Мои заявки на консультации</h3>
                            @if($consultations->isEmpty())
                                <p class="text-gray-500">Нет активных заявок на консультации</p>
                            @else
                                <div class="grid gap-4">
                                    @foreach($consultations as $consultation)
                                        <div class="border rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-semibold">
                                                        <a href="{{ route('Post', $consultation->poster_id) }}" 
                                                           class="text-blue-600 hover:underline">
                                                            {{ $consultation->poster->name ?? 'Удаленный товар' }}
                                                        </a>
                                                    </p>
                                                    <p class="text-sm text-gray-600">{{ $consultation->created_at->format('d.m.Y H:i') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm">
                                                        <a href="tel:{{ $consultation->phone }}" class="text-blue-600 hover:underline">
                                                            {{ $consultation->phone }}
                                                        </a>
                                                    </p>
                                                    <p class="text-sm text-gray-600">Статус: {{ $consultation->status ?? 'В обработке' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="posters-grid col-span-3">
                    <div class="bg-white shadow-md rounded px-4 py-3 mb-3">
                        <h5 class="text-lg font-bold">Избранное</h5>
                        <div class="gap-2 mb-2">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 py-4">
                                @foreach ($like as $name)
                                    @if (is_object($name->poster))
                                        <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition-transform hover:scale-105">
                                            <a href="{{ route('Post', ['post_id' => $name->poster->id]) }}">
                                                <img src="{{ asset($name->poster->image) }}" alt="{{ $name->poster->name }}" class="w-full h-96 object-cover">
                                            </a>
                                            <div class="p-4">
                                                <h3 class="text-lg font-bold mb-2">{{ $name->poster->name }}</h3>
                                                <div class="text-sm text-gray-500 mb-2">
                                                    @foreach ($name->poster->parameters as $parameter)
                                                        @if ($parameter->attribute === 'manufacturer')
                                                            <span class="font-semibold">Производитель:</span> {{ $parameter->name }}<br>
                                                        @elseif ($parameter->attribute === 'algorithm')
                                                            <span class="font-semibold">Алгоритм:</span> {{ $parameter->name }}<br>
                                                        @elseif ($parameter->attribute === 'coin')
                                                            <span class="font-semibold">Монета:</span> {{ $parameter->name }}<br>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <a href="{{ route('Post', ['post_id' => $name->poster->id]) }}"
                                                    class="text-blue-500 hover:underline">Подробнее</a>
                                                <form action="{{ route('removeFromFavorites', ['like_id' => $name->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-white hover:bg-black text-black-700 font-semibold hover:text-white py-2 px-4 border border-black hover:border-transparent rounded mt-2">
                                                        Удалить из избранных
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <p>Некорректные данные</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Модальное окно ремонта -->
<div id="repairModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Заявка на ремонт</h3>
            <form method="post" action="{{ route('repair.request') }}" class="mt-4">
                @csrf
                <!-- Поле модели аппарата -->
                <div class="mb-4">
                    <input type="text" name="model" 
                           class="w-full px-3 py-2 border rounded-md" 
                           placeholder="Модель аппарата*" 
                           required>
                </div>
                
                <div class="mb-4">
                    <input type="tel" name="phone" 
                    class="w-full px-3 py-2 border rounded-md"
                    placeholder="Контактный телефон*"
                    pattern="\+7\d{10}"
                    required>
                    <p class="text-xs text-gray-500 mt-1">Формат: +71234567890</p>
                </div>

                <!-- Поле описания проблемы -->
                <div class="mb-4">
                    <textarea name="description" 
                              class="w-full px-3 py-2 border rounded-md" 
                              rows="4" 
                              placeholder="Опишите проблему..."></textarea>
                </div>

                <!-- Кнопки -->
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeRepairModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Отмена
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Отправить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function openRepairModal() {
    document.getElementById('repairModal').classList.remove('hidden');
}

function closeRepairModal() {
    document.getElementById('repairModal').classList.add('hidden');
}

// Закрытие при клике вне модалки
window.onclick = function(event) {
    const modal = document.getElementById('repairModal');
    if (event.target === modal) {
        closeRepairModal();
    }
}
</script>
@endsection