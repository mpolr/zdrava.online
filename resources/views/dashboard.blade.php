@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 pt-12">
        <div class="flex flex-wrap">
            <div class="w-full lg:w-1/4 lg:max-w-lg">
                <!-- Боковое меню -->
                <div class="w-full mb-6 max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
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
                                <div class="relative inline-flex items-center justify-center w-24 h-24 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                    <span class="font-bold text-3xl text-gray-600 dark:text-gray-300">
                                        {{ Auth::user()->getInitials() }}
                                    </span>
                                </div>
                            </a>
                        @endif
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->nickname }}</span>
                        <div class="grid text-sm mt-4 grid-cols-3 gap-6 sm:grid-cols-3">
                            <a href="{{ route('athlete.subscriptions') }}">
                                <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                                    Подписки<br />{{ Auth::user()->subscriptions()->where('confirmed', 1)->count() }}
                                </p>
                            </a>
                            <a href="{{ route('athlete.subscribers') }}">
                                <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                                    Подписчики<br />{{ Auth::user()->subscribers()->where('confirmed', 1)->count() }}
                                </p>
                            </a>
                            <a href="{{ route('athlete.training') }}">
                                <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                                    Тренировки<br />
                                    {{ Auth::user()->activities->count() ? Auth::user()->activities->count() : 0 }}
                                </p>
                            </a>
                        </div>
                        <div class="text-sm mt-4 ml-4 mr-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Последняя тренировка</span>
                            <p>
                                @if (!empty(auth()->user()->activities()->latest()->first()))
                                <a class="text-large hover-orange" href="{{ route('activities.get', auth()->user()->activities()->latest()->first()->id) }}">
                                    <strong>
                                        {{ auth()->user()->activities()->latest()->first()->name }}
                                    </strong> &blacksquare; <time class="timestamp text-sm">{{ auth()->user()->activities()->latest()->first()->getShortStartDate() }}</time>
                                </a>
                                @else
                                    <strong>-</strong>
                                @endif
                            </p>
                        </div>
{{--                        <div class="flex mt-4 space-x-3 md:mt-6">--}}
{{--                            <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add friend</a>--}}
{{--                            <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-700 dark:focus:ring-gray-700">Message</a>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/4 lg:ml-4">
                @if($activities)
                    @foreach($activities as $activity)
                        <div class="max-w-full mb-3 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div class="p-4">
                                <div class="flex gap-x-4">
                                    @if($activity->getUser()->getPhoto())
                                        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{ $activity->getUser()->getPhoto() }}" alt="{{ $activity->getUser()->getFullName() }}">
                                    @else
                                        <a href="{{ route('settings.profile') }}">
                                            <div class="relative inline-flex items-center justify-center w-12 h-12 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                            <span class="font-bold text-xl text-gray-600 dark:text-gray-300">
                                                {{ Auth::user()->getInitials() }}
                                            </span>
                                            </div>
                                        </a>
                                    @endif
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ $activity->getUser()->getFullName() }}</p>
                                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">
                                            {{ $activity->getLongStartDate() }} - {{ $activity->getCountry() }}@if($activity->locality), {{ $activity->locality }}@endif
                                        </p>
                                        <a href="{{ route('activities.get', $activity->id) }}">
                                            <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                                {{ $activity->name }}
                                            </h5>
                                        </a>
                                        <div class="grid text-sm mt-4 grid-cols-1 gap-6 sm:grid-cols-3">
                                            <a href="{{ route('athlete.subscriptions') }}">
                                                <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                    {{ __('Distance') }}<br />
                                                    <span class="text-black text-lg font-semibold">{{ __(':distance km', ['distance' => $activity->getDistance()]) }}</span>
                                                </p>
                                            </a>
                                            <a href="{{ route('athlete.subscribers') }}">
                                                <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                    {{ __('Elevation') }}<br />
                                                    <span class="text-black text-lg font-semibold">{{ __(':elevation m', ['elevation' => $activity->elevation_gain]) }}</span>
                                                </p>
                                            </a>
                                            <a href="{{ route('athlete.training') }}">
                                                <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                    {{ __('Duration') }}<br />
                                                    <span class="text-black text-lg font-semibold">{{ $activity->getDuration() }}</span>
                                                </p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div>

                                </div>
                            </div>
                            <a href="#">
                                <img class="max-h-60" src="{{ $activity->getImage() }}" alt="" />
                            </a>
                            <div class="p-0">

                            </div>
                        </div>
                    @endforeach
                @else
                    Нет тренировок
                @endif
            </div>
            <div class="w-full lg:w-1/4">

            </div>
        </div>
    </main>
@endsection
