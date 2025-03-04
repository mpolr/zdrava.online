<!DOCTYPE html>

<html
    @if(session()->get('theme') === 'dark')
        class="dark"
    @endif
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>
<head>
    @if (session()->get('theme') !== 'dark' || session()->get('theme') !== 'light')
        <script type="text/javascript" nonce="{{ csp_nonce() }}">
            const getPreferredScheme = () => window?.matchMedia?.('(prefers-color-scheme:dark)')?.matches ? 'dark' : 'light';
            if (getPreferredScheme() === 'dark') {
                document.querySelector('html').classList.add("dark");
            }
        </script>
    @endif
    <meta charset="utf-8">
    <meta name="theme-color" content="#ff6600" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#ff6600">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'Социальная сеть для спортсменов. Бег, велоспорт, сап борд и многое другое. Загружай тренировки, общайся, заводи новых друзей.')" />
    {{-- Open graph --}}
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Zdrava - ' . __('Social network for athletes'))">
    <meta property="og:description" content="@yield('description', 'Социальная сеть для спортсменов. Бег, велоспорт, сап борд и многое другое. Загружай тренировки, общайся, заводи новых друзей.')">
    <meta property="og:image" content="{{ config('app.url') }}/zdrava.png">
    {{-- Telegram InstaView --}}
    <meta property="telegram:channel" content="@zdrava_online">
    <meta property="tg:site_verification" content="g7j8/rPFXfhyrq5q0QQV7EsYWv4=">
    <link rel="icon" href="{{ asset('favicon.svg') }}" sizes="any" type="image/svg+xml">
    <title>@yield('title', 'Zdrava - ' . __('Social network for athletes'))</title>
    {{-- Fonts --}}
    @googlefonts('ubuntu')
    {{-- Styles --}}
    @vite('resources/css/app.css')
    @livewireStyles(['nonce' => csp_nonce()])
    {{-- Scripts --}}
    @vite('resources/js/app.js')
    @livewireScripts(['nonce' => csp_nonce()])
    @yield('js')
    {{-- Matomo --}}
    <script nonce="{{ csp_nonce() }}">
        window.user_id = {!! auth()->check()?auth()->user()->id:'null' !!};
        var _paq = window._paq = window._paq || [];
        @if(!empty(auth()->id()))
        _paq.push(['setUserId', '{{ auth()->user()->getFullName() }} (ID: {{ auth()->id() }})']);
        @endif
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
    {{-- End Matomo Code --}}
</head>
<body class="bg-gray-100 dark:bg-gray-700 min-h-screen flex flex-col">
    <div>
        <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="px-3 py-2 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-start">
                        @if(!empty(auth()->user()))
                        <button data-drawer-target="zdrava-sidebar" data-drawer-toggle="zdrava-sidebar" aria-controls="zdrava-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                            <span class="sr-only">Open sidebar</span>
                            <svg fill="currentColor" aria-hidden="true" class="w-6 h-6" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75zm0 10.5a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1-.75-.75zM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        @endif
                        <a href="{{ route('index') }}" class="flex ml-2 md:mr-24">
                            <img src="{{ asset('favicon.svg') }}" class="h-8 mr-3" alt="{{ __('Zdrava') }}" />
                            <span class="self-center text-xl font-semibold xs:text-xl sm:text-2xl whitespace-nowrap text-orange-600 dark:text-white">{{ __('Zdrava') }}</span>
                        </a>
                    </div>
                    @auth
                        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-2">
                            <div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
                                <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 md:mt-0 md:border-0 bg-white border-b border-gray-200 dark:bg-gray-800">
                                    <li>
                                        <button id="buttonWorkouts" data-dropdown-toggle="buttonWorkoutsDropdownNavbar" class="flex items-center justify-between w-full py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                                            {{ __('Workout') }} <svg fill="currentColor" aria-hidden="true" class="w-5 h-5 ml-1" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z" clip-rule="evenodd"/></svg>
                                        </button>
                                        <div id="buttonWorkoutsDropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                                                <li>
                                                    <a href="{{ route('athlete.calendar') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        {{ __('Training calendar') }}
                                                    </a>
                                                </li>
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
                    <div class="flex items-center">
                        <div class="flex items-center ml-3">
                            @auth
                                <div class="group relative cursor-pointer py-1">
                                    <div class="flex items-center justify-between space-x-5 px-4 h-8 bg-white border-gray-200 dark:bg-gray-800">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="dark:text-white w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="invisible absolute z-50 flex right-0 w-56 flex-col bg-gray-100 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white py-1 px-4 text-gray-800 shadow-xl group-hover:visible" onClick="">
                                        <a href="{{ route('upload.workout') }}" class="my-2 py-1 text-gray-500 hover:text-black dark:text-white dark:hover:text-white md:mx-2">
                                            {{ __('Upload workout') }}
                                        </a>
                                    </div>
                                </div>
                                @if (auth()->user()->subscribers()->where('confirmed', 0)->count() > 0)
                                    <div class="group relative cursor-pointer py-1">
                                        <div class="flex items-center justify-between space-x-5 px-4 h-8 bg-white border-gray-200 dark:bg-gray-800">
                                            <span>
                                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 stroke-gray-700 dark:stroke-gray-100" xmlns="http://www.w3.org/2000/svg">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                                                </svg>
                                            </span>
                                            <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-orange-500 border-2 border-white rounded-full -top-1 -right-0 dark:border-gray-900">
                                                {{ auth()->user()->subscribers()->where('confirmed', 0)->count() }}
                                            </div>
                                        </div>
                                        <div class="invisible absolute z-50 flex right-0 w-56 flex-col bg-gray-100 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white py-1 px-4 text-gray-800 shadow-xl group-hover:visible" onClick="">
                                            <a href="{{ route('friends.requests') }}" class="my-2 block py-1 text-black dark:text-gray-100 md:mx-2">
                                                {{ __('Subscription requests: :count', ['count' => auth()->user()->subscribers()->where('confirmed', 0)->count()]) }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                <div class="group relative cursor-pointer py-1">
                                    <div class="flex items-center justify-between space-x-1 pl-6 bg-white border-gray-200 dark:bg-gray-800">
                                        <a class="menu-hover text-base font-medium text-black lg:mx-0" onClick="">
                                            @if (auth()->user()->getPhoto())
                                                <img src="{{ auth()->user()->getPhoto() }}"
                                                     alt="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}"
                                                     loading="lazy"
                                                     class="min-w-9 min-h-9 w9 h-9 p-1 rounded-full ring-1 ring-gray-300 dark:ring-gray-500" />
                                            @else
                                                <div class="relative inline-flex items-center justify-center w-9 h-9 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">
                                                        {{ auth()->user()->getInitials() }}
                                                    </span>
                                                </div>
                                            @endif
                                        </a>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="dark:text-gray-400 w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="invisible absolute z-50 flex right-0 w-48 flex-col bg-gray-100 dark:bg-gray-700 py-1 px-4 shadow-xl group-hover:visible">
                                        <a href="{{ route('friends.find') }}" class="my-2 block border-b border-gray-100 dark:text-gray-100 dark:border-gray-700 py-1 text-success hover:text-success-700 md:mx-2">
                                            {{ __('Find friends') }}
                                        </a>
                                        <a href="{{ route('settings.profile') }}" class="my-2 block border-b border-gray-100 dark:text-gray-100 dark:border-gray-700 py-1 text-gray-500 hover:text-black md:mx-2">
                                            {{ __('Settings') }}
                                        </a>
                                        @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('admin.index') }}" class="my-2 block border-b border-gray-100 dark:text-gray-100 dark:border-gray-700 py-1 text-red-500 hover:text-red-700 md:mx-2">
                                                {{ __('Admin panel') }}
                                            </a>
                                        @endif
                                        <a href="{{ route('auth.logout') }}" class="font-semibold my-2 block border-b border-gray-100 dark:text-gray-100 dark:border-gray-700 py-1 text-black-500 hover:text-black md:mx-2">
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
                </div>
            </div>
        </nav>
        {{-- Боковая панель навигации --}}
        @if(!empty(auth()->user()))
        <div id="zdrava-sidebar" class="fixed top-16 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="zdrava-sidebar-label">
            <h5 id="zdrava-sidebar-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400">
                {{ __('Navigation') }}
            </h5>
            <button type="button" data-drawer-hide="zdrava-sidebar" aria-controls="zdrava-sidebar" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close menu</span>
            </button>
            <div class="py-4 overflow-y-auto">
                <ul class="space-y-2 font-medium">
                    <li>
                        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                            </svg>
                            <span class="ml-3">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    <li>
                        <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="side-dropdown-workout" data-collapse-toggle="side-dropdown-workout">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="dark:text-white w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ __('Workout') }}</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul id="side-dropdown-workout" class="hidden py-2 space-y-2">
                            <li>
                                <a href="{{ route('athlete.training') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-12 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                    {{ __('My workouts') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('upload.workout') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-12 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                    {{ __('Upload workout') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="side-dropdown-new-items" data-collapse-toggle="side-dropdown-new-items">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ __('New items') }}</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul id="side-dropdown-new-items" class="hidden py-2 space-y-2">
                            <li>
                                <a href="{{ route('segments.explore') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-12 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                    {{ __('Explore segments') }}
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-12 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                    {{ __('Search athletes') }}
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-12 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                    {{ __('Search clubs') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if (auth()->user()->subscribers()->where('confirmed', 0)->count() > 0)
                    <li>
                        <a href="{{ route('friends.requests') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                            </svg>
                            <span class="flex-1 ml-3 whitespace-nowrap">{{ __('Notifications') }}</span>
                            <span class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                {{ auth()->user()->subscribers()->where('confirmed', 0)->count() }}
                            </span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('settings.profile') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                            </svg>
                            <span class="flex-1 ml-3 whitespace-nowrap">{{ __('Settings') }}</span>
                        </a>
                    </li>
                </ul>
                @if(auth()->user()->hasRole('admin'))
                    <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
                        <li>
                            <a href="{{ route('admin.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                                </svg>
                                <span class="flex-1 ml-3 whitespace-nowrap">{{ __('Admin panel') }}</span>
                            </a>
                        </li>
                    </ul>
                @endif
                <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
                    <li>
                        <a href="{{ route('auth.logout') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            <span class="flex-1 ml-3 whitespace-nowrap">{{ __('Logout') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        @endif
    </div>
    <div class="mt-8 mx-auto text-grey-darkest flex-grow">
        <main class="w-screen flex flex-wrap px-2 mx-auto lg:px-8 justify-center">
            @if(empty($slot))
                @yield('content')
            @else
                {{ $slot }}
            @endif
        </main>
    </div>
    <footer class="mt-10 entry-footer">
        <div class="mx-auto w-full max-w-screen-xl">
            <div class="grid grid-cols-2 gap-8 px-4 py-6 lg:py-8 md:grid-cols-4">
                <div class="cat-links">
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">{{ __('New items') }}</h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="{{ route('segments.explore') }}" class=" hover:underline">{{ __('Segments') }}</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('friends.find') }}" class="hover:underline">{{ __('Find friends') }}</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('tools.antplus') }}" class="hover:underline">{{ __('ANT+ sensor test') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="cat-links">
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">{{ __('Download') }}</h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="{{ route('mobile') }}" class="hover:underline">Android</a>
                        </li>
                    </ul>
                </div>
                <div class="cat-links">
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">{{ __('Links') }}</h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="https://vk.com/zdrava.online" class="hover:underline" target="_blank">VK</a>
                        </li>
                        <li class="mb-4">
                            <a href="https://t.me/zdrava_online" class="hover:underline" target="_blank">Telegram</a>
                        </li>
                    </ul>
                </div>
                <div class="cat-links">
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">{{ __('Legal terms') }}</h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="{{ route('legal.privacy') }}" class="hover:underline">{{ __('Privacy Policy') }}</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('legal.terms') }}" class="hover:underline">{{ __('Terms &amp; Conditions') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <x-toaster-hub />
</body>
</html>
