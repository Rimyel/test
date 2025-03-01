<!-- Header -->
<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex sm:justify-center md:justify-between items-center max-w-screen-xl">
        <div class="flex sm:items-center">
            <a href="{{ route('welcome') }}">
                <h1 class="text-blue-600 text-3xl sm:justify-center font-bold">Криптомир</h1>
            </a>
        </div>
        <nav class="xs:hidden md:flex flex-grow justify-center space-x-8" x-data="{ isAdminMenuOpen: false }">
            <ul class="flex space-x-8 h-full items-stretch" id="main-nav">
                <li class="flex items-center">
                    <a href="/" class="text-blue-600 relative group ">
                        Главная
                        <span
                            class="absolute left-0 right-0 bottom-[-5px] h-0.5 bg-blue-600 scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></span>
                    </a>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('see') }}" class="text-blue-600 relative group">
                        Что бы выбрать
                        <span
                            class="absolute left-0 right-0 bottom-[-5px] h-0.5 bg-blue-600 scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></span>
                    </a>    
                </li>
                
                @if (Auth::user() && Auth::user()->is_admin == 1)
                <li class="relative flex items-center" 
    @mouseenter="isAdminMenuOpen = true; clearTimeout(timeoutId)" 
    @mouseleave="timeoutId = setTimeout(() => { isAdminMenuOpen = false }, 500)">
    <a href="{{ route('admin') }}" class="text-blue-600 relative group">
        Админка
        <span class="absolute left-0 right-0 bottom-[-5px] h-0.5 bg-blue-600 scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></span>
    </a>
    <!-- Выпадающее меню -->
    <div x-show="isAdminMenuOpen" 
         @mouseenter="clearTimeout(timeoutId)" 
         @mouseleave="timeoutId = setTimeout(() => { isAdminMenuOpen = false }, 500)"
         class="absolute top-full left-0 bg-white shadow-lg rounded-md py-2 w-48 z-50">
        <a href="{{ route('admin.requests') }}" 
           class="block px-4 py-2 text-gray-700 hover:bg-blue-50 transition-colors">
            Заявки
        </a>
        <!-- Другие пункты меню -->
    </div>
</li>
                @endif
            </ul>
        </nav>
        <div class="xs:hidden md:flex items-center space-x-4">
            <form action="{{ route('Search') }}" method="POST" class="flex items-center gap-2">
                @csrf
                <input type="text" name="word" placeholder="Поиск" class="border rounded px-3 py-1">
                <button type="submit" class="text-blue-600 transition-all hover:scale-125 active:scale-90">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
                </button>
            </form>
            @if (Auth::user())
                <a href="{{ route('home') }}" class="text-blue-600">{{ Auth::user()->name }}</a>
            @else
                <a href="{{ route('register') }}" class="text-blue-600">Вход</a>
            @endif
        </div>
    </div>

    <!-- Подключение Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <div
        class="fixed bg-white top-0 left-0 md:hidden container mx-auto px-4 py-4 flex flex-col items-center max-w-screen-xl z-50">
        <nav class="grid grid-cols-5 gap-10" id="nav-links">
            <a href="/" class="flex flex-col items-center">
                <img class="w-16" src="{{ asset('sait/afisha.svg') }}" alt="Афиша">
            </a>
            <a href="{{ route('see') }}" class="flex flex-col items-center">
                <img class="w-16" src="{{ asset('sait/question.svg') }}" alt="Что посмотреть">
            </a>
            <a href="{{ route('rating') }}" class="flex flex-col items-center">
                <img class="w-16" src="{{ asset('sait/star.svg') }}" alt="Рейтинг">
            </a>
            @if (Auth::user())
                <a href="{{ route('home') }}" class="text-blue-600">
                    <img class="w-16" src="{{ asset('sait/person.svg') }}" alt="Личный кабинет">
                </a>
            @else
                <a href="/" class="flex flex-col items-center">
                    <img class="w-16" src="{{ asset('sait/login.svg') }}" alt="Личный кабинет">
                </a>
            @endif

            <button id="search-button" class="flex flex-col items-center" onclick="toggleSearch()">
                <img class="w-16" src="{{ asset('sait/search.svg') }}" alt="Поиск">
            </button>
        </nav>
        <div id="search-form" class=" w-10/12 flex items-center justify-center">
            <form action="{{ route('Search') }}" method="POST" class="flex w-10/12 items-center">
                @csrf
                <input type="text" name="word" placeholder="Поиск" class="border rounded  w-10/12 px-3 py-2">
                <button type="submit" class="text-blue-600 px-3 py-2">
                    <img class="w-10" src="{{ asset('sait/search.svg') }}" alt="Поиск">
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleSearch() {
            const searchForm = document.getElementById('search-form');
            const navLinks = document.getElementById('nav-links');

            if (searchForm.classList.contains('hidden')) {
                searchForm.classList.remove('hidden');
                navLinks.classList.add('hidden');
                // Устанавливаем фокус на инпут поиска
                setTimeout(() => {
                    searchForm.querySelector('input').focus();
                }, 0);
            } else {
                searchForm.classList.add('hidden');
                navLinks.classList.remove('hidden');
            }
        }

        // Закрытие инпута при клике вне его
        document.addEventListener('click', (event) => {
            const searchForm = document.getElementById('search-form');
            const searchButton = document.getElementById('search-button');

            if (!searchButton.contains(event.target) && !searchForm.contains(event.target)) {
                if (!searchForm.classList.contains('hidden')) {
                    searchForm.classList.add('hidden');
                    document.getElementById('nav-links').classList.remove('hidden');
                }
            }
        });
    </script>
    </div>
    </div>
</header>