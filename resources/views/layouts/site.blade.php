<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Zdrava') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    @vite('resources/css/app.css')

    <!-- Scripts -->
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-50">
    <section class="mb-40">
        <nav class="navbar navbar-expand-lg shadow-md py-2 bg-white relative flex items-center w-full justify-between">
            <div class="px-6 w-full flex flex-wrap items-center justify-between">
                <div class="flex items-center">
                    <button
                        class="navbar-toggler border-0 py-3 lg:hidden leading-none text-xl bg-transparent text-gray-600 hover:text-gray-700 focus:text-gray-700 transition-shadow duration-150 ease-in-out mr-2"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContentY"
                        aria-controls="navbarSupportedContentY"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <svg
                            aria-hidden="true"
                            focusable="false"
                            data-prefix="fas"
                            class="w-5"
                            role="img"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512"
                        >
                            <path
                                fill="currentColor"
                                d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"
                            ></path>
                        </svg>
                    </button>
                    <a class="navbar-brand text-blue-600" href="{{ route('index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-2 lg:ml-0 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                    </a>
                    <div>
                        <a class="text-xl text-neutral-800 dark:text-neutral-200" href="#">
                            {{ __('Zdrava') }}
                        </a>
                    </div>
                </div>

                <div class="navbar-collapse collapse grow items-center" id="navbarSupportedContentY">
                    <ul class="navbar-nav mr-auto lg:flex lg:flex-row">
                        <li class="nav-item">
                            <a href="#!" class="nav-link block pr-2 lg:px-2 py-2 text-gray-600 hover:text-gray-700 focus:text-gray-700 transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#!" class="nav-link block pr-2 lg:px-2 py-2 text-gray-600 hover:text-gray-700 focus:text-gray-700 transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light">
                                Team
                            </a>
                        </li>
                        <li class="nav-item mb-2 lg:mb-0">
                            <a href="#!" class="nav-link block pr-2 lg:px-2 py-2 text-gray-600 hover:text-gray-700 focus:text-gray-700 transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light">
                                Projects
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="flex items-center lg:ml-auto">
                    @auth
                        <div class="group relative cursor-pointer py-2">
                            <div class="flex items-center justify-between space-x-5 bg-white px-4">
                                <a class="menu-hover text-base font-medium text-black lg:mx-0" onClick="">
                                    @if (Auth::user()->photo)
                                        <img
                                            src="{{ asset('/pictures/athletes/' . Auth::user()->id . '/' . Auth::user()->photo . '/medium.jpg') }}"
                                            class="rounded-full"
                                            style="height: 25px; width: 25px"
                                            alt=""
                                            loading="lazy" />
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    @endif
                                </a>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </div>
                            <div class="invisible absolute z-50 flex right-0 w-48 flex-col bg-gray-100 py-1 px-4 shadow-xl group-hover:visible" onClick="">
                                <a class="my-2 block border-b border-gray-100 py-1 text-success hover:text-black md:mx-2">
                                    Найти друзей
                                </a>
                                <a class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">
                                    Мой профиль
                                </a>
                                <a class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">
                                    Настройки
                                </a>
                                <a href="{{ route('auth.logout') }}" class="font-semibold my-2 block border-b border-gray-100 py-1 text-black-500 hover:text-black md:mx-2">
                                    {{ __('Logout') }}
                                </a>
                            </div>
                        </div>
                        <div class="group relative cursor-pointer py-2">
                            <div class="flex items-center justify-between space-x-5 bg-white px-4">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="invisible absolute z-50 flex right-0 w-56 flex-col bg-gray-100 py-1 px-4 text-gray-800 shadow-xl group-hover:visible" onClick="">
                                <a class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">
                                    Загрузить тренировку
                                </a>
                                <a class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">
                                    Добавить тренировку вручную
                                </a>
                                <a class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">
                                    Новый маршрут
                                </a>
                                <a class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">
                                    Создать запись
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('auth.login') }}" type="button" class="inline-block px-6 py-2.5 mr-2 bg-transparent text-blue-600 font-medium text-xs leading-tight uppercase rounded hover:text-blue-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none focus:ring-0 active:bg-gray-200 transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('auth.register') }}" type="button" class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light">
                            {{ __('Sign up for free') }}
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        @yield('content')
    </section>

</body>
</html>
