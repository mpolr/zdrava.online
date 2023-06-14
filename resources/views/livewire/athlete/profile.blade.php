@extends('layouts.site')
@section('content')
<main class="container mx-auto px-0 py-12">
    <div class="flex">
        <div class="w-1/4">
            <div class="flex items-center justify-center w-full">
                @if ($user->photo)
                    <img src="{{ $user->getPhoto() }}"
                         alt="{{ $user->getFullName() }}"
                         loading="lazy"
                         class="w-32 h-32 mb-3 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" />
                @else
                    <div class="relative inline-flex items-center justify-center w-24 h-24 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                        <span class="font-bold text-3xl text-gray-600 dark:text-gray-300">
                            {{ $user->getInitials() }}
                        </span>
                    </div>
                @endif
                <h2 class="mb-2 mt-0 ml-4 text-4xl font-medium leading-tight text-black">
                    {{ $user->getFullName() }}
                </h2>
            </div>
            <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <div class="w-4/4 ml-4">
            <div class="pl-4">
                @error('profile') @livewire('toast.errors') @enderror
                @if (Session::get('success'))
                    @livewire('toast.success')
                @endif
                @if($user->activities)
                    @foreach($user->activities as $activity)
                        <div class="max-w-full mb-3 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div class="p-4">
                                <div class="flex gap-x-4">
                                    <a href="{{ route('athlete.profile', $activity->getUser()->id) }}">
                                    @if($activity->getUser()->getPhoto())
                                        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{ $activity->getUser()->getPhoto() }}" alt="{{ $activity->getUser()->getFullName() }}">
                                    @else
                                        <div class="relative inline-flex items-center justify-center w-12 h-12 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                        <span class="font-bold text-xl text-gray-600 dark:text-gray-300">
                                            {{ $activity->getUser()->getInitials() }}
                                        </span>
                                        </div>
                                    @endif
                                    </a>
                                    <div class="min-w-0 flex-auto">
                                        <a href="{{ route('athlete.profile', $activity->getUser()->id) }}">
                                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $activity->getUser()->getFullName() }}</p>
                                        </a>
                                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">
                                            {{ $activity->getLongStartDate() }} - {{ $activity->getCountry() }}@if($activity->locality), {{ $activity->locality }}@endif
                                        </p>
                                        <a href="{{ route('activities.get', $activity->id) }}">
                                            <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                                {{ $activity->name }}
                                            </h5>
                                        </a>
                                        <div class="grid text-sm mt-4 grid-cols-1 gap-6 sm:grid-cols-3">
                                            <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                {{ __('Distance') }}<br />
                                                <span class="text-black text-lg font-semibold">{{ __(':distance km', ['distance' => $activity->getDistance()]) }}</span>
                                            </p>
                                            <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                {{ __('Elevation') }}<br />
                                                <span class="text-black text-lg font-semibold">{{ __(':elevation m', ['elevation' => $activity->elevation_gain]) }}</span>
                                            </p>
                                            <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                {{ __('Duration') }}<br />
                                                <span class="text-black text-lg font-semibold">{{ $activity->getDuration() }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div>

                                </div>
                            </div>
                            <div class="p-0">
                                <a href="#">
                                    <img class="max-h-60" src="{{ $activity->getImage() }}" alt="" />
                                </a>
                            </div>
                            <div class="p-0">

                            </div>
                        </div>
                    @endforeach
                @else
                    // TODO: Нет тренировок
                @endif
            </div>
        </div>
        <div class="w-1/4">

        </div>
    </div>
</main>
@endsection
