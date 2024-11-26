@extends('layouts.app')

<!-- Подключение ApexCharts.js -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>



@section('content')
<div class="max-w-screen-2xl w-full h-auto mx-auto my-0 mb-20">
    <div class="flex justify-between pt-20">
        <h1 class="text-3xl font-bold text-gray-900">Статистика</h1>
        <div>
            <a href="{{ route('export.word') }}" class="btn btn-primary">Скачать в Word</a>
            <a href="{{ route('export.excel') }}" class="btn btn-primary">Скачать в Excel</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
        <!-- Общее количество постов -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900">Общее количество постов</h2>
            <p class="text-4xl font-bold text-blue-600 mt-4">{{ $totalPosts }}</p>
        </div>

        <!-- Средняя оценка -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900">Средняя оценка</h2>
            <p class="text-4xl font-bold text-yellow-500 mt-4">{{ number_format($averageRating, 1) }}</p>
        </div>

        <!-- Общее количество комментариев -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900">Общее количество комментариев</h2>
            <p class="text-4xl font-bold text-green-600 mt-4">{{ $totalComments }}</p>
        </div>

        <!-- Общее количество пользователей -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900">Общее количество пользователей</h2>
            <p class="text-4xl font-bold text-purple-600 mt-4">{{ $totalUsers }}</p>
        </div>

        <!-- Общее количество просмотров -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900">Общее количество просмотров</h2>
            <p class="text-4xl font-bold text-red-600 mt-4">{{ $totalViews }}</p>
        </div>

        <!-- Общее количество просмотров страниц -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900">Общее количество просмотров страниц</h2>
            <p class="text-4xl font-bold text-blue-600 mt-4">{{ $analyticsData['pageViews'] }}</p>
        </div>
    </div>

    <!-- Графики -->
    <div class="mt-10">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Графики</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- График количества постов по месяцам -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900">Количество постов по месяцам</h3>
                <div id="postsChart"></div>
            </div>

            <!-- График количества комментариев по месяцам -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900">Количество комментариев по месяцам</h3>
                <div id="commentsChart"></div>
            </div>

            <!-- График количества просмотров страниц -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900">Количество просмотров страниц</h3>
                <div id="pageViewsChart"></div>
            </div>

            <!-- График последних входов пользователей по дням -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900">Последние входы пользователей по дням</h3>
                <div id="lastLoginChart"></div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript код -->
<script>
    // Данные для графиков
    const postsData = {!! json_encode($postsByMonth) !!};
    const commentsData = {!! json_encode($commentsByMonth) !!};
    const lastLoginData = {!! json_encode($lastLoginByDay) !!};
    const analyticsData = {!! json_encode($analyticsData) !!};

    // График количества постов по месяцам
    const postsChart = new ApexCharts(document.querySelector("#postsChart"), {
        chart: {
            type: 'line'
        },
        series: [{
            name: 'Количество постов',
            data: postsData.data
        }],
        xaxis: {
            categories: postsData.labels
        }
    });
    postsChart.render();

    // График количества комментариев по месяцам
    const commentsChart = new ApexCharts(document.querySelector("#commentsChart"), {
        chart: {
            type: 'bar'
        },
        series: [{
            name: 'Количество комментариев',
            data: commentsData.data
        }],
        xaxis: {
            categories: commentsData.labels
        }
    });
    commentsChart.render();

    // График количества просмотров страниц
    const pageViewsChart = new ApexCharts(document.querySelector("#pageViewsChart"), {
        chart: {
            type: 'bar'
        },
        series: [{
            name: 'Количество просмотров',
            data: analyticsData.pageViewsData
        }],
        xaxis: {
            categories: analyticsData.pageViewsLabels
        }
    });
    pageViewsChart.render();

    // График последних входов пользователей по дням
    const lastLoginChart = new ApexCharts(document.querySelector("#lastLoginChart"), {
        chart: {
            type: 'line'
        },
        series: [{
            name: 'Последние входы пользователей',
            data: lastLoginData.data
        }],
        xaxis: {
            categories: lastLoginData.labels
        }
    });
    lastLoginChart.render();
</script>
@endsection