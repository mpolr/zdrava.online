@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12">
        <div class="flex">
            <div class="w-2/4 lg:w-3/12 px-4">
                <!-- Боковое меню -->
                <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-end px-4 pt-4">
                        <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5" type="button">
                            <span class="sr-only">Open dropdown</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdown" class="z-10 hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                            <ul class="py-2" aria-labelledby="dropdownButton">
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Export Data</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex flex-col items-center pb-10">
                        @if (Auth::user()->photo)
                            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ Auth::user()->getPhoto() }}" alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}"/>
                        @else
                            <a href="{{ route('settings.profile') }}">
                                <div class="relative inline-flex items-center justify-center w-24 h-24 mb-3 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                                    <span class="font-bold text-3xl text-gray-600 dark:text-gray-300">
                                        {{ Str::limit(Auth::user()->first_name, 1, '') }}{{ Str::limit(Auth::user()->last_name, 1, '') }}
                                    </span>
                                </div>
                            </a>
                        @endif
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->nickname }}</span>
                        <div class="grid text-sm mt-4 grid-cols-1 gap-6 sm:grid-cols-3">
                            <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                                Подписки<br />0
                            </p>
                            <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                                Подписчики<br />0
                            </p>
                            <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                                Тренировки<br />
                                {{ \App\Models\Activities::where(['user_id' => Auth::user()->id])->count() }}
                            </p>
                        </div>
                        <div class="text-sm mt-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Последняя тренировка</span>
                            <p>
                                <a class="text-large hover-orange" href="{{ route('activities.get', $activities->latest()->first()->id) }}">
                                    <strong>
                                        {{ $activities->latest()->first()->name }}
                                    </strong> &blacksquare; <time class="timestamp text-sm">{{ $activities->latest()->first()->getShortStartDate() }}</time>
                                </a>
                            </p>
                        </div>
{{--                        <div class="flex mt-4 space-x-3 md:mt-6">--}}
{{--                            <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add friend</a>--}}
{{--                            <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-700 dark:focus:ring-gray-700">Message</a>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
            <div class="w-2/4">
                <!-- Контент и графики -->
                <p>
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eligendi harum tempore
                    cupiditate asperiores provident, itaque, quo ex iusto rerum voluptatum delectus corporis
                    quisquam maxime a ipsam nisi sapiente qui optio! Dignissimos harum quod culpa officiis
                    suscipit soluta labore! Expedita quas, nesciunt similique autem, sunt, doloribus pariatur
                    maxime qui sint id enim. Placeat, maxime labore. Dolores ex provident ipsa impedit, omnis
                    magni earum. Sed fuga ex ducimus consequatur corporis, architecto nesciunt vitae ipsum
                    consequuntur perspiciatis nulla esse voluptatem quos dolorum delectus similique eum vero
                    in est velit quasi pariatur blanditiis incidunt quam.
                </p>
            </div>
            <div class="w-1/4 lg:w-3/12 px-4">
                <!-- Боковое меню -->
                <p>
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eligendi harum tempore
                    cupiditate asperiores provident, itaque, quo ex iusto rerum voluptatum delectus corporis
                    quisquam maxime a ipsam nisi sapiente qui optio! Dignissimos harum quod culpa officiis
                    suscipit soluta labore! Expedita quas, nesciunt similique autem, sunt, doloribus pariatur
                    maxime qui sint id enim. Placeat, maxime labore. Dolores ex provident ipsa impedit, omnis
                    magni earum. Sed fuga ex ducimus consequatur corporis, architecto nesciunt vitae ipsum
                    consequuntur perspiciatis nulla esse voluptatem quos dolorum delectus similique eum vero
                    in est velit quasi pariatur blanditiis incidunt quam.
                </p>
            </div>
        </div>
    </main>
@endsection
