<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Социальная сеть для спортсменов. Бег, велоспорт, сап борд и многое другое. Загружай тренировки, общайся, заводи новых друзей." />
    <!-- Open gpaph -->
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Zdrava - бег и велоспорт">
    <meta property="og:description" content="Социальная сеть для спортсменов">
    <meta property="og:image" content="{{ config('app.url') }}/zdrava.png">

    <link rel="icon" href="{{ URL::asset('favicon.svg') }}">
    <title>{{ config('app.name', 'Zdrava - соцсеть для спортсменов') }}</title>

    <!-- Fonts -->
    @googlefonts('ubuntu')

    <!-- Styles -->
    @vite('resources/css/app.css')
    @livewireStyles

    <!-- Scripts -->
    @vite('resources/js/app.js')
    @livewireScripts
    @yield('js')
    <!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//stat.mpolr.ru/";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Matomo Code -->
</head>
<body>
    <section>
        <nav class="navbar navbar-expand-lg shadow-md py-2 bg-white relative flex items-center w-full justify-between">
            <div class="px-6 w-full flex flex-wrap items-center justify-between">
                <div class="flex items-center">
                    <button
                        class="navbar-toggler border-0 py-3 lg:hidden leading-none text-xl bg-transparent text-gray-600 hover:text-gray-700 focus:text-gray-700 transition-shadow duration-150 ease-in-out mr-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContentY" aria-controls="navbarSupportedContentY" aria-expanded="false" aria-label="Toggle navigation">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" class="w-5" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"></path></svg>
                    </button>
                    <a class="navbar-brand text-blue-600" href="{{ route('index') }}">
                        <img src="{{ asset('favicon.svg') }}" width="24" height="24" alt="{{ __('Zdrava') }}">
                    </a>
                    <div>
                        <a href="{{ route('index') }}" class="text-xl text-neutral-800 dark:text-neutral-200 ml-3">
                            {{ __('Zdrava') }}
                        </a>
                    </div>
                </div>
                @auth
                <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-2">
                    <div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
                        <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
{{--                            <li>--}}
{{--                                <a href="{{ route('app') }}" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">--}}
{{--                                    {{ __('Download Zdrava :version for android', ['version' => '']) }}--}}
{{--                                </a>--}}
{{--                            </li>--}}
                            <li>
                                <button id="buttonWorkouts" data-dropdown-toggle="buttonWorkoutsDropdownNavbar" class="flex items-center justify-between w-full py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                                    {{ __('Workout') }} <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                                <div id="buttonWorkoutsDropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                                        <li>
                                            <a href="{{ route('athlete.training') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                {{ __('My workouts') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <button id="buttonNewitems" data-dropdown-toggle="buttonNewitemsDropdownNavbar" class="flex items-center justify-between w-full py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                                    {{ __('New items') }} <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                                <div id="buttonNewitemsDropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                                        <li>
                                            <a href="{{ route('segments.explore') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                {{ __('Explore segments') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('segments.search') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                {{ __('Search segments') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                {{ __('Search athletes') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                {{ __('Search clubs') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @endauth
                <div class="flex items-center lg:ml-auto">
                    @auth
                        <div class="group relative cursor-pointer py-1">
                            <div class="flex items-center justify-between space-x-5 bg-white px-4">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="invisible absolute z-50 flex right-0 w-56 flex-col bg-gray-100 py-1 px-4 text-gray-800 shadow-xl group-hover:visible" onClick="">
                                <a href="{{ route('upload.workout') }}" class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">
                                    {{ __('Upload workout') }}
                                </a>
                                {{--                                <a class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">--}}
                                {{--                                    Добавить тренировку вручную--}}
                                {{--                                </a>--}}
                                {{--                                <a class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">--}}
                                {{--                                    Новый маршрут--}}
                                {{--                                </a>--}}
                                {{--                                <a class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">--}}
                                {{--                                    Создать запись--}}
                                {{--                                </a>--}}
                            </div>
                        </div>
                        @if (auth()->user()->subscribers()->where('confirmed', 0)->count() > 0)
                            <div class="group relative cursor-pointer py-1">
                                <div class="flex items-center justify-between space-x-5 bg-white px-4">
                                    <span>
                                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 stroke-gray-700" xmlns="http://www.w3.org/2000/svg">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                                        </svg>
                                    </span>
                                    <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-orange-500 border-2 border-white rounded-full -top-1 -right-0 dark:border-gray-900">
                                        {{ auth()->user()->subscribers()->where('confirmed', 0)->count() }}
                                    </div>
                                </div>
                                <div class="invisible absolute z-50 flex right-0 w-56 flex-col bg-gray-100 py-1 px-4 text-gray-800 shadow-xl group-hover:visible" onClick="">
                                    <a href="{{ route('friends.requests') }}" class="my-2 block border-b border-gray-100 py-1 text-black md:mx-2">
                                        {{ __('Subscription requests: :count', ['count' => auth()->user()->subscribers()->where('confirmed', 0)->count()]) }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="group relative cursor-pointer py-1">
                            <div class="flex items-center justify-between space-x-5 bg-white px-4">
                                <a class="menu-hover text-base font-medium text-black lg:mx-0" onClick="">
                                    @if (auth()->user()->getPhoto())
                                        <img src="{{ auth()->user()->getPhoto() }}"
                                             alt="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}"
                                             loading="lazy"
                                             class="w9 h-9 p-1 rounded-full ring-1 ring-gray-300 dark:ring-gray-500" />
                                    @else
                                        <div class="relative inline-flex items-center justify-center w-9 h-9 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                            <span class="font-medium text-gray-600 dark:text-gray-300">
                                                {{ auth()->user()->getInitials() }}
                                            </span>
                                        </div>
                                    @endif
                                </a>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </div>
                            <div class="invisible absolute z-50 flex right-0 w-48 flex-col bg-gray-100 py-1 px-4 shadow-xl group-hover:visible" onClick="">
                                <a href="{{ route('friends.find') }}" class="my-2 block border-b border-gray-100 py-1 text-success hover:text-success-700 md:mx-2">
                                    {{ __('Find friends') }}
                                </a>
                                <a href="{{ route('settings.profile') }}" class="my-2 block border-b border-gray-100 py-1 text-gray-500 hover:text-black md:mx-2">
                                    {{ __('Settings') }}
                                </a>
                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('admin.index') }}" class="my-2 block border-b border-gray-100 py-1 text-red-500 hover:text-red-700 md:mx-2">
                                        {{ __('Admin panel') }}
                                    </a>
                                @endif
                                <a href="{{ route('auth.logout') }}" class="font-semibold my-2 block border-b border-gray-100 py-1 text-black-500 hover:text-black md:mx-2">
                                    {{ __('Logout') }}
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
    </section>
    <div class="mx-auto text-grey-darkest">
        <main class="w-full flex flex-wrap px-2 mx-auto lg:px-72 text-center-center">
            @if(empty($slot))
                @yield('content')
            @else
                {{ $slot }}
            @endif
        </main>
    </div>
</body>
</html>
