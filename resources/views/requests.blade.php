@extends('layouts.app')

@section('title', 'Управление заявками')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ activeTab: 'consultations' }">
    <!-- Табы для переключения -->
    <div class="mb-8 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px">
            <li class="mr-2">
                <button @click="activeTab = 'consultations'" 
                        :class="activeTab === 'consultations' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent'"
                        class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                    Консультации ({{ $consultations->total() }})
                </button>
            </li>
            <li class="mr-2">
                <button @click="activeTab = 'repairs'" 
                        :class="activeTab === 'repairs' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent'"
                        class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                    Ремонты ({{ $repairs->total() }})
                </button>
            </li>
        </ul>
    </div>

    <!-- Консультационные заявки -->
    <div x-show="activeTab === 'consultations'" class="bg-white rounded-lg shadow overflow-hidden">
        <h2 class="text-2xl font-bold p-4 border-b">Заявки на консультацию</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Пользователь</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Оборудование</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Телефон</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($consultations as $consultation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $consultation->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $consultation->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $consultation->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('Post', $consultation->poster_id) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                {{ $consultation->poster->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="tel:{{ $consultation->phone }}" class="hover:text-blue-600">
                                {{ $consultation->phone }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $consultation->created_at->format('d.m.Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Нет активных заявок на консультации
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 p-4 border-t">
            {{ $consultations->links() }}
        </div>
    </div>

    <!-- Ремонтные заявки -->
    <div x-show="activeTab === 'repairs'" class="bg-white rounded-lg shadow overflow-hidden">
        <h2 class="text-2xl font-bold p-4 border-b">Заявки на ремонт</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Пользователь</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Модель</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Телефон</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($repairs as $repair)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $repair->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $repair->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $repair->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $repair->model }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="tel:{{ $repair->phone }}" class="hover:text-blue-600">
                                {{ $repair->phone }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('repair.update-status', $repair->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" 
                                        onchange="this.form.submit()" 
                                        class="px-2 text-xs leading-5 font-semibold rounded-full cursor-pointer
                                            {{ $repair->status === 'Новая' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($repair->status === 'В работе' ? 'bg-blue-100 text-blue-800' : 
                                               'bg-green-100 text-green-800') }}">
                                    @foreach(App\Models\RepairRequest::STATUSES as $value => $label)
                                        <option value="{{ $value }}" {{ $repair->status === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $repair->created_at->format('d.m.Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Нет активных заявок на ремонт
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 p-4 border-t">
            {{ $repairs->links() }}
        </div>
    </div>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection