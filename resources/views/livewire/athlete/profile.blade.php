@section('title', $user->getFullName() . ' | Zdrava')
@section('description', 'Профиль пользователя ' . $user->getFullName() . ' в Zdrava')
<div class="container mx-auto px-0 py-12">
    <div class="flex sm:flex-row md:flex-row flex-col gap-4">
        {{-- Левый контейнер --}}
        <div>
            <div class="w-full">
                <div class="flex items-center justify-center w-full">
                    <x-avatar image="{{ $user->getPhoto() }}" name="{{ $user->getFullName() }}" class="w-24 h-24" />
                    <h2 class="mb-2 mt-0 ml-4 text-4xl font-medium leading-tight text-black dark:text-gray-100">
                        {{ $user->getFullName() }}
                    </h2>
                </div>
                <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
                <div class="grid text-sm mt-4 grid-cols-3 gap-6 sm:grid-cols-3">
                    <a href="{{ route('athlete.subscriptions.user', $user->id) }}">
                        <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                            {{ __('Subscriptions') }}<br/>{{ $user->confirmedSubscriptions()->count() }}
                        </p>
                    </a>
                    <a href="{{ route('athlete.subscribers.user', $user->id) }}">
                        <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                            {{ __('Subscribers') }}<br/>{{ $user->confirmedSubscribers()->count() }}
                        </p>
                    </a>
                    <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                        {{ __('Activities') }}<br/>
                        {{ $user->activities->count() ?: 0 }}
                    </p>
                </div>
                @if(!auth()->user()->isSubscriber($user))
                    <div class="grid text-sm mt-4 grid-cols-3 gap-6 sm:grid-cols-3">
                        <div>
                            <button type="button" wire:click=""
                                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                {{ __('Subscribe') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        {{-- Центральный контейнер --}}
        <div class="w-full">
            <div class="w-full">
                @error('profile') @livewire('toast.errors') @enderror
                @if (Session::get('success'))
                    @livewire('toast.success')
                @endif
                @if(count($user->activities->where('status', \App\Models\Activities::DONE)) >= 1)
                    @foreach($user->activities->where('status', \App\Models\Activities::DONE) as $activity)
                        <div
                            class="max-w-full mb-3 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div class="p-4">
                                <div class="flex gap-x-4">
                                    <a href="{{ route('athlete.profile', $activity->getUser()->id) }}">
                                        @if($activity->getUser()->getPhoto())
                                            <img class="h-12 w-12 flex-none rounded-full bg-gray-50"
                                                 src="{{ $activity->getUser()->getPhoto() }}"
                                                 alt="{{ $activity->getUser()->getFullName() }}">
                                        @else
                                            <div
                                                class="relative inline-flex items-center justify-center w-12 h-12 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                        <span class="font-bold text-xl text-gray-600 dark:text-gray-300">
                                            {{ $activity->getUser()->getInitials() }}
                                        </span>
                                            </div>
                                        @endif
                                    </a>
                                    <svg class="w-6 h-6 text-black dark:text-gray-300 dark:stroke-gray-300">
                                        {!! File::get($activity::getSportSvgIcon($activity->sport, $activity->sub_sport)) !!}
                                    </svg>
                                    <div class="min-w-0 flex-auto">
                                        <a href="{{ route('athlete.profile', $activity->getUser()->id) }}">
                                            <p class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">{{ $activity->getUser()->getFullName() }}</p>
                                        </a>
                                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">
                                            {{ $activity->getLongStartDate() }}
                                            - {{ $activity->getCountry() }}@if($activity->locality)
                                                , {{ $activity->locality }}
                                            @endif
                                        </p>
                                        <a href="{{ route('activities.get', $activity->id) }}">
                                            <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                                {{ $activity->name }}
                                            </h5>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ Str::limit($activity->description, 140, ' ...') }}
                                            </p>
                                        </a>
                                        <div class="grid text-sm mt-4 grid-cols-1 gap-6 sm:grid-cols-3">
                                            <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                {{ __('Distance') }}<br/>
                                                <span
                                                    class="text-lg font-semibold">{{ __(':distance km', ['distance' => $activity->getDistance()]) }}</span>
                                            </p>
                                            <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                {{ __('Elevation') }}<br/>
                                                <span
                                                    class="text-lg font-semibold">{{ __(':elevation m', ['elevation' => $activity->elevation_gain]) }}</span>
                                            </p>
                                            <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                {{ __('Duration') }}<br/>
                                                <span
                                                    class="text-lg font-semibold">{{ $activity->getDuration() }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-0">
                                <a href="{{ route('activities.get', $activity->id) }}">
                                    <div id="map_{{ $activity->id }}" x-data="mapComponent({
                                        lat: {{ $activity->getTrackCenter()['lat'] }},
                                        lng: {{ $activity->getTrackCenter()['long'] }},
                                        polyline: '{{ $activity->polyline }}',
                                    })" class="w-full h-[400px] z-0" x-init="init"></div>
                                </a>
                            </div>
                            <div class="p-0">
                                <div class="flex flex-wrap items-center justify-end gap-4" role="group">
                                    <livewire:components.like :model="$activity"/>
                                    <button type="button"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-transparent hover:text-black focus:text-orange-500 dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                                        </svg>
                                        {{ count($activity->comments) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Тренировок нет :( --}}
                    <div>
                        <div
                            class="w-full mb-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex flex-col p-4">
                                <div class="flex flex-col p-4">
                                    <span
                                        class="text-sm text-gray-500 dark:text-gray-400">{{ __('This athlete has no training yet') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
